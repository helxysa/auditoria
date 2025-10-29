<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RedmineService
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.redmine.url'), '/');
        $this->apiKey = config('services.redmine.api_key');

        if (empty($this->baseUrl) || empty($this->apiKey)) {
            throw new \Exception('Configurações do Redmine não encontradas. Verifique REDMINE_URL e REDMINE_API_KEY no arquivo .env');
        }
    }

    /**
     * Retorna uma instância configurada do HTTP Client
     */
    private function getClient()
    {
        return Http::withHeaders([
            'X-Redmine-API-Key' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->baseUrl($this->baseUrl);
    }
    /**
     * Obtém todas as não conformidades ativas do Redmine agrupadas por projeto
     *
     * @return array
     * @throws \Exception
     */
    public function getNaoConformidadesStats(): array
    {
        try {
            $allIssues = [];
            $offset = 0;
            $limit = 100; // Máximo permitido pela API

            do {
                $response = $this->getClient()->get('/issues.json', [
                    'limit' => $limit,
                    'offset' => $offset,
                    'status_id' => 'open', // Apenas issues abertas/ativas
                    'tracker_id' => $this->getTrackerIdByName('Não conformidade'),
                ]);

                if ($response->failed()) {
                    Log::error('Erro ao obter não conformidades do Redmine', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                    throw new \Exception('Falha ao obter não conformidades do Redmine: ' . $response->status());
                }

                $data = $response->json();
                $issues = $data['issues'] ?? [];
                $totalCount = $data['total_count'] ?? 0;

                $allIssues = array_merge($allIssues, $issues);
                $offset += $limit;
            } while (count($allIssues) < $totalCount && count($issues) > 0);

            $groupedByProject = [];
            foreach ($allIssues as $issue) {
                $projectName = $issue['project']['name'] ?? 'Sem Projeto';

                if (!isset($groupedByProject[$projectName])) {
                    $groupedByProject[$projectName] = 0;
                }
                $groupedByProject[$projectName]++;
            }

            $chartData = [];
            foreach ($groupedByProject as $projectName => $count) {
                $chartData[] = [
                    'name' => $projectName,
                    'count' => $count,
                ];
            }

            // Ordena por quantidade decrescente
            usort($chartData, function ($a, $b) {
                return $b['count'] - $a['count'];
            });

            return $chartData;
        } catch (\Exception $error) {
            Log::error('Erro ao obter não conformidades do Redmine', [
                'message' => $error->getMessage(),
                'trace' => $error->getTraceAsString(),
            ]);
            throw $error;
        }
    }

    /**
     * Obtém o ID do tracker pelo nome
     *
     * @param string $trackerName
     * @return int|null
     * @throws \Exception
     */
    private function getTrackerIdByName(string $trackerName): ?int
    {
        try {
            $response = $this->getClient()->get('/trackers.json');

            if ($response->failed()) {
                Log::error('Erro ao obter trackers do Redmine', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            $trackers = $response->json('trackers', []);

            foreach ($trackers as $tracker) {
                if (strcasecmp($tracker['name'], $trackerName) === 0) {
                    return $tracker['id'];
                }
            }

            Log::warning("Tracker '{$trackerName}' não encontrado no Redmine");
            return null;
        } catch (\Exception $error) {
            Log::error('Erro ao buscar tracker no Redmine', [
                'message' => $error->getMessage(),
            ]);
            return null;
        }
    }


    /**
     * Lista todos os projetos principais ativos disponíveis no Redmine (sem subprojetos)
     *
     * @return array
     * @throws \Exception
     */
    public function getProjects(): array
    {
        try {
            $allProjects = [];
            $offset = 0;
            $limit = 100;
            do {
                $response = $this->getClient()->get('/projects.json', [
                    'limit' => $limit,
                    'offset' => $offset,
                    'status' => 1,
                ]);

                if ($response->failed()) {
                    Log::error('Erro ao obter projetos do Redmine', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                    throw new \Exception('Falha ao obter projetos do Redmine: ' . $response->status());
                }

                $data = $response->json();
                $projects = $data['projects'] ?? [];
                $totalCount = $data['total_count'] ?? 0;

                $allProjects = array_merge($allProjects, $projects);
                $offset += $limit;

                // Continua até buscar todos os projetos
            } while (count($allProjects) < $totalCount && count($projects) > 0);

            // Filtra apenas projetos principais (sem pai)
            $mainProjects = array_filter($allProjects, function ($project) {
                return !isset($project['parent']) || empty($project['parent']);
            });

            return array_values($mainProjects);
        } catch (\Exception $error) {
            Log::error('Erro ao obter projetos do Redmine', [
                'message' => $error->getMessage(),
                'trace' => $error->getTraceAsString(),
            ]);
            throw $error;
        }
    }

    /**
     * Obtém um projeto específico por ID
     *
     * @param int $projectId
     * @return array|null
     * @throws \Exception
     */
    public function getProject(int $projectId): ?array
    {
        try {
            $response = $this->getClient()->get("/projects/{$projectId}.json");

            if ($response->failed()) {
                if ($response->status() === 404) {
                    return null;
                }

                Log::error('Erro ao obter projeto do Redmine', [
                    'project_id' => $projectId,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new \Exception('Falha ao obter projeto do Redmine: ' . $response->status());
            }

            return $response->json('project');
        } catch (\Exception $error) {
            Log::error('Erro ao obter projeto do Redmine', [
                'project_id' => $projectId,
                'message' => $error->getMessage(),
            ]);
            throw $error;
        }
    }
}

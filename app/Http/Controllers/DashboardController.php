<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\NaoConformidade;
use App\Models\TipoAuditoria;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * TTL do cache em segundos (15 minutos)
     */
    private const CACHE_TTL = 900;

    /**
     * Retorna todas as métricas do dashboard
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return Cache::remember('dashboard_metrics', self::CACHE_TTL, function () {
            return response()->json([
                'total_auditorias' => $this->getTotalAuditorias(),
                'auditorias_por_tipo' => $this->getAuditoriasPorTipo(),
                'distribuicao_nao_conformidades' => $this->getDistribuicaoNaoConformidades(),
                'nao_conformidades_por_tipo' => $this->getNaoConformidadesPorTipo(),
                'media_processo_produto_por_tipo' => $this->getMediaProcessoProdutoPorTipo(),
                'timeline_auditorias' => $this->getTimelineAuditorias(),
                'top_nao_conformidades' => $this->getTopNaoConformidades(),
                'distribuicao_por_analista' => $this->getDistribuicaoPorAnalista(),
            ]);
        });
    }

    /**
     * Retorna o total de auditorias realizadas
     *
     * @return int
     */
    private function getTotalAuditorias(): int
    {
        return Auditoria::count();
    }

    /**
     * Retorna a quantidade de auditorias agrupadas por tipo
     *
     * Formato: [
     *   {tipo: 'Nome do Tipo', quantidade: 10},
     *   ...
     * ]
     *
     * @return array
     */
    private function getAuditoriasPorTipo(): array
    {
        return TipoAuditoria::select('tipo_auditorias.nome as tipo')
            ->withCount('auditorias as quantidade')
            ->orderByDesc('quantidade')
            ->get()
            ->toArray();
    }

    /**
     * Retorna a distribuição de auditorias por número de não conformidades
     *
     * Formato: [
     *   {label: '0 NC', quantidade: 5},
     *   {label: '1-3 NC', quantidade: 10},
     *   {label: '4-6 NC', quantidade: 8},
     *   {label: '7+ NC', quantidade: 3}
     * ]
     *
     * @return array
     */
    private function getDistribuicaoNaoConformidades(): array
    {
        $resultado = Auditoria::select('auditorias.id')
            ->withCount('naoConformidades')
            ->get()
            ->groupBy(function ($auditoria) {
                $count = $auditoria->nao_conformidades_count;
                if ($count === 0) return '0 NC';
                if ($count <= 3) return '1-3 NC';
                if ($count <= 6) return '4-6 NC';
                return '7+ NC';
            })
            ->map(fn($group) => $group->count())
            ->toArray();

        // Garantir que todas as categorias existam
        $categorias = ['0 NC', '1-3 NC', '4-6 NC', '7+ NC'];
        $distribuicao = [];

        foreach ($categorias as $categoria) {
            $distribuicao[] = [
                'label' => $categoria,
                'quantidade' => $resultado[$categoria] ?? 0
            ];
        }

        return $distribuicao;
    }

    /**
     * Retorna a quantidade de não conformidades por tipo de auditoria
     *
     * Formato: [
     *   {tipo: 'Nome do Tipo', total_nao_conformidades: 25},
     *   ...
     * ]
     *
     * @return array
     */
    private function getNaoConformidadesPorTipo(): array
    {
        return DB::table('tipo_auditorias')
            ->select(
                'tipo_auditorias.nome as tipo',
                DB::raw('COUNT(DISTINCT auditoria_nao_conformidade.nao_conformidade_id) as total_nao_conformidades')
            )
            ->leftJoin('auditorias', 'tipo_auditorias.id', '=', 'auditorias.tipo_auditorias_id')
            ->leftJoin('auditoria_nao_conformidade', 'auditorias.id', '=', 'auditoria_nao_conformidade.auditoria_id')
            ->groupBy('tipo_auditorias.id', 'tipo_auditorias.nome')
            ->orderByDesc('total_nao_conformidades')
            ->get()
            ->toArray();
    }

    /**
     * Retorna a média de processo e produto por tipo de auditoria
     *
     * Formato: [
     *   {
     *     tipo: 'Nome do Tipo',
     *     media_processo: 85.5,
     *     media_produto: 90.2
     *   },
     *   ...
     * ]
     *
     * @return array
     */
    private function getMediaProcessoProdutoPorTipo(): array
    {
        return DB::table('tipo_auditorias')
            ->select(
                'tipo_auditorias.nome as tipo',
                DB::raw('ROUND(AVG(auditorias.processo), 2) as media_processo'),
                DB::raw('ROUND(AVG(auditorias.produto), 2) as media_produto')
            )
            ->leftJoin('auditorias', 'tipo_auditorias.id', '=', 'auditorias.tipo_auditorias_id')
            ->groupBy('tipo_auditorias.id', 'tipo_auditorias.nome')
            ->havingRaw('COUNT(auditorias.id) > 0')
            ->get()
            ->map(function ($item) {
                return [
                    'tipo' => $item->tipo,
                    'media_processo' => (float) $item->media_processo,
                    'media_produto' => (float) $item->media_produto,
                ];
            })
            ->toArray();
    }

    /**
     * Retorna a timeline de auditorias nos últimos 6 meses
     *
     * Formato: [
     *   {mes: '2024-05', quantidade: 15},
     *   {mes: '2024-06', quantidade: 20},
     *   ...
     * ]
     *
     * @return array
     */
    private function getTimelineAuditorias(): array
    {
        $sixMonthsAgo = now()->subMonths(6)->startOfMonth();

        $timeline = Auditoria::select(
                DB::raw("TO_CHAR(created_at, 'YYYY-MM') as mes"),
                DB::raw('COUNT(*) as quantidade')
            )
            ->where('created_at', '>=', $sixMonthsAgo)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->toArray();

        // Garantir que todos os meses tenham dados (mesmo que zero)
        $resultado = [];
        $currentDate = $sixMonthsAgo->copy();
        $timelineMap = collect($timeline)->keyBy('mes');

        for ($i = 0; $i < 6; $i++) {
            $mesKey = $currentDate->format('Y-m');
            $resultado[] = [
                'mes' => $mesKey,
                'quantidade' => (int) ($timelineMap->get($mesKey)['quantidade'] ?? 0)
            ];
            $currentDate->addMonth();
        }

        return $resultado;
    }

    /**
     * Retorna as 10 não conformidades mais encontradas
     *
     * Formato: [
     *   {
     *     sigla: 'NC-001',
     *     descricao: 'Descrição da não conformidade',
     *     tipo_auditoria: 'Nome do Tipo',
     *     ocorrencias: 15
     *   },
     *   ...
     * ]
     *
     * @return array
     */
    private function getTopNaoConformidades(): array
    {
        return NaoConformidade::select(
                'nao_conformidades.sigla',
                'nao_conformidades.descricao',
                'tipo_auditorias.nome as tipo_auditoria'
            )
            ->withCount('auditorias as ocorrencias')
            ->leftJoin('tipo_auditorias', 'nao_conformidades.tipo_auditoria_id', '=', 'tipo_auditorias.id')
            ->orderByDesc('ocorrencias')
            ->limit(10)
            ->get()
            ->toArray();
    }

    /**
     * Retorna a distribuição de auditorias por analista responsável
     *
     * Formato: [
     *   {analista: 'Nome do Analista', quantidade: 25},
     *   ...
     * ]
     *
     * @return array
     */
    private function getDistribuicaoPorAnalista(): array
    {
        return Auditoria::select('analista_responsavel as analista')
            ->selectRaw('COUNT(*) as quantidade')
            ->whereNotNull('analista_responsavel')
            ->groupBy('analista_responsavel')
            ->orderByDesc('quantidade')
            ->get()
            ->toArray();
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AuditoriaCompletaSeeder extends Seeder
{
    // Mapeamento de IDs criados
    private array $tiposAuditoriaIds = [];
    private array $analistasIds = [];
    private array $naoConformidadesIds = [];
    private array $auditoriasIds = [];

    // Constantes
    private const TIPOS_AUDITORIA = [
        'Produto' => 'produto',
        'Serviço' => 'servico',
        'Qualidade' => 'qualidade',
    ];

    // Dados dos analistas
    private array $analistas = [
        ['name' => 'Aymmée Silva', 'email' => 'aymmee.silva@auditor.com', 'cargo' => 'analista', 'identificador' => 'AYMMEE'],
        ['name' => 'Francimara Santos', 'email' => 'francimara.santos@auditor.com', 'cargo' => 'analista', 'identificador' => 'FRANCIMARA'],
        ['name' => 'Isabelle Costa', 'email' => 'isabelle.costa@auditor.com', 'cargo' => 'analista', 'identificador' => 'ISABELLE'],
        ['name' => 'Amanda Oliveira', 'email' => 'amanda.oliveira@auditor.com', 'cargo' => 'analista', 'identificador' => 'AMANDA'],
        ['name' => 'André Souza', 'email' => 'andre.souza@auditor.com', 'cargo' => 'analista', 'identificador' => 'ANDRE'],
        ['name' => 'Adriano Lima', 'email' => 'adriano.lima@auditor.com', 'cargo' => 'analista', 'identificador' => 'ADRIANO'],
        ['name' => 'Cleberson Alves', 'email' => 'cleberson.alves@auditor.com', 'cargo' => 'analista', 'identificador' => 'CLEBERSON'],
        ['name' => 'Diego Pereira', 'email' => 'diego.pereira@auditor.com', 'cargo' => 'analista', 'identificador' => 'DIEGO'],
        ['name' => 'Kleverton Rocha', 'email' => 'kleverton.rocha@auditor.com', 'cargo' => 'analista', 'identificador' => 'KLEVERTON'],
        ['name' => 'Equipe QA', 'email' => 'equipe.qa@auditor.com', 'cargo' => 'analista', 'identificador' => 'EQUIPE_QA'],
    ];

    // Dados das não conformidades
    private array $naoConformidades = [
        // PRODUTO - Peer Review
        ['sigla' => 'PR 1', 'descricao' => 'Falta revisão por pares', 'tipo' => 'Produto'],
        ['sigla' => 'PR 2', 'descricao' => 'Falta revisão por pares', 'tipo' => 'Produto'],
        ['sigla' => 'PR 3', 'descricao' => 'Falta revisão por pares', 'tipo' => 'Produto'],

        // PRODUTO - Gestão de Projeto
        ['sigla' => 'GPR 1', 'descricao' => 'Ata da planning não está preenchida corretamente', 'tipo' => 'Produto'],
        ['sigla' => 'GPR 2', 'descricao' => 'Ata da planning não preenchida', 'tipo' => 'Produto'],
        ['sigla' => 'GPR 3', 'descricao' => 'Ata da planning não preenchida', 'tipo' => 'Produto'],
        ['sigla' => 'GPR 4', 'descricao' => 'Preenchimento da viabilidade na planning', 'tipo' => 'Produto'],
        ['sigla' => 'GPR 5', 'descricao' => 'Análise de marco não preenchida', 'tipo' => 'Produto'],
        ['sigla' => 'GPR 6', 'descricao' => 'Análise de marco não preenchida', 'tipo' => 'Produto'],
        ['sigla' => 'GPR 7', 'descricao' => 'Ata da review não preenchida', 'tipo' => 'Produto'],

        // PRODUTO - Risk Management
        ['sigla' => 'RSKM 1', 'descricao' => 'Planilha de riscos não preenchida', 'tipo' => 'Produto'],
        ['sigla' => 'RSKM 2', 'descricao' => 'Planilha de riscos não preenchida', 'tipo' => 'Produto'],
        ['sigla' => 'RSKM 3', 'descricao' => 'Planilha de riscos não preenchida', 'tipo' => 'Produto'],
        ['sigla' => 'RSKM 4', 'descricao' => 'Planilha de riscos não preenchida', 'tipo' => 'Produto'],
        ['sigla' => 'RSKM 5', 'descricao' => 'Falta preenchimento correto do relatório de marco', 'tipo' => 'Produto'],
        ['sigla' => 'RSKM 6', 'descricao' => 'Tarefa relacionada a subtarefa na análise de marco', 'tipo' => 'Produto'],
        ['sigla' => 'RSKM 7', 'descricao' => 'Sem planilha de riscos', 'tipo' => 'Produto'],

        // PRODUTO - Verificação e Validação
        ['sigla' => 'VV 1', 'descricao' => 'Indicação de pontos para teste', 'tipo' => 'Produto'],
        ['sigla' => 'VV 2', 'descricao' => 'Os critérios de aceitação não foram definidos', 'tipo' => 'Produto'],
        ['sigla' => 'VV 3', 'descricao' => 'Tarefa de executar e especificar testes', 'tipo' => 'Produto'],
        ['sigla' => 'VV 6', 'descricao' => 'Falta preenchimento correto do relatório de marco', 'tipo' => 'Produto'],

        // PRODUTO - Requisitos
        ['sigla' => 'REQ 2', 'descricao' => 'Tarefa de especificar H.U não preenchida', 'tipo' => 'Produto'],
        ['sigla' => 'REQ 3', 'descricao' => 'Tarefa de especificar H.U não preenchida', 'tipo' => 'Produto'],
        ['sigla' => 'REQ 4', 'descricao' => 'Tarefa de especificar H.U não preenchida', 'tipo' => 'Produto'],
        ['sigla' => 'REQ 6', 'descricao' => 'Falta cenário de teste / tarefa de especificar H.U', 'tipo' => 'Produto'],
        ['sigla' => 'REQ 7', 'descricao' => 'Tarefa de especificar H.U não preenchida', 'tipo' => 'Produto'],
        ['sigla' => 'REQ 8', 'descricao' => 'Interface DINT de software', 'tipo' => 'Produto'],
        ['sigla' => 'REQ 9', 'descricao' => 'Pontos MSB não preenchidos na H.U ao término da sprint', 'tipo' => 'Produto'],
        ['sigla' => 'REQ 10', 'descricao' => 'Não possui o indicador de tempo gasto com as horas de desenvolvimento', 'tipo' => 'Produto'],

        // PRODUTO - Versões com prefixo "PRODUTO"
        ['sigla' => 'PRODUTO PR 1', 'descricao' => 'Falta revisão por pares precisa está correto e completo', 'tipo' => 'Produto'],
        ['sigla' => 'PRODUTO PR 2', 'descricao' => 'Falta revisão por pares precisa está correto e completo', 'tipo' => 'Produto'],
        ['sigla' => 'PRODUTO GPR 1', 'descricao' => 'Precisa está correto e completo do GRP', 'tipo' => 'Produto'],
        ['sigla' => 'PRODUTO GPR 2', 'descricao' => 'Precisa está correto e completo do GRP', 'tipo' => 'Produto'],
        ['sigla' => 'PRODUTO GPR 3', 'descricao' => 'Ata da review precisa está correto e completo', 'tipo' => 'Produto'],
        ['sigla' => 'PRODUTO GPR 4', 'descricao' => 'Ata da review precisa está correto e completo do GRP', 'tipo' => 'Produto'],
        ['sigla' => 'PRODUTO GPR 5', 'descricao' => 'Precisa está correto e completo', 'tipo' => 'Produto'],
        ['sigla' => 'PRODUTO GPR 6', 'descricao' => 'Precisa está correto e completo', 'tipo' => 'Produto'],
        ['sigla' => 'PRODUTO RSKM 1', 'descricao' => 'Planilha de riscos não está correta e nem preenchida', 'tipo' => 'Produto'],
        ['sigla' => 'PRODUTO RSKM 2', 'descricao' => 'Planilha de riscos não está correta e nem preenchida', 'tipo' => 'Produto'],
        ['sigla' => 'PRODUTO VV 1', 'descricao' => 'Falta cenário de teste', 'tipo' => 'Produto'],
        ['sigla' => 'PRODUTO VV 2', 'descricao' => 'Falta cenários de testes em produto', 'tipo' => 'Produto'],
        ['sigla' => 'PRODUTO VV TS 1', 'descricao' => 'Falta critério de aceitação', 'tipo' => 'Produto'],
        ['sigla' => 'PRODUTO VV TS 2', 'descricao' => 'Falta critério de aceitação', 'tipo' => 'Produto'],
        ['sigla' => 'PRODUTO REQ 1', 'descricao' => 'Tarefa de especificar H.U não preenchida', 'tipo' => 'Produto'],
        ['sigla' => 'PRODUTO REQ 2', 'descricao' => 'Tarefa de especificar H.U não preenchida', 'tipo' => 'Produto'],
        ['sigla' => 'PRODUTO REQ 3', 'descricao' => 'O apontamento de pontos MSB e horas em tempo gasto no desenvolvimento', 'tipo' => 'Produto'],
        ['sigla' => 'PRODUTO REQ 4', 'descricao' => 'O apontamento de pontos MSB e horas em tempo gasto no desenvolvimento', 'tipo' => 'Produto'],
        ['sigla' => 'PRODUTO REQ 5', 'descricao' => 'Definição de software não estão corretos e nem completos', 'tipo' => 'Produto'],
        ['sigla' => 'PRODUTO REQ 6', 'descricao' => 'Definição de software não estão corretos e nem completos', 'tipo' => 'Produto'],
        ['sigla' => 'PRODUTO REQ TS 5', 'descricao' => 'Definição de software', 'tipo' => 'Produto'],
        ['sigla' => 'PRODUTO REQ TS 6', 'descricao' => 'Definição de software', 'tipo' => 'Produto'],

        // QUALIDADE - Gestão Planejamento Treinamento
        ['sigla' => 'GPT 1', 'descricao' => 'Plano padrão de testes não está atualizado', 'tipo' => 'Qualidade'],
        ['sigla' => 'GPT 2', 'descricao' => 'Plano padrão de teste não está atualizado', 'tipo' => 'Qualidade'],
        ['sigla' => 'GPT 4', 'descricao' => 'Ticket de planejamento da sprint não totalmente preenchido', 'tipo' => 'Qualidade'],
        ['sigla' => 'GPT 5', 'descricao' => 'Método de estimativa, tamanho, esforço não preenchido', 'tipo' => 'Qualidade'],
        ['sigla' => 'GPT 6', 'descricao' => 'Documentação do planejamento não preenchida', 'tipo' => 'Qualidade'],
        ['sigla' => 'GPT 7', 'descricao' => 'Problema repositório do GitLab / planilha de risco', 'tipo' => 'Qualidade'],
        ['sigla' => 'GPT 8', 'descricao' => 'Ticket de treinamento não inserido', 'tipo' => 'Qualidade'],
        ['sigla' => 'GPT 10', 'descricao' => 'Não possui ticket de monitoramento da sprint', 'tipo' => 'Qualidade'],
        ['sigla' => 'GPT 11', 'descricao' => 'Ticket de monitoramento inexistente', 'tipo' => 'Qualidade'],
        ['sigla' => 'GPT 12', 'descricao' => 'Ticket de monitoramento não preenchido', 'tipo' => 'Qualidade'],
        ['sigla' => 'GPT 13', 'descricao' => 'Ticket de monitoramento não possui subtarefas', 'tipo' => 'Qualidade'],
        ['sigla' => 'GPT 14', 'descricao' => 'No ticket de monitoramento critérios não preenchidos', 'tipo' => 'Qualidade'],

        // QUALIDADE - Gestão Requisitos Testes
        ['sigla' => 'GRT 1', 'descricao' => 'Plano padrão de teste desatualizado', 'tipo' => 'Qualidade'],
        ['sigla' => 'GRT 2', 'descricao' => 'Falta apontamento de horas', 'tipo' => 'Qualidade'],
        ['sigla' => 'GRT 5', 'descricao' => 'Falta os tickets de testes em tarefas relacionadas', 'tipo' => 'Qualidade'],

        // QUALIDADE - Processo Especificação Testes
        ['sigla' => 'PET 1', 'descricao' => 'Falta seguir o modelo de acordo com especificação', 'tipo' => 'Qualidade'],
        ['sigla' => 'PET 2', 'descricao' => 'Falta ticket de execução de testes conforme padrão', 'tipo' => 'Qualidade'],
        ['sigla' => 'PET 4', 'descricao' => 'Checklist não preenchido corretamente', 'tipo' => 'Qualidade'],

        // QUALIDADE - Produtos
        ['sigla' => 'PRODUTO GPT 1', 'descricao' => 'Ticket de planejamento não está completo', 'tipo' => 'Qualidade'],
        ['sigla' => 'PRODUTO GPT 2', 'descricao' => 'Ticket de monitoramento não está completo', 'tipo' => 'Qualidade'],
        ['sigla' => 'PRODUTO GPT 3', 'descricao' => 'O plano padrão de teste não está completo', 'tipo' => 'Qualidade'],
        ['sigla' => 'PRODUTO GPT 4', 'descricao' => 'Planilha de risco não está completa', 'tipo' => 'Qualidade'],
        ['sigla' => 'PRODUTO GPR 3', 'descricao' => 'Plano padrão de testes não completo', 'tipo' => 'Qualidade'],
        ['sigla' => 'PRODUTO GRT 2', 'descricao' => 'Tarefas relacionadas no ticket', 'tipo' => 'Qualidade'],
        ['sigla' => 'PRODUTO GRT 3', 'descricao' => 'Tarefas relacionadas no ticket', 'tipo' => 'Qualidade'],
        ['sigla' => 'PRODUTO PET 1', 'descricao' => 'Ticket de especificação não completa', 'tipo' => 'Qualidade'],
        ['sigla' => 'PRODUTO PET 2', 'descricao' => 'Ticket de execução de testes não completo', 'tipo' => 'Qualidade'],

        // SERVIÇO - Categorias específicas
        ['sigla' => 'ATD 1', 'descricao' => 'Atendimento não conforme com SLA', 'tipo' => 'Serviço'],
        ['sigla' => 'ATD 2', 'descricao' => 'Documentação de atendimento incompleta', 'tipo' => 'Serviço'],
        ['sigla' => 'ATD 3', 'descricao' => 'Relatório mensal não enviado', 'tipo' => 'Serviço'],
        ['sigla' => 'GOS 1', 'descricao' => 'Gestão de operações de serviço inadequada', 'tipo' => 'Serviço'],
        ['sigla' => 'GOS 2', 'descricao' => 'Processos de serviço não padronizados', 'tipo' => 'Serviço'],
        ['sigla' => 'GOS 3', 'descricao' => 'De acordo dos colaboradores não inserido', 'tipo' => 'Serviço'],
        ['sigla' => 'GOS 5', 'descricao' => 'Problema não registrado na planilha', 'tipo' => 'Serviço'],
        ['sigla' => 'CAP 1', 'descricao' => 'Plano de treinamento não atualizado', 'tipo' => 'Serviço'],
        ['sigla' => 'CAP 2', 'descricao' => 'Plano de treinamento não atualizado', 'tipo' => 'Serviço'],
        ['sigla' => 'GNS 1', 'descricao' => 'Catálogo de serviços não atualizado', 'tipo' => 'Serviço'],
        ['sigla' => 'GNS 2', 'descricao' => 'Contrato do cliente não atualizado', 'tipo' => 'Serviço'],
        ['sigla' => 'GNS 3', 'descricao' => 'Relatório mensal não enviado', 'tipo' => 'Serviço'],
        ['sigla' => 'GCO 1', 'descricao' => 'Gestão de conhecimento organizacional falha', 'tipo' => 'Serviço'],
        ['sigla' => 'GCO 2', 'descricao' => 'Tags não inseridas', 'tipo' => 'Serviço'],
        ['sigla' => 'MED 1', 'descricao' => 'Medição de processos não realizada', 'tipo' => 'Serviço'],
        ['sigla' => 'MED 2', 'descricao' => 'Planilha de indicadores não inserida', 'tipo' => 'Serviço'],
        ['sigla' => 'MED 3', 'descricao' => 'Planilha de indicadores não inserida', 'tipo' => 'Serviço'],
        ['sigla' => 'MED 4', 'descricao' => 'Planilha de indicadores não inserida', 'tipo' => 'Serviço'],

        // SERVIÇO - Produtos
        ['sigla' => 'PRODUTO BASELINE 1', 'descricao' => 'As tags precisam estar corretas e completas', 'tipo' => 'Serviço'],
        ['sigla' => 'PRODUTO BASELINE 2', 'descricao' => 'As tags precisam estar corretas e completas', 'tipo' => 'Serviço'],
        ['sigla' => 'PRODUTO REUNIÃO 1', 'descricao' => 'Reunião com a alta gerência precisa estar correta', 'tipo' => 'Serviço'],
        ['sigla' => 'PRODUTO REUNIÃO 2', 'descricao' => 'Reunião com a alta gerência precisa estar correta', 'tipo' => 'Serviço'],
        ['sigla' => 'PRODUTO PLANILHA DE IND 1', 'descricao' => 'Planilha de indicadores precisa estar correta', 'tipo' => 'Serviço'],
        ['sigla' => 'PRODUTO PLANILHA DE IND 2', 'descricao' => 'Planilha de indicadores precisa estar correta', 'tipo' => 'Serviço'],
        ['sigla' => 'PRODUTO CATALOGO SERV 1', 'descricao' => 'Catálogo de serviços precisa estar correto', 'tipo' => 'Serviço'],
        ['sigla' => 'PRODUTO CATALOGO SERV 2', 'descricao' => 'Catálogo de serviços precisa estar correto', 'tipo' => 'Serviço'],
        ['sigla' => 'PRODUTO TICKET TR 1', 'descricao' => 'Plano de treinamento precisa estar correto', 'tipo' => 'Serviço'],
        ['sigla' => 'PRODUTO TICKET TR 2', 'descricao' => 'Plano de treinamento precisa estar correto', 'tipo' => 'Serviço'],
    ];

    /**
     * Executar o seeder
     */
    public function run(): void
    {
        DB::transaction(function () {
            $this->command->info('Iniciando seeder de auditorias completo...');

            $this->truncateTables();
            $this->createTiposAuditoria();
            $this->createAnalistas();
            $this->createNaoConformidades();
            $this->createAuditorias();
            $this->createRelacionamentos();

            $this->command->info("\n✓ Seeder executado com sucesso!");
            $this->printStatistics();
        });
    }

    /**
     * Limpar tabelas antes de inserir dados
     */
    private function truncateTables(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('auditoria_nao_conformidade')->truncate();
        DB::table('auditorias')->truncate();
        DB::table('nao_conformidades')->truncate();
        DB::table('tipo_auditorias')->truncate();
        DB::table('users')->where('cargo', 'analista')->delete();

        Schema::enableForeignKeyConstraints();

        $this->command->info('✓ Tabelas limpas');
    }

    /**
     * Criar tipos de auditoria
     */
    private function createTiposAuditoria(): void
    {
        $tipos = [];

        foreach (self::TIPOS_AUDITORIA as $nome => $key) {
            $uuid = Str::uuid()->toString();
            $tipos[] = [
                'id' => $uuid,
                'nome' => $nome,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $this->tiposAuditoriaIds[$key] = $uuid;
        }

        DB::table('tipo_auditorias')->insert($tipos);
        $this->command->info('✓ ' . count($tipos) . ' tipos de auditoria criados');
    }

    /**
     * Criar analistas
     */
    private function createAnalistas(): void
    {
        foreach ($this->analistas as $analista) {
            $id = DB::table('users')->insertGetId([
                'name' => $analista['name'],
                'email' => $analista['email'],
                'cargo' => $analista['cargo'],
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->analistasIds[$analista['identificador']] = $id;
        }

        $this->command->info('✓ ' . count($this->analistas) . ' analistas criados');
    }

    /**
     * Criar não conformidades
     */
    private function createNaoConformidades(): void
    {
        $ncs = [];

        foreach ($this->naoConformidades as $nc) {
            $uuid = Str::uuid()->toString();
            $tipoAuditoriaId = $this->getTipoAuditoriaIdByNome($nc['tipo']);

            $ncs[] = [
                'id' => $uuid,
                'sigla' => $nc['sigla'],
                'descricao' => $nc['descricao'],
                'tipo_auditoria_id' => $tipoAuditoriaId,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $this->naoConformidadesIds[$nc['sigla']] = $uuid;
        }

        DB::table('nao_conformidades')->insert($ncs);
        $this->command->info('✓ ' . count($ncs) . ' não conformidades criadas');
    }

    /**
     * Criar auditorias para todos os projetos
     */
    private function createAuditorias(): void
    {
        $projetos = $this->getProjetos();
        $totalAuditorias = 0;

        foreach ($projetos as $projeto) {
            $tipoAuditoriaId = $this->getTipoAuditoriaIdByNome($projeto['tipo']);
            $auditorias = [];

            foreach ($projeto['periodos'] as $index => $periodo) {
                $analistaIdentificador = $this->determinarAnalistaParaPeriodo($projeto['analistas'], $index);
                $analistaId = $this->analistasIds[$analistaIdentificador];
                $analistaNome = $this->getAnalistaNomeById($analistaId);

                $processo = $periodo['processo'] ?? $this->gerarPercentualAleatorio($projeto['tipo']);
                $produto = $periodo['produto'] ?? $this->gerarPercentualAleatorio($projeto['tipo']);

                $uuid = Str::uuid()->toString();
                $auditorias[] = [
                    'id' => $uuid,
                    'tipo_auditorias_id' => $tipoAuditoriaId,
                    'periodo' => $periodo['periodo'],
                    'quem_criou' => 'Auditor', // Quem fez a auditoria é sempre "Auditor"
                    'analista_responsavel' => $analistaNome, // Analista de requisitos do projeto
                    'processo' => $processo,
                    'produto' => $produto,
                    'tarefa_redmine' => $this->gerarTarefaRedmine($projeto['nome'], $periodo['periodo']),
                    'nome_do_projeto' => $projeto['nome'],
                    'created_at' => $periodo['data'],
                    'updated_at' => $periodo['data'],
                ];

                $this->auditoriasIds[$projeto['nome']][] = $uuid;
                $totalAuditorias++;
            }

            if (!empty($auditorias)) {
                DB::table('auditorias')->insert($auditorias);
            }
        }

        $this->command->info('✓ ' . $totalAuditorias . ' auditorias criadas');
    }

    /**
     * Criar relacionamentos entre auditorias e não conformidades
     */
    private function createRelacionamentos(): void
    {
        $projetos = $this->getProjetos();
        $totalRelacionamentos = 0;

        foreach ($projetos as $projeto) {
            $auditoriasIds = $this->auditoriasIds[$projeto['nome']] ?? [];

            if (empty($auditoriasIds)) {
                continue;
            }

            $ncsIds = $this->getNaoConformidadesIdsBySiglas($projeto['siglas_ncs']);

            if (empty($ncsIds)) {
                continue;
            }

            $relacionamentos = $this->distribuirNaoConformidades(
                $auditoriasIds,
                $ncsIds,
                $projeto['total_ncs']
            );

            foreach (array_chunk($relacionamentos, 500) as $chunk) {
                DB::table('auditoria_nao_conformidade')->insert($chunk);
            }

            $totalRelacionamentos += count($relacionamentos);
        }

        $this->command->info('✓ ' . $totalRelacionamentos . ' relacionamentos criados');
    }

    /**
     * Obter ID do tipo de auditoria pelo nome
     */
    private function getTipoAuditoriaIdByNome(string $tipo): string
    {
        $map = [
            'Produto' => 'produto',
            'Serviço' => 'servico',
            'Qualidade' => 'qualidade',
        ];

        return $this->tiposAuditoriaIds[$map[$tipo]];
    }

    /**
     * Determinar qual analista é responsável por determinado período
     */
    private function determinarAnalistaParaPeriodo(array $analistas, int $indicePeriodo): string
    {
        if (count($analistas) === 1 && $analistas[0]['periodos'] === 'todos') {
            return $analistas[0]['identificador'];
        }

        foreach ($analistas as $analista) {
            if (is_array($analista['periodos']) && in_array($indicePeriodo, $analista['periodos'])) {
                return $analista['identificador'];
            }
        }

        return $analistas[0]['identificador'];
    }

    /**
     * Obter nome do analista pelo ID
     */
    private function getAnalistaNomeById(int $id): string
    {
        foreach ($this->analistas as $analista) {
            if ($this->analistasIds[$analista['identificador']] === $id) {
                return $analista['name'];
            }
        }

        return 'Desconhecido';
    }

    /**
     * Gerar percentual aleatório realista baseado no tipo
     */
    private function gerarPercentualAleatorio(string $tipoAuditoria): float
    {
        switch ($tipoAuditoria) {
            case 'Produto':
                return round(rand(9500, 10000) / 100, 2);
            case 'Serviço':
                return round(rand(9200, 10000) / 100, 2);
            case 'Qualidade':
                return round(rand(8600, 9700) / 100, 2);
            default:
                return 95.00;
        }
    }

    /**
     * Gerar número de tarefa Redmine
     */
    private function gerarTarefaRedmine(string $projeto, string $periodo): string
    {
        $prefixo = substr(md5($projeto . $periodo), 0, 6);
        $numero = rand(10000, 99999);
        return "#$numero-$prefixo";
    }

    /**
     * Obter IDs das NCs pelas siglas
     */
    private function getNaoConformidadesIdsBySiglas(array $siglas): array
    {
        $ids = [];
        foreach ($siglas as $sigla) {
            if (isset($this->naoConformidadesIds[$sigla])) {
                $ids[] = $this->naoConformidadesIds[$sigla];
            }
        }
        return $ids;
    }

    /**
     * Distribuir não conformidades entre auditorias
     */
    private function distribuirNaoConformidades(array $auditoriasIds, array $ncsIds, int $totalOcorrencias): array
    {
        $relacionamentos = [];
        $numAuditorias = count($auditoriasIds);
        $numNcs = count($ncsIds);

        $ncsPorAuditoria = max(1, (int) ceil($totalOcorrencias / $numAuditorias));

        foreach ($auditoriasIds as $auditoriaId) {
            $ncsSubset = $this->selecionarNcsAleatorias($ncsIds, $ncsPorAuditoria, $numNcs);

            foreach ($ncsSubset as $ncId) {
                // 70% de chance de estar resolvido
                $resolvido = (rand(1, 100) <= 70) ? 'sim' : 'não';

                $relacionamentos[] = [
                    'auditoria_id' => $auditoriaId,
                    'nao_conformidade_id' => $ncId,
                    'resolvido' => $resolvido,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        return $relacionamentos;
    }

    /**
     * Selecionar NCs aleatórias
     */
    private function selecionarNcsAleatorias(array $ncsIds, int $quantidade, int $total): array
    {
        if ($quantidade >= $total) {
            return $ncsIds;
        }

        $ncsIdsCopy = $ncsIds;
        shuffle($ncsIdsCopy);
        return array_slice($ncsIdsCopy, 0, $quantidade);
    }

    /**
     * Exibir estatísticas finais
     */
    private function printStatistics(): void
    {
        $stats = [
            'Tipos de Auditoria' => count($this->tiposAuditoriaIds),
            'Analistas' => count($this->analistasIds),
            'Não Conformidades' => count($this->naoConformidadesIds),
            'Auditorias' => array_sum(array_map('count', $this->auditoriasIds)),
            'Relacionamentos' => DB::table('auditoria_nao_conformidade')->count(),
        ];

        $this->command->info("\n=== ESTATÍSTICAS ===");
        foreach ($stats as $label => $valor) {
            $this->command->info("$label: $valor");
        }
    }

    /**
     * Definição de todos os projetos com seus períodos e NCs
     */
    private function getProjetos(): array
    {
        return [
            // ===== PROJETOS DE PRODUTO =====
            [
                'nome' => 'VIPGOV',
                'tipo' => 'Produto',
                'total_ncs' => 259,
                'analistas' => [
                    ['identificador' => 'AYMMEE', 'periodos' => 'todos']
                ],
                'periodos' => [
                    ['periodo' => 'Janeiro 2024', 'data' => '2024-01-31', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Fevereiro 2024 - 1', 'data' => '2024-02-15', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Fevereiro 2024 - 2', 'data' => '2024-02-29', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Março 2024 - 1', 'data' => '2024-03-10', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Março 2024 - 2', 'data' => '2024-03-20', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Março 2024 - 3', 'data' => '2024-03-31', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Abril 2024 - 1', 'data' => '2024-04-10', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Abril 2024 - 2', 'data' => '2024-04-20', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Abril 2024 - 3', 'data' => '2024-04-30', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Maio 2024', 'data' => '2024-05-31', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Junho 2024 - 1', 'data' => '2024-06-10', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Junho 2024 - 2', 'data' => '2024-06-20', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Junho 2024 - 3', 'data' => '2024-06-30', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Julho 2024', 'data' => '2024-07-31', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Agosto 2024 - 1', 'data' => '2024-08-10', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Agosto 2024 - 2', 'data' => '2024-08-20', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Agosto 2024 - 3', 'data' => '2024-08-31', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Setembro 2024', 'data' => '2024-09-30', 'processo' => null, 'produto' => null],
                ],
                'siglas_ncs' => ['PR 1', 'PR 2', 'PR 3', 'GPR 1', 'GPR 2', 'GPR 3', 'GPR 4', 'GPR 5', 'GPR 6', 'GPR 7',
                    'RSKM 1', 'RSKM 2', 'RSKM 3', 'RSKM 4', 'RSKM 5', 'RSKM 6', 'RSKM 7',
                    'VV 1', 'VV 2', 'VV 3', 'VV 6', 'REQ 9', 'REQ 10',
                    'PRODUTO PR 1', 'PRODUTO PR 2', 'PRODUTO GPR 1', 'PRODUTO GPR 2', 'PRODUTO GPR 3', 'PRODUTO GPR 4',
                    'PRODUTO GPR 5', 'PRODUTO GPR 6', 'PRODUTO RSKM 1', 'PRODUTO RSKM 2',
                    'PRODUTO VV 1', 'PRODUTO VV 2', 'PRODUTO VV TS 1', 'PRODUTO VV TS 2',
                    'PRODUTO REQ 3', 'PRODUTO REQ 4']
            ],

            [
                'nome' => 'SELO AMAPÁ',
                'tipo' => 'Produto',
                'total_ncs' => 210,
                'analistas' => [
                    ['identificador' => 'AYMMEE', 'periodos' => 'todos']
                ],
                'periodos' => [
                    ['periodo' => 'Fevereiro 2024 - 1', 'data' => '2024-02-15', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Fevereiro 2024 - 2', 'data' => '2024-02-29', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Março 2024 - 1', 'data' => '2024-03-10', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Março 2024 - 2', 'data' => '2024-03-20', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Março 2024 - 3', 'data' => '2024-03-31', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Abril 2024 - 1', 'data' => '2024-04-15', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Abril 2024 - 2', 'data' => '2024-04-30', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Junho 2024 - 1', 'data' => '2024-06-10', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Junho 2024 - 2', 'data' => '2024-06-20', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Junho 2024 - 3', 'data' => '2024-06-30', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Agosto 2024', 'data' => '2024-08-31', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Setembro 2024 - 1', 'data' => '2024-09-15', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Setembro 2024 - 2', 'data' => '2024-09-30', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Outubro 2024', 'data' => '2024-10-31', 'processo' => 84.62, 'produto' => 84.62],
                ],
                'siglas_ncs' => ['GPR 1', 'GPR 2', 'GPR 3', 'GPR 4', 'GPR 5', 'GPR 6', 'GPR 7',
                    'RSKM 1', 'RSKM 2', 'RSKM 3', 'RSKM 4', 'RSKM 5', 'RSKM 6', 'RSKM 7',
                    'VV 1', 'VV 2', 'VV 3', 'VV 6',
                    'PR 1', 'PR 2', 'PR 3',
                    'REQ 2', 'REQ 3', 'REQ 4', 'REQ 6', 'REQ 8', 'REQ 9',
                    'PRODUTO GPR 1', 'PRODUTO GPR 2', 'PRODUTO GPR 3', 'PRODUTO GPR 4', 'PRODUTO GPR 5', 'PRODUTO GPR 6',
                    'PRODUTO RSKM 1', 'PRODUTO RSKM 2',
                    'PRODUTO VV 1', 'PRODUTO VV TS 1', 'PRODUTO VV TS 2',
                    'PRODUTO PR 1', 'PRODUTO PR 2',
                    'PRODUTO REQ 1', 'PRODUTO REQ 2', 'PRODUTO REQ 4', 'PRODUTO REQ 6']
            ],

            [
                'nome' => 'SICWEB',
                'tipo' => 'Produto',
                'total_ncs' => 52,
                'analistas' => [
                    ['identificador' => 'FRANCIMARA', 'periodos' => [0, 5, 6, 7, 8]], // Fev, Jul, Ago, Set, Out
                    ['identificador' => 'ISABELLE', 'periodos' => [1, 3]], // Mar, Mai
                    ['identificador' => 'AMANDA', 'periodos' => [4]] // Jun
                ],
                'periodos' => [
                    ['periodo' => 'Fevereiro 2024', 'data' => '2024-02-29', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Março 2024', 'data' => '2024-03-31', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Abril 2024', 'data' => '2024-04-30', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Maio 2024', 'data' => '2024-05-31', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Junho 2024', 'data' => '2024-06-30', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Julho 2024 - 1', 'data' => '2024-07-15', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Julho 2024 - 2', 'data' => '2024-07-31', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Agosto 2024', 'data' => '2024-08-31', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Setembro 2024', 'data' => '2024-09-30', 'processo' => null, 'produto' => null],
                ],
                'siglas_ncs' => ['RSKM 1', 'RSKM 2', 'RSKM 3', 'RSKM 4', 'RSKM 5', 'RSKM 6', 'RSKM 7',
                    'GPR 5', 'GPR 6', 'GPR 7',
                    'VV 3', 'VV 6',
                    'PR 1', 'PR 2', 'PR 3',
                    'REQ 9',
                    'PRODUTO RSKM 1', 'PRODUTO RSKM 2',
                    'PRODUTO GPR 4', 'PRODUTO GPR 5', 'PRODUTO GPR 6',
                    'PRODUTO PR 1', 'PRODUTO PR 2',
                    'PRODUTO REQ 4',
                    'PRODUTO VV TS 1', 'PRODUTO VV TS 2']
            ],

            [
                'nome' => 'SEIIC',
                'tipo' => 'Produto',
                'total_ncs' => 145,
                'analistas' => [
                    ['identificador' => 'ANDRE', 'periodos' => [0, 1]], // Jul 2x
                    ['identificador' => 'ADRIANO', 'periodos' => [2, 3]], // Ago 2x
                    ['identificador' => 'CLEBERSON', 'periodos' => [4, 5, 6, 7]] // Set 2x, Out 2x
                ],
                'periodos' => [
                    ['periodo' => 'Julho 2024 - 1', 'data' => '2024-07-15', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Julho 2024 - 2', 'data' => '2024-07-31', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Agosto 2024 - 1', 'data' => '2024-08-15', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Agosto 2024 - 2', 'data' => '2024-08-31', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Setembro 2024 - 1', 'data' => '2024-09-15', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Setembro 2024 - 2', 'data' => '2024-09-30', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Outubro 2024 - 1', 'data' => '2024-10-15', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Outubro 2024 - 2', 'data' => '2024-10-31', 'processo' => null, 'produto' => null],
                ],
                'siglas_ncs' => ['GPR 1', 'GPR 2', 'GPR 3', 'GPR 4', 'GPR 5', 'GPR 6', 'GPR 7',
                    'REQ 2', 'REQ 3', 'REQ 4', 'REQ 6', 'REQ 7', 'REQ 8', 'REQ 9', 'REQ 10',
                    'RSKM 1', 'RSKM 2', 'RSKM 3', 'RSKM 4', 'RSKM 5', 'RSKM 6', 'RSKM 7',
                    'VV 1', 'VV 2', 'VV 3', 'VV 6',
                    'PR 1', 'PR 2', 'PR 3',
                    'PRODUTO GPR 1', 'PRODUTO GPR 2', 'PRODUTO GPR 3', 'PRODUTO GPR 4', 'PRODUTO GPR 5', 'PRODUTO GPR 6',
                    'PRODUTO REQ 1', 'PRODUTO REQ 2', 'PRODUTO REQ 3', 'PRODUTO REQ 4', 'PRODUTO REQ TS 5', 'PRODUTO REQ TS 6',
                    'PRODUTO RSKM 1', 'PRODUTO RSKM 2',
                    'PRODUTO VV 1', 'PRODUTO VV 2', 'PRODUTO VV TS 1', 'PRODUTO VV TS 2',
                    'PRODUTO PR 1', 'PRODUTO PR 2']
            ],

            [
                'nome' => 'SEFAZ',
                'tipo' => 'Produto',
                'total_ncs' => 49,
                'analistas' => [
                    ['identificador' => 'DIEGO', 'periodos' => 'todos']
                ],
                'periodos' => [
                    ['periodo' => 'Junho 2024', 'data' => '2024-06-30', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Julho 2024', 'data' => '2024-07-31', 'processo' => null, 'produto' => null],
                ],
                'siglas_ncs' => ['GPR 1', 'GPR 2', 'GPR 3', 'GPR 5', 'GPR 6', 'GPR 7',
                    'REQ 2', 'REQ 3', 'REQ 6', 'REQ 7', 'REQ 8', 'REQ 9',
                    'RSKM 1', 'RSKM 2', 'RSKM 3', 'RSKM 4', 'RSKM 5', 'RSKM 6', 'RSKM 7',
                    'VV 1', 'VV 2', 'VV 3', 'VV 6',
                    'PR 1', 'PR 3',
                    'PRODUTO GPR 1', 'PRODUTO GPR 2', 'PRODUTO GPR 3', 'PRODUTO GPR 4',
                    'PRODUTO REQ 3', 'PRODUTO REQ 4', 'PRODUTO REQ 5', 'PRODUTO REQ 6',
                    'PRODUTO RSKM 1', 'PRODUTO RSKM 2',
                    'PRODUTO VV 1', 'PRODUTO VV 2', 'PRODUTO VV TS 1', 'PRODUTO VV TS 2',
                    'PRODUTO PR 1', 'PRODUTO PR 2']
            ],

            [
                'nome' => 'RURAP',
                'tipo' => 'Produto',
                'total_ncs' => 57,
                'analistas' => [
                    ['identificador' => 'ADRIANO', 'periodos' => [0, 1, 4, 5, 6]], // Abr, Mai, Ago 3x
                    ['identificador' => 'ANDRE', 'periodos' => [2, 3, 7]] // Jul 3x
                ],
                'periodos' => [
                    ['periodo' => 'Abril 2024', 'data' => '2024-04-30', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Maio 2024', 'data' => '2024-05-31', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Julho 2024 - 1', 'data' => '2024-07-10', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Julho 2024 - 2', 'data' => '2024-07-20', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Agosto 2024 - 1', 'data' => '2024-08-10', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Agosto 2024 - 2', 'data' => '2024-08-20', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Agosto 2024 - 3', 'data' => '2024-08-31', 'processo' => null, 'produto' => null],
                ],
                'siglas_ncs' => ['RSKM 1', 'RSKM 2', 'RSKM 3', 'RSKM 4', 'RSKM 5', 'RSKM 6', 'RSKM 7',
                    'GPR 1', 'GPR 5', 'GPR 6',
                    'REQ 2', 'REQ 3', 'REQ 4', 'REQ 6', 'REQ 8', 'REQ 9', 'REQ 10',
                    'PR 1', 'PR 2', 'PR 3',
                    'PRODUTO RSKM 1', 'PRODUTO RSKM 2',
                    'PRODUTO GPR 1', 'PRODUTO GPR 2', 'PRODUTO GPR 3', 'PRODUTO GPR 5', 'PRODUTO GPR 6',
                    'PRODUTO REQ 3', 'PRODUTO REQ 4',
                    'PRODUTO PR 1', 'PRODUTO PR 2']
            ],

            [
                'nome' => 'EXPOFEIRA',
                'tipo' => 'Produto',
                'total_ncs' => 9,
                'analistas' => [
                    ['identificador' => 'AMANDA', 'periodos' => 'todos']
                ],
                'periodos' => [
                    ['periodo' => 'Julho 2024', 'data' => '2024-07-31', 'processo' => null, 'produto' => null],
                ],
                'siglas_ncs' => ['GPR 5', 'GPR 6', 'RSKM 5', 'RSKM 6', 'VV 6',
                    'PRODUTO GPR 5', 'PRODUTO GPR 6', 'PRODUTO RSKM 1', 'PRODUTO RSKM 2']
            ],

            [
                'nome' => 'AMAPAZEIRO',
                'tipo' => 'Produto',
                'total_ncs' => 24,
                'analistas' => [
                    ['identificador' => 'ADRIANO', 'periodos' => [0]], // Ago
                    ['identificador' => 'CLEBERSON', 'periodos' => [1, 2]] // Set, Out
                ],
                'periodos' => [
                    ['periodo' => 'Agosto 2024', 'data' => '2024-08-31', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Setembro 2024', 'data' => '2024-09-30', 'processo' => null, 'produto' => null],
                    ['periodo' => 'Outubro 2024', 'data' => '2024-10-31', 'processo' => null, 'produto' => null],
                ],
                'siglas_ncs' => ['GPR 5', 'GPR 6', 'GPR 7',
                    'RSKM 5', 'RSKM 6',
                    'VV 6',
                    'PR 1', 'PR 2', 'PR 3',
                    'PRODUTO GPR 3', 'PRODUTO GPR 4', 'PRODUTO GPR 5', 'PRODUTO GPR 6',
                    'PRODUTO RSKM 1', 'PRODUTO RSKM 2',
                    'PRODUTO PR 1', 'PRODUTO PR 2']
            ],

            [
                'nome' => 'OUVAMAPÁ',
                'tipo' => 'Produto',
                'total_ncs' => 1,
                'analistas' => [
                    ['identificador' => 'ADRIANO', 'periodos' => 'todos']
                ],
                'periodos' => [
                    ['periodo' => 'Agosto 2024', 'data' => '2024-08-31', 'processo' => null, 'produto' => null],
                ],
                'siglas_ncs' => ['GPR 1']
            ],

            // ===== PROCESSOS DE SERVIÇO =====
            [
                'nome' => 'SERVIÇOS CORPORATIVOS',
                'tipo' => 'Serviço',
                'total_ncs' => 52,
                'analistas' => [
                    ['identificador' => 'EQUIPE_QA', 'periodos' => 'todos']
                ],
                'periodos' => [
                    ['periodo' => '28/11/2024', 'data' => '2024-11-28', 'processo' => 84.21, 'produto' => 87.50],
                    ['periodo' => '12/12/2024', 'data' => '2024-12-12', 'processo' => 84.00, 'produto' => 94.44],
                    ['periodo' => '16/01/2025', 'data' => '2025-01-16', 'processo' => 100.00, 'produto' => 100.00],
                    ['periodo' => '30/01/2025', 'data' => '2025-01-30', 'processo' => 100.00, 'produto' => 88.89],
                    ['periodo' => '13/02/2025', 'data' => '2025-02-13', 'processo' => 100.00, 'produto' => 100.00],
                    ['periodo' => '27/02/2025', 'data' => '2025-02-27', 'processo' => 100.00, 'produto' => 100.00],
                    ['periodo' => '15/03/2025', 'data' => '2025-03-15', 'processo' => 100.00, 'produto' => 100.00],
                    ['periodo' => '16/04/2025', 'data' => '2025-04-16', 'processo' => 100.00, 'produto' => 100.00],
                    ['periodo' => '29/04/2025', 'data' => '2025-04-29', 'processo' => 100.00, 'produto' => 100.00],
                    ['periodo' => '15/05/2025', 'data' => '2025-05-15', 'processo' => 68.00, 'produto' => 77.78],
                    ['periodo' => '29/05/2025', 'data' => '2025-05-29', 'processo' => 76.92, 'produto' => 77.78],
                    ['periodo' => '18/06/2025', 'data' => '2025-06-18', 'processo' => 100.00, 'produto' => 100.00],
                    ['periodo' => '02/07/2025', 'data' => '2025-07-02', 'processo' => 96.00, 'produto' => 89.00],
                    ['periodo' => '16/07/2025', 'data' => '2025-07-16', 'processo' => 96.00, 'produto' => 100.00],
                    ['periodo' => '30/07/2025', 'data' => '2025-07-30', 'processo' => 88.00, 'produto' => 88.89],
                    ['periodo' => '13/08/2025', 'data' => '2025-08-13', 'processo' => 96.00, 'produto' => 88.89],
                    ['periodo' => '17/09/2025', 'data' => '2025-09-17', 'processo' => 100.00, 'produto' => 100.00],
                    ['periodo' => '01/10/2025', 'data' => '2025-10-01', 'processo' => 96.43, 'produto' => 88.89],
                    ['periodo' => '15/10/2025', 'data' => '2025-10-15', 'processo' => 100.00, 'produto' => 100.00],
                    ['periodo' => '29/10/2025', 'data' => '2025-10-29', 'processo' => 100.00, 'produto' => 100.00],
                ],
                'siglas_ncs' => ['ATD 3', 'GOS 5', 'CAP 1', 'CAP 2', 'GNS 1', 'GNS 2', 'GNS 3', 'GOS 3',
                    'GCO 2', 'MED 2', 'MED 3', 'MED 4',
                    'PRODUTO BASELINE 1', 'PRODUTO BASELINE 2', 'PRODUTO REUNIÃO 1', 'PRODUTO REUNIÃO 2',
                    'PRODUTO PLANILHA DE IND 1', 'PRODUTO PLANILHA DE IND 2',
                    'PRODUTO CATALOGO SERV 1', 'PRODUTO CATALOGO SERV 2',
                    'PRODUTO TICKET TR 1', 'PRODUTO TICKET TR 2']
            ],

            // ===== PROCESSOS DE QUALIDADE =====
            [
                'nome' => 'SPRINTS',
                'tipo' => 'Qualidade',
                'total_ncs' => 295,
                'analistas' => [
                    ['identificador' => 'KLEVERTON', 'periodos' => 'todos']
                ],
                'periodos' => [
                    ['periodo' => 'Sprint 1', 'data' => '2024-06-20', 'processo' => 92.31, 'produto' => 90.00],
                    ['periodo' => 'Sprint 2', 'data' => '2024-07-04', 'processo' => 84.62, 'produto' => 80.00],
                    ['periodo' => 'Sprint 3', 'data' => '2024-07-18', 'processo' => 92.31, 'produto' => 90.00],
                    ['periodo' => 'Sprint 4', 'data' => '2024-08-01', 'processo' => 84.62, 'produto' => 80.00],
                    ['periodo' => 'Sprint 5', 'data' => '2024-08-15', 'processo' => 92.31, 'produto' => 90.00],
                    ['periodo' => 'Sprint 6', 'data' => '2024-08-29', 'processo' => 84.62, 'produto' => 80.00],
                    ['periodo' => 'Sprint 7', 'data' => '2024-09-12', 'processo' => 92.31, 'produto' => 90.00],
                    ['periodo' => 'Sprint 8', 'data' => '2024-09-26', 'processo' => 76.92, 'produto' => 70.00],
                    ['periodo' => 'Sprint 9', 'data' => '2024-10-10', 'processo' => 84.62, 'produto' => 80.00],
                    ['periodo' => 'Sprint 10', 'data' => '2024-10-24', 'processo' => 92.31, 'produto' => 90.00],
                    ['periodo' => 'Sprint 11', 'data' => '2024-11-07', 'processo' => 84.62, 'produto' => 80.00],
                    ['periodo' => 'Sprint 12', 'data' => '2024-11-21', 'processo' => 92.31, 'produto' => 90.00],
                    ['periodo' => 'Sprint 13', 'data' => '2024-12-05', 'processo' => 76.92, 'produto' => 70.00],
                    ['periodo' => 'Sprint 14', 'data' => '2024-12-19', 'processo' => 69.23, 'produto' => 60.00],
                    ['periodo' => 'Sprint 15', 'data' => '2025-01-09', 'processo' => 69.23, 'produto' => 60.00],
                    ['periodo' => 'Sprint 16', 'data' => '2025-01-23', 'processo' => 46.15, 'produto' => 50.00],
                    ['periodo' => 'Sprint 17', 'data' => '2025-02-06', 'processo' => 92.31, 'produto' => 90.00],
                    ['periodo' => 'Sprint 18', 'data' => '2025-02-20', 'processo' => 84.62, 'produto' => 80.00],
                    ['periodo' => 'Sprint 19', 'data' => '2025-03-06', 'processo' => 92.31, 'produto' => 90.00],
                    ['periodo' => 'Sprint 20', 'data' => '2025-03-20', 'processo' => 84.62, 'produto' => 80.00],
                    ['periodo' => 'Sprint 21', 'data' => '2025-04-03', 'processo' => 92.31, 'produto' => 90.00],
                    ['periodo' => 'Sprint 22', 'data' => '2025-04-17', 'processo' => 76.92, 'produto' => 70.00],
                    ['periodo' => 'Sprint 23', 'data' => '2025-05-01', 'processo' => 84.62, 'produto' => 80.00],
                    ['periodo' => 'Sprint 24', 'data' => '2025-05-15', 'processo' => 92.31, 'produto' => 90.00],
                    ['periodo' => 'Sprint 25', 'data' => '2025-05-29', 'processo' => 84.62, 'produto' => 80.00],
                    ['periodo' => 'Sprint 26', 'data' => '2025-06-12', 'processo' => 92.31, 'produto' => 90.00],
                    ['periodo' => 'Sprint 27', 'data' => '2025-06-26', 'processo' => 84.62, 'produto' => 80.00],
                    ['periodo' => 'Sprint 28', 'data' => '2025-07-10', 'processo' => 100.00, 'produto' => 100.00],
                    ['periodo' => 'Sprint 29', 'data' => '2025-07-24', 'processo' => 92.31, 'produto' => 90.00],
                    ['periodo' => 'Sprint 30', 'data' => '2025-08-07', 'processo' => 100.00, 'produto' => 100.00],
                    ['periodo' => 'Sprint 31', 'data' => '2025-08-21', 'processo' => 100.00, 'produto' => 100.00],
                ],
                'siglas_ncs' => ['GPT 1', 'GPT 2', 'GPT 4', 'GPT 5', 'GPT 6', 'GPT 7', 'GPT 8', 'GPT 10', 'GPT 11', 'GPT 12', 'GPT 13', 'GPT 14',
                    'GRT 1', 'GRT 2', 'GRT 5',
                    'PET 1', 'PET 2', 'PET 4',
                    'PRODUTO GPT 1', 'PRODUTO GPT 2', 'PRODUTO GPT 3', 'PRODUTO GPT 4', 'PRODUTO GPR 3',
                    'PRODUTO GRT 2', 'PRODUTO GRT 3',
                    'PRODUTO PET 1', 'PRODUTO PET 2']
            ],
        ];
    }
}

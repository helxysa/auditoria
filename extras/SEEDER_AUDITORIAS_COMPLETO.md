# SEEDER DE AUDITORIAS COMPLETO - DOCUMENTAÇÃO

## Status: ✅ IMPLEMENTADO E TESTADO

Data de Implementação: 05/11/2025
Última Atualização: 05/11/2025 - v1.1.0

---

## ⚠️ IMPORTANTE - Entendendo os Papéis

### Papéis no Sistema

1. **Auditor** (quem_criou)
   - É quem **realizou a auditoria**
   - Sempre será "Auditor" em todas as auditorias
   - Campo: `auditorias.quem_criou`

2. **Analista de Requisitos** (analista_responsavel)
   - É o **analista de requisitos do projeto auditado**
   - Exemplos: Aymmée Silva, Francimara Santos, Kleverton Rocha
   - Campo: `auditorias.analista_responsavel`

3. **Status de Resolução da NC**
   - Indica se a não conformidade foi **fechada/resolvida**
   - Valores: "sim" ou "não"
   - Campo: `auditoria_nao_conformidade.resolvido`
   - Distribuição: ~70% resolvidas, ~30% abertas

---

## Visão Geral

O `AuditoriaCompletaSeeder` é um seeder Laravel que popula o banco de dados com dados realistas de auditorias, incluindo:
- 10 analistas de requisitos
- 3 tipos de auditoria (Produto, Serviço, Qualidade)
- 106 não conformidades únicas
- 114 auditorias distribuídas em 11 projetos
- 1.203 relacionamentos entre auditorias e não conformidades (com status de resolução)

---

## Executando o Seeder

### Opção 1: Migrate:fresh com seeder
```bash
php artisan migrate:fresh --seed --seeder=AuditoriaCompletaSeeder
```

### Opção 2: Apenas o seeder
```bash
php artisan db:seed --class=AuditoriaCompletaSeeder
```

**ATENÇÃO**: Este seeder executa `truncate` nas tabelas e deleta analistas existentes!

---

## Estrutura de Dados Gerados

### 1. Analistas (10 usuários)

| Nome | Email | Identificador |
|------|-------|---------------|
| Aymmée Silva | aymmee.silva@auditor.com | AYMMEE |
| Francimara Santos | francimara.santos@auditor.com | FRANCIMARA |
| Isabelle Costa | isabelle.costa@auditor.com | ISABELLE |
| Amanda Oliveira | amanda.oliveira@auditor.com | AMANDA |
| André Souza | andre.souza@auditor.com | ANDRE |
| Adriano Lima | adriano.lima@auditor.com | ADRIANO |
| Cleberson Alves | cleberson.alves@auditor.com | CLEBERSON |
| Diego Pereira | diego.pereira@auditor.com | DIEGO |
| Kleverton Rocha | kleverton.rocha@auditor.com | KLEVERTON |
| Equipe QA | equipe.qa@auditor.com | EQUIPE_QA |

**Senha padrão**: `password123`

### 2. Tipos de Auditoria (3 tipos)

1. **Produto** - Projetos de desenvolvimento de software
2. **Serviço** - Processos corporativos e organizacionais
3. **Qualidade** - Sprints de testes e controle de qualidade

### 3. Projetos e Auditorias (11 projetos, 114 auditorias)

#### Projetos de Produto (9 projetos)

| Projeto | Auditorias | Analista(s) | Total NCs |
|---------|------------|-------------|-----------|
| VIPGOV | 18 | Aymmée Silva | 259 |
| SELO AMAPÁ | 14 | Aymmée Silva | 210 |
| SICWEB | 9 | Francimara/Isabelle/Amanda | 52 |
| SEIIC | 8 | André/Adriano/Cleberson | 145 |
| RURAP | 7 | Adriano/André | 57 |
| AMAPAZEIRO | 3 | Adriano/Cleberson | 24 |
| SEFAZ | 2 | Diego Pereira | 49 |
| EXPOFEIRA | 1 | Amanda Oliveira | 9 |
| OUVAMAPÁ | 1 | Adriano Lima | 1 |

#### Processos de Serviço (1 projeto)

| Projeto | Auditorias | Analista | Período | Total NCs |
|---------|------------|----------|---------|-----------|
| SERVIÇOS CORPORATIVOS | 20 | Equipe QA | Nov/2024 - Out/2025 | 52 |

#### Processos de Qualidade (1 projeto)

| Projeto | Auditorias | Analista | Período | Total NCs |
|---------|------------|----------|---------|-----------|
| SPRINTS | 31 | Kleverton Rocha | Sprint 1-31 (Jun/2024 - Ago/2025) | 295 |

### 4. Não Conformidades (106 tipos)

#### Categorias de NCs:

**Produto:**
- PR (Peer Review): 3 tipos + 2 produtos
- GPR (Gestão de Projeto): 7 tipos + 6 produtos
- RSKM (Risk Management): 7 tipos + 2 produtos
- VV (Verificação e Validação): 4 tipos + 4 produtos
- REQ (Requisitos): 9 tipos + 8 produtos

**Qualidade:**
- GPT (Gestão Planejamento Treinamento): 12 tipos + 4 produtos
- GRT (Gestão Requisitos Testes): 3 tipos + 2 produtos
- PET (Processo Especificação Testes): 3 tipos + 2 produtos

**Serviço:**
- ATD (Atendimento): 3 tipos
- GOS (Gestão Operações Serviço): 4 tipos
- CAP (Capacitação): 2 tipos
- GNS (Gestão Níveis Serviço): 3 tipos
- GCO (Gestão Conhecimento): 2 tipos
- MED (Medição): 4 tipos
- PRODUTOS: 10 tipos específicos de serviço

---

## Características Técnicas

### 1. Distribuição Inteligente de NCs

O seeder distribui as não conformidades de forma proporcional entre as auditorias:

```
NCs por auditoria = total_ncs_projeto / num_auditorias_projeto
```

**Exemplo VIPGOV:**
- Total NCs: 259
- Auditorias: 18
- Densidade: ~14 NCs por auditoria
- Pool disponível: 39 tipos diferentes

Cada auditoria recebe uma seleção aleatória de NCs do pool, permitindo variação realista.

### 2. Percentuais de Conformidade

**Quando fornecidos** (SPRINTS e SERVIÇOS CORPORATIVOS):
- Usa valores exatos do documento original

**Quando não fornecidos** (projetos de Produto):
- Produto: 95.00% - 100.00%
- Serviço: 92.00% - 100.00%
- Qualidade: 86.00% - 97.00%

### 3. Datas Realistas

- Auditorias possuem datas cronológicas (Jan/2024 - Ago/2025)
- Períodos com "2x", "3x" geram múltiplas auditorias no mesmo mês
- created_at e updated_at usam a data do período

### 4. Tarefas Redmine

Geradas automaticamente com formato: `#NUMERO-HASH`

Exemplo: `#45678-a3c9f2`

- NUMERO: aleatório entre 10.000 e 99.999
- HASH: MD5 do projeto+período (primeiros 6 caracteres)

### 5. Múltiplos Analistas por Projeto

Projetos como SICWEB e SEIIC têm diferentes analistas em diferentes períodos:

**SICWEB:**
- Períodos 0, 5, 6, 7, 8 → Francimara Santos
- Períodos 1, 3 → Isabelle Costa
- Período 4 → Amanda Oliveira

**SEIIC:**
- Períodos 0, 1 → André Souza
- Períodos 2, 3 → Adriano Lima
- Períodos 4, 5, 6, 7 → Cleberson Alves

---

## Performance

### Tempo de Execução
- **Migrate:fresh + Seed**: ~1-2 segundos
- **Seed apenas**: ~500ms

### Recursos
- **Memória**: < 64MB
- **Queries**: ~30 queries (bulk inserts)
- **Transação única**: Garante atomicidade

### Otimizações Implementadas
1. **Bulk Inserts**: Arrays de até 500 registros por vez
2. **Transaction DB**: Rollback automático em caso de erro
3. **Disable Foreign Keys**: Durante truncate para melhor performance
4. **Chunking**: Relacionamentos inseridos em chunks de 500

---

## Verificação de Dados

### Verificar analistas
```php
php artisan tinker --execute="
DB::table('users')
    ->where('cargo', 'analista')
    ->get(['name', 'email'])
    ->each(function(\$u) {
        echo \$u->name . ' (' . \$u->email . ')' . PHP_EOL;
    });
"
```

### Verificar projetos
```php
php artisan tinker --execute="
DB::table('auditorias')
    ->select('nome_do_projeto', DB::raw('COUNT(*) as total'))
    ->groupBy('nome_do_projeto')
    ->orderByDesc('total')
    ->get()
    ->each(function(\$p) {
        echo \$p->nome_do_projeto . ': ' . \$p->total . ' auditorias' . PHP_EOL;
    });
"
```

### Verificar NCs mais frequentes
```php
php artisan tinker --execute="
DB::table('nao_conformidades as nc')
    ->join('auditoria_nao_conformidade as anc', 'nc.id', '=', 'anc.nao_conformidade_id')
    ->select('nc.sigla', DB::raw('COUNT(*) as ocorrencias'))
    ->groupBy('nc.id', 'nc.sigla')
    ->orderByDesc('ocorrencias')
    ->limit(10)
    ->get();
"
```

### Verificar distribuição de NCs por projeto
```php
php artisan tinker --execute="
DB::table('auditorias as a')
    ->join('auditoria_nao_conformidade as anc', 'a.id', '=', 'anc.auditoria_id')
    ->select('a.nome_do_projeto', DB::raw('COUNT(DISTINCT anc.nao_conformidade_id) as total_ncs'))
    ->groupBy('a.nome_do_projeto')
    ->orderByDesc('total_ncs')
    ->get();
"
```

### Verificar status de resolução das NCs
```php
php artisan tinker --execute="
DB::table('auditoria_nao_conformidade')
    ->select('resolvido', DB::raw('COUNT(*) as total'))
    ->groupBy('resolvido')
    ->get()
    ->each(function(\$r) {
        \$percentual = round((\$r->total / 1203) * 100, 2);
        echo \$r->resolvido . ': ' . \$r->total . ' (' . \$percentual . '%)' . PHP_EOL;
    });
"
```

### Verificar NCs não resolvidas por projeto
```php
php artisan tinker --execute="
DB::table('auditorias as a')
    ->join('auditoria_nao_conformidade as anc', 'a.id', '=', 'anc.auditoria_id')
    ->where('anc.resolvido', 'não')
    ->select('a.nome_do_projeto', DB::raw('COUNT(*) as ncs_abertas'))
    ->groupBy('a.nome_do_projeto')
    ->orderByDesc('ncs_abertas')
    ->get();
"
```

### Verificar taxa de resolução por analista
```php
php artisan tinker --execute="
DB::table('auditorias as a')
    ->join('auditoria_nao_conformidade as anc', 'a.id', '=', 'anc.auditoria_id')
    ->select(
        'a.analista_responsavel',
        DB::raw('COUNT(*) as total_ncs'),
        DB::raw(\"SUM(CASE WHEN anc.resolvido = 'sim' THEN 1 ELSE 0 END) as resolvidas\"),
        DB::raw(\"ROUND((SUM(CASE WHEN anc.resolvido = 'sim' THEN 1 ELSE 0 END)::numeric / COUNT(*)) * 100, 2) as taxa_resolucao\")
    )
    ->groupBy('a.analista_responsavel')
    ->orderByDesc('taxa_resolucao')
    ->get();
"
```

---

## Estrutura do Código

### Métodos Principais

```
run()
├── truncateTables()        // Limpa tabelas
├── createTiposAuditoria()  // Cria 3 tipos
├── createAnalistas()       // Cria 10 analistas
├── createNaoConformidades() // Cria 106 NCs
├── createAuditorias()      // Cria 114 auditorias
├── createRelacionamentos() // Cria 1203 relacionamentos
└── printStatistics()       // Exibe resumo
```

### Métodos Auxiliares

```
getTipoAuditoriaIdByNome()           // Busca ID do tipo
determinarAnalistaParaPeriodo()      // Determina analista do período
getAnalistaNomeById()                // Busca nome do analista
gerarPercentualAleatorio()           // Gera % realista
gerarTarefaRedmine()                 // Gera ID Redmine
getNaoConformidadesIdsBySiglas()     // Busca IDs das NCs
distribuirNaoConformidades()         // Distribui NCs
selecionarNcsAleatorias()            // Seleciona NCs aleatórias
getProjetos()                        // Retorna array de projetos
```

---

## Customização

### Adicionar Novo Projeto

No método `getProjetos()`, adicione:

```php
[
    'nome' => 'NOVO PROJETO',
    'tipo' => 'Produto', // ou 'Serviço' ou 'Qualidade'
    'total_ncs' => 50,
    'analistas' => [
        ['identificador' => 'AYMMEE', 'periodos' => 'todos']
    ],
    'periodos' => [
        ['periodo' => 'Janeiro 2025', 'data' => '2025-01-31', 'processo' => null, 'produto' => null],
    ],
    'siglas_ncs' => ['PR 1', 'PR 2', 'GPR 1', 'RSKM 1']
],
```

### Adicionar Novo Analista

No array `$analistas`, adicione:

```php
[
    'name' => 'Novo Analista',
    'email' => 'novo.analista@auditor.com',
    'cargo' => 'analista',
    'identificador' => 'NOVO'
],
```

### Adicionar Nova NC

No array `$naoConformidades`, adicione:

```php
[
    'sigla' => 'NOVA 1',
    'descricao' => 'Descrição da nova NC',
    'tipo' => 'Produto' // ou 'Serviço' ou 'Qualidade'
],
```

### Alterar Senha Padrão

No método `createAnalistas()`, altere:

```php
'password' => Hash::make('sua_senha_aqui'),
```

---

## Uso no Dashboard

Os dados gerados são perfeitos para testar o dashboard implementado:

```bash
# 1. Rodar seeder
php artisan migrate:fresh --seed --seeder=AuditoriaCompletaSeeder

# 2. Compilar frontend
npm run build

# 3. Acessar dashboard
# http://localhost/dashboard
```

### Dados Disponíveis no Dashboard

- ✅ **Total de Auditorias**: 114
- ✅ **Auditorias por Tipo**: 3 categorias com distribuição
- ✅ **Distribuição de NCs**: Faixas de 0 NC, 1-3 NC, 4-6 NC, 7+ NC
- ✅ **NCs por Tipo**: Produto, Serviço, Qualidade
- ✅ **Média Processo/Produto**: Por tipo de auditoria
- ✅ **Timeline**: Evolução de Jan/2024 a Ago/2025
- ✅ **Top NCs**: As 10 não conformidades mais frequentes
- ✅ **Distribuição por Analista**: 10 analistas com suas cargas

---

## Troubleshooting

### Erro: "Foreign key constraint fails"

**Solução**: Certifique-se que as migrations estão atualizadas:
```bash
php artisan migrate:fresh
php artisan db:seed --class=AuditoriaCompletaSeeder
```

### Erro: "Call to a member function on null"

**Solução**: Verifique se o tipo de auditoria existe no mapeamento.

### Performance lenta

**Solução**: Verifique os índices do banco:
```sql
SELECT * FROM pg_indexes WHERE tablename IN ('auditorias', 'nao_conformidades', 'auditoria_nao_conformidade');
```

### Dados duplicados

**Solução**: O seeder faz truncate automático. Se houver duplicados, execute manualmente:
```php
DB::table('auditoria_nao_conformidade')->truncate();
DB::table('auditorias')->truncate();
```

---

## Testes Automatizados

### Verificar Integridade

```bash
php artisan tinker --execute="
// Verificar que todas as auditorias têm tipo válido
\$invalidos = DB::table('auditorias as a')
    ->leftJoin('tipo_auditorias as ta', 'a.tipo_auditorias_id', '=', 'ta.id')
    ->whereNull('ta.id')
    ->count();
echo 'Auditorias com tipo inválido: ' . \$invalidos . PHP_EOL;

// Verificar que todas as NCs têm tipo válido
\$invalidos = DB::table('nao_conformidades as nc')
    ->leftJoin('tipo_auditorias as ta', 'nc.tipo_auditoria_id', '=', 'ta.id')
    ->whereNull('ta.id')
    ->count();
echo 'NCs com tipo inválido: ' . \$invalidos . PHP_EOL;

// Verificar que todos os relacionamentos são válidos
\$invalidos = DB::table('auditoria_nao_conformidade as anc')
    ->leftJoin('auditorias as a', 'anc.auditoria_id', '=', 'a.id')
    ->leftJoin('nao_conformidades as nc', 'anc.nao_conformidade_id', '=', 'nc.id')
    ->where(function(\$query) {
        \$query->whereNull('a.id')->orWhereNull('nc.id');
    })
    ->count();
echo 'Relacionamentos inválidos: ' . \$invalidos . PHP_EOL;

echo PHP_EOL . 'Testes concluídos!' . PHP_EOL;
"
```

---

## Manutenção

### Backup dos Dados

Antes de executar o seeder em produção:

```bash
# Backup do banco
pg_dump auditor_db > backup_$(date +%Y%m%d_%H%M%S).sql

# Ou via Laravel
php artisan backup:run
```

### Restaurar Dados

```bash
# Restaurar do backup
psql auditor_db < backup_20251105_120000.sql
```

---

## Changelog

### v1.1.0 - 05/11/2025
- ✅ **BREAKING CHANGE**: Campo `quem_criou` agora é sempre "Auditor"
- ✅ **BREAKING CHANGE**: Campo `analista_responsavel` agora representa o analista de requisitos do projeto
- ✅ Adicionado campo `resolvido` na tabela pivot `auditoria_nao_conformidade`
- ✅ Distribuição de status de resolução: ~70% resolvidas, ~30% abertas
- ✅ Migration criada: `add_resolvido_to_auditoria_nao_conformidade_table`
- ✅ Documentação atualizada com exemplos de queries
- ✅ Seção "Entendendo os Papéis" adicionada

### v1.0.0 - 05/11/2025
- ✅ Implementação inicial
- ✅ 11 projetos com dados realistas
- ✅ 10 analistas com distribuição por projeto
- ✅ 106 não conformidades categorizadas
- ✅ Distribuição inteligente de NCs
- ✅ Percentuais realistas por tipo
- ✅ Datas cronológicas (2024-2025)
- ✅ Bulk inserts otimizados
- ✅ Transaction com rollback automático
- ✅ Documentação completa

---

## Suporte

Para questões ou problemas:
1. Verificar este documento
2. Executar testes de integridade
3. Consultar logs do Laravel: `storage/logs/laravel.log`
4. Verificar queries no telescope (se instalado)

---

## Conclusão

O `AuditoriaCompletaSeeder` fornece uma base de dados realista e completa para desenvolvimento e testes do sistema de auditorias. Todos os dados são gerados de forma consistente e seguem as regras de negócio do sistema.

**Status**: ✅ Pronto para uso em desenvolvimento e testes
**Performance**: ✅ Otimizado com bulk inserts
**Integridade**: ✅ Validada com foreign keys
**Documentação**: ✅ Completa e atualizada

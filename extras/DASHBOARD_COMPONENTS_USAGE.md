# Guia de Uso dos Componentes do Dashboard

Este guia fornece exemplos práticos de como usar cada componente do dashboard individualmente.

## Índice

1. [MetricCard](#metriccard)
2. [ChartBarAuditoriasTipo](#chartbarauditoriastipo)
3. [ChartBarDistribuicaoNc](#chartbardistribuicaonc)
4. [ChartBarNcPorTipo](#chartbarncportipo)
5. [ChartBarMediaProcessoProduto](#chartbarmediaprocessoproduto)
6. [ChartLineTimeline](#chartlinetimeline)
7. [ChartTableTopNc](#charttabletopnc)
8. [ChartBarAnalistas](#chartbaranalistas)
9. [Hook useDashboard](#hook-usedashboard)

---

## MetricCard

Componente para exibir métricas simples.

### Import

```tsx
import { MetricCard } from '@/components/dashboard/metric-card';
import { FileCheck, AlertTriangle, Users } from 'lucide-react';
```

### Exemplos de Uso

#### Métrica Simples

```tsx
<MetricCard
  title="Total de Auditorias"
  value={150}
/>
```

#### Com Descrição

```tsx
<MetricCard
  title="Total de Auditorias"
  value={150}
  description="Todas as auditorias registradas no sistema"
/>
```

#### Com Ícone

```tsx
<MetricCard
  title="Não Conformidades"
  value={87}
  description="Total de NCs identificadas"
  icon={AlertTriangle}
/>
```

#### Grid de Métricas

```tsx
<div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
  <MetricCard
    title="Auditorias"
    value={150}
    icon={FileCheck}
  />
  <MetricCard
    title="Não Conformidades"
    value={87}
    icon={AlertTriangle}
  />
  <MetricCard
    title="Analistas"
    value={12}
    icon={Users}
  />
  <MetricCard
    title="Taxa de Aprovação"
    value="94.2%"
    icon={Activity}
  />
</div>
```

---

## ChartBarAuditoriasTipo

Gráfico de barras mostrando auditorias por tipo.

### Import

```tsx
import { ChartBarAuditoriasTipo } from '@/components/charts/chart-bar-auditorias-tipo';
```

### Formato dos Dados

```typescript
const data = [
  { tipo: "Qualidade", quantidade: 75 },
  { tipo: "Segurança", quantidade: 45 },
  { tipo: "Ambiental", quantidade: 30 }
];
```

### Exemplos de Uso

#### Básico

```tsx
<ChartBarAuditoriasTipo data={auditoriasPorTipo} />
```

#### Customizado

```tsx
<ChartBarAuditoriasTipo
  data={auditoriasPorTipo}
  title="Auditorias Realizadas por Categoria"
  description="Distribuição de auditorias no último trimestre"
  showFooter={true}
/>
```

#### Sem Footer

```tsx
<ChartBarAuditoriasTipo
  data={auditoriasPorTipo}
  showFooter={false}
/>
```

---

## ChartBarDistribuicaoNc

Gráfico de barras coloridas mostrando distribuição de NCs.

### Import

```tsx
import { ChartBarDistribuicaoNc } from '@/components/charts/chart-bar-distribuicao-nc';
```

### Formato dos Dados

```typescript
const data = [
  { label: "0 NC", quantidade: 30 },
  { label: "1-3 NC", quantidade: 80 },
  { label: "4-6 NC", quantidade: 25 },
  { label: "7+ NC", quantidade: 15 }
];
```

### Exemplos de Uso

#### Básico

```tsx
<ChartBarDistribuicaoNc data={distribuicaoNc} />
```

#### Customizado

```tsx
<ChartBarDistribuicaoNc
  data={distribuicaoNc}
  title="Distribuição de Conformidades"
  description="Faixas de não conformidades por auditoria"
/>
```

---

## ChartBarNcPorTipo

Gráfico de barras mostrando total de NCs por tipo.

### Import

```tsx
import { ChartBarNcPorTipo } from '@/components/charts/chart-bar-nc-por-tipo';
```

### Formato dos Dados

```typescript
const data = [
  { tipo: "Qualidade", total_nao_conformidades: 120 },
  { tipo: "Segurança", total_nao_conformidades: 85 },
  { tipo: "Ambiental", total_nao_conformidades: 45 }
];
```

### Exemplos de Uso

#### Básico

```tsx
<ChartBarNcPorTipo data={ncPorTipo} />
```

#### Em Grid

```tsx
<div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
  <ChartBarAuditoriasTipo data={auditoriasPorTipo} />
  <ChartBarNcPorTipo data={ncPorTipo} />
</div>
```

---

## ChartBarMediaProcessoProduto

Gráfico de barras agrupadas comparando médias.

### Import

```tsx
import { ChartBarMediaProcessoProduto } from '@/components/charts/chart-bar-media-processo-produto';
```

### Formato dos Dados

```typescript
const data = [
  {
    tipo: "Qualidade",
    media_processo: 85.50,
    media_produto: 90.20
  },
  {
    tipo: "Segurança",
    media_processo: 78.30,
    media_produto: 82.10
  }
];
```

### Exemplos de Uso

#### Básico

```tsx
<ChartBarMediaProcessoProduto data={mediaProcessoProduto} />
```

#### Customizado

```tsx
<ChartBarMediaProcessoProduto
  data={mediaProcessoProduto}
  title="Performance: Processo vs Produto"
  description="Comparativo de médias por categoria de auditoria"
/>
```

---

## ChartLineTimeline

Gráfico de linha mostrando evolução temporal.

### Import

```tsx
import { ChartLineTimeline } from '@/components/charts/chart-line-timeline';
```

### Formato dos Dados

```typescript
const data = [
  { mes: "2025-01", quantidade: 25 },
  { mes: "2025-02", quantidade: 32 },
  { mes: "2025-03", quantidade: 28 },
  { mes: "2025-04", quantidade: 45 },
  { mes: "2025-05", quantidade: 38 }
];
```

**Importante**: O campo `mes` deve estar no formato `"YYYY-MM"`.

### Exemplos de Uso

#### Básico

```tsx
<ChartLineTimeline data={timeline} />
```

#### Full Width

```tsx
<div className="w-full">
  <ChartLineTimeline
    data={timeline}
    title="Evolução Mensal de Auditorias"
    description="Quantidade de auditorias realizadas nos últimos 12 meses"
  />
</div>
```

---

## ChartTableTopNc

Tabela mostrando top não conformidades.

### Import

```tsx
import { ChartTableTopNc } from '@/components/charts/chart-table-top-nc';
```

### Formato dos Dados

```typescript
const data = [
  {
    sigla: "NC-001",
    descricao: "Documentação incompleta",
    tipo_auditoria: "Qualidade",
    ocorrencias: 45
  },
  {
    sigla: "NC-002",
    descricao: "Equipamento não calibrado",
    tipo_auditoria: "Qualidade",
    ocorrencias: 38
  }
];
```

### Exemplos de Uso

#### Básico (Top 10)

```tsx
<ChartTableTopNc data={topNcs} />
```

#### Top 5

```tsx
<ChartTableTopNc
  data={topNcs}
  maxItems={5}
  title="Top 5 Não Conformidades"
/>
```

#### Top 20

```tsx
<ChartTableTopNc
  data={topNcs}
  maxItems={20}
  title="Top 20 Não Conformidades"
  description="NCs mais recorrentes em todas as auditorias"
/>
```

---

## ChartBarAnalistas

Gráfico de barras horizontal mostrando distribuição por analista.

### Import

```tsx
import { ChartBarAnalistas } from '@/components/charts/chart-bar-analistas';
```

### Formato dos Dados

```typescript
const data = [
  { analista: "João Silva", quantidade: 60 },
  { analista: "Maria Santos", quantidade: 45 },
  { analista: "Pedro Oliveira", quantidade: 30 }
];
```

### Exemplos de Uso

#### Básico

```tsx
<ChartBarAnalistas data={distribuicaoPorAnalista} />
```

#### Em Grid com Tabela

```tsx
<div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
  <ChartTableTopNc data={topNcs} />
  <ChartBarAnalistas data={distribuicaoPorAnalista} />
</div>
```

---

## Hook useDashboard

Hook customizado para buscar dados do dashboard.

### Import

```tsx
import { useDashboard } from '@/hooks/useDashboard';
```

### Uso Básico

```tsx
function MeuDashboard() {
  const { data, loading, error, refetch } = useDashboard();

  if (loading) return <div>Carregando...</div>;
  if (error) return <div>Erro: {error.message}</div>;
  if (!data) return null;

  return (
    <div>
      <h1>Dashboard</h1>
      <ChartBarAuditoriasTipo data={data.auditorias_por_tipo} />
    </div>
  );
}
```

### Com Loading State Customizado

```tsx
function MeuDashboard() {
  const { data, loading, error } = useDashboard();

  if (loading) {
    return (
      <div className="flex items-center justify-center h-screen">
        <div className="text-center">
          <Loader2 className="h-8 w-8 animate-spin mx-auto mb-4" />
          <p>Carregando dados do dashboard...</p>
        </div>
      </div>
    );
  }

  // resto do código...
}
```

### Com Botão de Refresh

```tsx
function MeuDashboard() {
  const { data, loading, error, refetch } = useDashboard();

  return (
    <div>
      <div className="flex justify-between items-center mb-6">
        <h1>Dashboard</h1>
        <Button onClick={refetch} disabled={loading}>
          <RefreshCw className={`h-4 w-4 mr-2 ${loading ? 'animate-spin' : ''}`} />
          Atualizar
        </Button>
      </div>
      {/* resto do código... */}
    </div>
  );
}
```

---

## Exemplos de Layouts

### Layout 1: Dashboard Completo

```tsx
import { useDashboard } from '@/hooks/useDashboard';
import { MetricCard } from '@/components/dashboard/metric-card';
import { ChartBarAuditoriasTipo } from '@/components/charts/chart-bar-auditorias-tipo';
import { ChartLineTimeline } from '@/components/charts/chart-line-timeline';
import { FileCheck, AlertTriangle } from 'lucide-react';

export default function Dashboard() {
  const { data, loading, error } = useDashboard();

  if (loading) return <div>Carregando...</div>;
  if (error) return <div>Erro: {error.message}</div>;
  if (!data) return null;

  return (
    <div className="container mx-auto py-8 space-y-6">
      {/* Métricas */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <MetricCard
          title="Total de Auditorias"
          value={data.total_auditorias}
          icon={FileCheck}
        />
        <MetricCard
          title="Não Conformidades"
          value={data.nao_conformidades_por_tipo.reduce(
            (sum, item) => sum + item.total_nao_conformidades, 0
          )}
          icon={AlertTriangle}
        />
      </div>

      {/* Gráficos */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <ChartBarAuditoriasTipo data={data.auditorias_por_tipo} />
        <ChartBarNcPorTipo data={data.nao_conformidades_por_tipo} />
      </div>

      {/* Timeline */}
      <ChartLineTimeline data={data.timeline_auditorias} />
    </div>
  );
}
```

### Layout 2: Dashboard Simples

```tsx
export default function SimpleDashboard() {
  const { data, loading, error } = useDashboard();

  if (loading) return <div>Carregando...</div>;
  if (error) return <div>Erro</div>;
  if (!data) return null;

  return (
    <div className="container mx-auto py-8">
      <h1 className="text-3xl font-bold mb-6">Dashboard</h1>
      <ChartBarAuditoriasTipo data={data.auditorias_por_tipo} />
    </div>
  );
}
```

### Layout 3: Dashboard Focado em NCs

```tsx
export default function NCDashboard() {
  const { data, loading, error } = useDashboard();

  if (loading) return <div>Carregando...</div>;
  if (error) return <div>Erro</div>;
  if (!data) return null;

  return (
    <div className="container mx-auto py-8 space-y-6">
      <h1 className="text-3xl font-bold">Não Conformidades</h1>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <ChartBarDistribuicaoNc data={data.distribuicao_nao_conformidades} />
        <ChartBarNcPorTipo data={data.nao_conformidades_por_tipo} />
      </div>

      <ChartTableTopNc data={data.top_nao_conformidades} maxItems={20} />
    </div>
  );
}
```

---

## Dicas de Customização

### Alterar Cores de um Gráfico Específico

Edite o array `COLORS` no arquivo do componente:

```tsx
// Em chart-bar-auditorias-tipo.tsx
const COLORS = [
  '#ef4444', // vermelho
  '#f59e0b', // amarelo
  '#10b981', // verde
  // ...
];
```

### Adicionar Filtro aos Dados

```tsx
// Mostrar apenas top 5 tipos de auditoria
<ChartBarAuditoriasTipo
  data={data.auditorias_por_tipo
    .sort((a, b) => b.quantidade - a.quantidade)
    .slice(0, 5)
  }
/>
```

### Condicionar Exibição de Componentes

```tsx
{data.auditorias_por_tipo.length > 0 && (
  <ChartBarAuditoriasTipo data={data.auditorias_por_tipo} />
)}
```

### Adicionar Loading por Componente

```tsx
{loading ? (
  <Card className="h-[500px] flex items-center justify-center">
    <Loader2 className="h-8 w-8 animate-spin" />
  </Card>
) : (
  <ChartBarAuditoriasTipo data={data.auditorias_por_tipo} />
)}
```

---

## Troubleshooting

### Problema: "data is undefined"

**Solução**: Sempre verifique se os dados existem antes de renderizar:

```tsx
if (!data) return null;
```

### Problema: Array vazio

**Solução**: Os componentes já lidam com arrays vazios graciosamente, mas você pode adicionar uma mensagem:

```tsx
{data.auditorias_por_tipo.length === 0 ? (
  <Card>
    <CardContent className="pt-6">
      <p className="text-center text-slate-500">
        Nenhuma auditoria encontrada
      </p>
    </CardContent>
  </Card>
) : (
  <ChartBarAuditoriasTipo data={data.auditorias_por_tipo} />
)}
```

### Problema: Formato de data incorreto

**Solução**: Certifique-se de que as datas estão no formato `"YYYY-MM"`:

```typescript
const timeline = auditorias.map(a => ({
  mes: a.created_at.format('Y-m'), // Laravel Carbon
  quantidade: a.quantidade
}));
```

---

## Recursos Adicionais

- [Recharts Documentation](https://recharts.org/)
- [Lucide Icons](https://lucide.dev/)
- [Shadcn UI Components](https://ui.shadcn.com/)
- [Tailwind CSS](https://tailwindcss.com/)

---

## Suporte

Para questões específicas sobre os componentes, consulte:
1. Esta documentação
2. DASHBOARD_FRONTEND_DOCUMENTATION.md
3. DASHBOARD_INTEGRATION_GUIDE.md

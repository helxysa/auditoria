# Dashboard Frontend - Documentação

## Visão Geral

O frontend do Dashboard de Auditorias foi implementado usando React 19 com TypeScript, Inertia.js, Shadcn UI e Recharts. Esta implementação fornece uma interface rica e responsiva para visualização de métricas e análises de auditorias.

## Arquitetura

### Estrutura de Arquivos

```
resources/js/
├── types/
│   └── dashboard.ts                    # Interfaces TypeScript
├── hooks/
│   └── useDashboard.ts                 # Hook customizado para fetch de dados
├── components/
│   ├── dashboard/
│   │   └── metric-card.tsx             # Card de métrica simples
│   └── charts/
│       ├── chart-bar-auditorias-tipo.tsx      # Gráfico de auditorias por tipo
│       ├── chart-bar-distribuicao-nc.tsx      # Distribuição de NCs
│       ├── chart-bar-nc-por-tipo.tsx          # NCs por tipo
│       ├── chart-bar-media-processo-produto.tsx # Médias comparativas
│       ├── chart-line-timeline.tsx            # Timeline de auditorias
│       ├── chart-table-top-nc.tsx             # Tabela de top NCs
│       └── chart-bar-analistas.tsx            # Distribuição por analista
└── pages/
    └── dashboard/
        └── main-dashboard.tsx          # Página principal do dashboard
```

## Componentes Implementados

### 1. Hook `useDashboard()`

Hook customizado que gerencia o estado e busca de dados do dashboard.

**Localização**: `resources/js/hooks/useDashboard.ts`

**Retorno**:
```typescript
{
  data: DashboardMetrics | null;
  loading: boolean;
  error: Error | null;
  refetch: () => void;
}
```

**Uso**:
```tsx
import { useDashboard } from '@/hooks/useDashboard';

const { data, loading, error, refetch } = useDashboard();
```

### 2. MetricCard

Componente para exibir uma métrica com título, valor e descrição opcional.

**Localização**: `resources/js/components/dashboard/metric-card.tsx`

**Props**:
```typescript
{
  title: string;              // Título da métrica
  value: number | string;     // Valor a ser exibido
  description?: string;       // Descrição opcional
  icon?: LucideIcon;         // Ícone opcional
  className?: string;        // Classes CSS customizadas
}
```

**Uso**:
```tsx
import { MetricCard } from '@/components/dashboard/metric-card';
import { FileCheck } from 'lucide-react';

<MetricCard
  title="Total de Auditorias"
  value={150}
  description="Todas as auditorias registradas"
  icon={FileCheck}
/>
```

### 3. Componentes de Gráficos

Todos os componentes de gráficos seguem um padrão consistente:

**Props Comuns**:
```typescript
{
  data: TipoEspecífico[];     // Dados do gráfico
  title?: string;             // Título customizado
  description?: string;       // Descrição customizada
  showFooter?: boolean;       // Mostrar/ocultar footer (padrão: true)
}
```

#### ChartBarAuditoriasTipo
Gráfico de barras verticais mostrando quantidade de auditorias por tipo.

```tsx
import { ChartBarAuditoriasTipo } from '@/components/charts/chart-bar-auditorias-tipo';

<ChartBarAuditoriasTipo data={data.auditorias_por_tipo} />
```

#### ChartBarDistribuicaoNc
Gráfico de barras coloridas mostrando distribuição de NCs em faixas (0, 1-3, 4-6, 7+).

```tsx
import { ChartBarDistribuicaoNc } from '@/components/charts/chart-bar-distribuicao-nc';

<ChartBarDistribuicaoNc data={data.distribuicao_nao_conformidades} />
```

#### ChartBarNcPorTipo
Gráfico de barras mostrando total de NCs por tipo de auditoria.

```tsx
import { ChartBarNcPorTipo } from '@/components/charts/chart-bar-nc-por-tipo';

<ChartBarNcPorTipo data={data.nao_conformidades_por_tipo} />
```

#### ChartBarMediaProcessoProduto
Gráfico de barras agrupadas comparando médias de processo e produto.

```tsx
import { ChartBarMediaProcessoProduto } from '@/components/charts/chart-bar-media-processo-produto';

<ChartBarMediaProcessoProduto data={data.media_processo_produto_por_tipo} />
```

#### ChartLineTimeline
Gráfico de linha mostrando evolução temporal das auditorias.

```tsx
import { ChartLineTimeline } from '@/components/charts/chart-line-timeline';

<ChartLineTimeline data={data.timeline_auditorias} />
```

#### ChartTableTopNc
Tabela mostrando as não conformidades mais recorrentes.

```tsx
import { ChartTableTopNc } from '@/components/charts/chart-table-top-nc';

<ChartTableTopNc
  data={data.top_nao_conformidades}
  maxItems={10}  // Limita quantas linhas mostrar
/>
```

#### ChartBarAnalistas
Gráfico de barras horizontal mostrando distribuição de auditorias por analista.

```tsx
import { ChartBarAnalistas } from '@/components/charts/chart-bar-analistas';

<ChartBarAnalistas data={data.distribuicao_por_analista} />
```

## Página Principal

**Localização**: `resources/js/pages/dashboard/main-dashboard.tsx`

A página principal integra todos os componentes e:
- Usa o hook `useDashboard()` para buscar dados
- Implementa estados de loading e error
- Organiza os componentes em um layout responsivo
- Calcula métricas adicionais
- Oferece botão de refresh

### Estados da Página

1. **Loading**: Mostra spinner animado
2. **Error**: Exibe mensagem de erro com botão de retry
3. **Success**: Renderiza dashboard completo

### Layout Responsivo

O dashboard usa um sistema de grid responsivo:
- Mobile (< 768px): 1 coluna
- Tablet (768px - 1024px): 2 colunas
- Desktop (> 1024px): 4 colunas para métricas, 2 para gráficos

## Integração com Backend

### Endpoint Esperado

O frontend espera que o endpoint `/dashboard` retorne um JSON com esta estrutura:

```typescript
{
  total_auditorias: number;
  auditorias_por_tipo: Array<{
    tipo: string;
    quantidade: number;
  }>;
  distribuicao_nao_conformidades: Array<{
    label: string;
    quantidade: number;
  }>;
  nao_conformidades_por_tipo: Array<{
    tipo: string;
    total_nao_conformidades: number;
  }>;
  media_processo_produto_por_tipo: Array<{
    tipo: string;
    media_processo: number;
    media_produto: number;
  }>;
  timeline_auditorias: Array<{
    mes: string;  // Formato: "YYYY-MM"
    quantidade: number;
  }>;
  top_nao_conformidades: Array<{
    sigla: string;
    descricao: string;
    tipo_auditoria: string;
    ocorrencias: number;
  }>;
  distribuicao_por_analista: Array<{
    analista: string;
    quantidade: number;
  }>;
}
```

### Headers Requeridos

O hook `useDashboard` envia os seguintes headers:
```javascript
{
  'Accept': 'application/json',
  'X-Requested-With': 'XMLHttpRequest'
}
```

## Customização

### Cores dos Gráficos

As cores são definidas em cada componente usando a paleta:

```typescript
const COLORS = [
  '#3b82f6', // azul
  '#06b6d4', // ciano
  '#8b5cf6', // roxo
  '#6366f1', // índigo
  '#0ea5e9', // azul claro
  '#14b8a6', // teal
  '#a855f7', // roxo claro
  '#0284c7', // azul escuro
  '#7c3aed', // violeta
  '#2563eb', // azul royal
];
```

Para alterar as cores, edite o array `COLORS` em cada componente de gráfico.

### Tooltips

Todos os gráficos possuem tooltips customizados. Para modificar:

1. Localize o componente `CustomTooltip` dentro do arquivo do gráfico
2. Ajuste a estrutura JSX e estilos conforme necessário

Exemplo:
```tsx
const CustomTooltip = ({ active, payload }: any) => {
  if (active && payload && payload.length) {
    return (
      <div className="bg-white border border-gray-200 rounded-lg shadow-xl p-3">
        {/* Seu conteúdo customizado aqui */}
      </div>
    );
  }
  return null;
};
```

### Títulos e Descrições

Todos os componentes de gráficos aceitam props `title` e `description`:

```tsx
<ChartBarAuditoriasTipo
  data={data.auditorias_por_tipo}
  title="Meu Título Customizado"
  description="Minha descrição customizada"
/>
```

### Footer

Para ocultar o footer de qualquer gráfico:

```tsx
<ChartBarAuditoriasTipo
  data={data.auditorias_por_tipo}
  showFooter={false}
/>
```

## Tratamento de Erros

O dashboard implementa tratamento robusto de erros:

1. **Erro de Network**: Exibe mensagem específica
2. **Erro 404/500**: Mostra código e mensagem de status
3. **Dados Vazios**: Cada componente lida graciosamente com arrays vazios

## Performance

### Otimizações Implementadas

1. **Memoização**: Componentes podem ser envolvidos com `React.memo` se necessário
2. **Cálculos Eficientes**: Redutores são usados para somar totais
3. **Responsividade**: ResponsiveContainer do Recharts para gráficos fluidos
4. **Lazy Loading**: Considere implementar code splitting para a rota

### Recomendações Futuras

```tsx
// Exemplo de code splitting
const MainDashboard = lazy(() => import('@/pages/dashboard/main-dashboard'));
```

## Acessibilidade

### Recursos Implementados

- ARIA labels apropriados
- Cores com contraste adequado
- Estrutura semântica HTML
- Suporte a navegação por teclado (componentes Shadcn UI)

## Testes

Para testar o dashboard:

1. **Dados Completos**: Verifique renderização normal
2. **Dados Vazios**: Teste com arrays vazios
3. **Loading State**: Simule delay na API
4. **Error State**: Simule erro de rede

```tsx
// Mock para testes
const mockData: DashboardMetrics = {
  total_auditorias: 150,
  auditorias_por_tipo: [
    { tipo: "Qualidade", quantidade: 75 },
    { tipo: "Segurança", quantidade: 75 }
  ],
  // ... resto dos dados
};
```

## Troubleshooting

### Problema: Gráficos não aparecem

**Solução**: Verifique se o Recharts está instalado:
```bash
npm install recharts
```

### Problema: Erro de CORS

**Solução**: Configure o Laravel para aceitar requisições do frontend:
```php
// config/cors.php
'paths' => ['api/*', 'dashboard'],
```

### Problema: Dados não carregam

**Solução**: Verifique no console do navegador:
1. Se a requisição está sendo feita
2. Se o endpoint está retornando 200
3. Se o formato JSON está correto

## Próximos Passos

Melhorias sugeridas:

1. **Filtros Temporais**: Adicionar seletores de data
2. **Export**: Botão para exportar relatórios em PDF/Excel
3. **Real-time**: Implementar WebSockets para atualizações em tempo real
4. **Drill-down**: Permitir clicar em gráficos para ver detalhes
5. **Comparação**: Comparar períodos diferentes lado a lado

## Suporte

Para dúvidas ou problemas:
1. Verifique esta documentação
2. Consulte a documentação do Recharts: https://recharts.org/
3. Consulte a documentação do Shadcn UI: https://ui.shadcn.com/

## Changelog

### v1.0.0 - 2025-11-05
- Implementação inicial do dashboard
- 7 componentes de gráficos
- Hook customizado useDashboard
- Página principal responsiva
- Estados de loading e error
- Documentação completa

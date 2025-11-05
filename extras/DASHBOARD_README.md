# Dashboard de Auditorias - Frontend React

## Visão Geral

Frontend completo e moderno para visualização de métricas e análises de auditorias, implementado com React 19, TypeScript, Inertia.js, Shadcn UI e Recharts.

![Dashboard](https://img.shields.io/badge/React-19-blue)
![TypeScript](https://img.shields.io/badge/TypeScript-5.0-blue)
![Tailwind](https://img.shields.io/badge/Tailwind-3.0-blue)
![Status](https://img.shields.io/badge/Status-Ready-green)

## Demonstração

O dashboard inclui 8 visualizações diferentes:

1. **Cards de Métricas** - Total de auditorias, NCs, tipos e analistas
2. **Auditorias por Tipo** - Gráfico de barras coloridas
3. **Distribuição de NCs** - Faixas de não conformidades
4. **NCs por Tipo** - Total de problemas por categoria
5. **Processo vs Produto** - Comparação de médias
6. **Timeline** - Evolução temporal de auditorias
7. **Top NCs** - Tabela das não conformidades mais frequentes
8. **Analistas** - Distribuição de trabalho por responsável

## Início Rápido

### 1. Instalar Dependências

```bash
npm install recharts lucide-react
```

### 2. Compilar

```bash
npm run build
```

### 3. Configurar Rota

Adicione em `routes/web.php`:

```php
Route::get('/dashboard-view', function () {
    return Inertia::render('dashboard/main-dashboard');
})->name('dashboard.view');
```

### 4. Acessar

```
http://localhost/dashboard-view
```

## Estrutura do Projeto

```
resources/js/
├── types/
│   └── dashboard.ts                    # Interfaces TypeScript
├── hooks/
│   └── useDashboard.ts                 # Hook de fetch de dados
├── components/
│   ├── dashboard/
│   │   ├── metric-card.tsx             # Card de métrica
│   │   └── dashboard-skeleton.tsx      # Loading skeleton
│   ├── ui/
│   │   └── skeleton.tsx                # Componente base skeleton
│   └── charts/
│       ├── chart-bar-auditorias-tipo.tsx
│       ├── chart-bar-distribuicao-nc.tsx
│       ├── chart-bar-nc-por-tipo.tsx
│       ├── chart-bar-media-processo-produto.tsx
│       ├── chart-line-timeline.tsx
│       ├── chart-table-top-nc.tsx
│       └── chart-bar-analistas.tsx
└── pages/
    └── dashboard/
        └── main-dashboard.tsx          # Página principal
```

## Documentação

### 📚 Guias Disponíveis

1. **[DASHBOARD_IMPLEMENTATION_SUMMARY.md](./DASHBOARD_IMPLEMENTATION_SUMMARY.md)**
   - Resumo completo da implementação
   - Lista de todos os arquivos criados
   - Checklist de features implementadas

2. **[DASHBOARD_FRONTEND_DOCUMENTATION.md](./DASHBOARD_FRONTEND_DOCUMENTATION.md)**
   - Documentação técnica detalhada
   - Arquitetura e estrutura
   - Customização e performance

3. **[DASHBOARD_INTEGRATION_GUIDE.md](./DASHBOARD_INTEGRATION_GUIDE.md)**
   - Guia passo a passo de integração
   - Configuração Laravel
   - Troubleshooting

4. **[DASHBOARD_COMPONENTS_USAGE.md](./DASHBOARD_COMPONENTS_USAGE.md)**
   - Exemplos práticos de uso
   - Código de exemplo para cada componente
   - Layouts alternativos

## Exemplo de Uso

### Dashboard Completo

```tsx
import { useDashboard } from '@/hooks/useDashboard';
import { DashboardSkeleton } from '@/components/dashboard/dashboard-skeleton';
import { ChartBarAuditoriasTipo } from '@/components/charts/chart-bar-auditorias-tipo';

export default function Dashboard() {
  const { data, loading, error } = useDashboard();

  if (loading) return <DashboardSkeleton />;
  if (error) return <div>Erro: {error.message}</div>;
  if (!data) return null;

  return (
    <div className="container mx-auto py-8 space-y-6">
      <h1 className="text-3xl font-bold">Dashboard</h1>
      <ChartBarAuditoriasTipo data={data.auditorias_por_tipo} />
    </div>
  );
}
```

### Componente Individual

```tsx
import { ChartBarAuditoriasTipo } from '@/components/charts/chart-bar-auditorias-tipo';

const data = [
  { tipo: "Qualidade", quantidade: 75 },
  { tipo: "Segurança", quantidade: 45 }
];

<ChartBarAuditoriasTipo
  data={data}
  title="Auditorias por Tipo"
  description="Distribuição no último trimestre"
/>
```

## Features Implementadas

### ✅ Funcionalidades

- [x] 8 tipos diferentes de visualizações
- [x] Loading state com skeleton elegante
- [x] Error handling com retry
- [x] Refresh manual de dados
- [x] Layout 100% responsivo
- [x] Tooltips customizados
- [x] Formatação de datas em português
- [x] Type-safe com TypeScript
- [x] Cores acessíveis e consistentes

### ✅ Qualidade

- [x] Código limpo e organizado
- [x] Componentização apropriada
- [x] Tratamento de erros robusto
- [x] Tratamento de dados vazios
- [x] Documentação completa
- [x] Performance otimizada

## Endpoint da API

O frontend espera que `/dashboard` retorne:

```json
{
  "total_auditorias": 150,
  "auditorias_por_tipo": [
    { "tipo": "Qualidade", "quantidade": 75 }
  ],
  "distribuicao_nao_conformidades": [
    { "label": "0 NC", "quantidade": 30 }
  ],
  "nao_conformidades_por_tipo": [
    { "tipo": "Qualidade", "total_nao_conformidades": 120 }
  ],
  "media_processo_produto_por_tipo": [
    {
      "tipo": "Qualidade",
      "media_processo": 85.50,
      "media_produto": 90.20
    }
  ],
  "timeline_auditorias": [
    { "mes": "2025-05", "quantidade": 25 }
  ],
  "top_nao_conformidades": [
    {
      "sigla": "NC-001",
      "descricao": "Descrição",
      "tipo_auditoria": "Qualidade",
      "ocorrencias": 45
    }
  ],
  "distribuicao_por_analista": [
    { "analista": "João Silva", "quantidade": 60 }
  ]
}
```

## Customização

### Alterar Cores

Edite o array `COLORS` em cada componente de gráfico:

```tsx
const COLORS = [
  '#3b82f6', // azul
  '#06b6d4', // ciano
  // ... suas cores
];
```

### Alterar Títulos

Todos os componentes aceitam props `title` e `description`:

```tsx
<ChartBarAuditoriasTipo
  data={data}
  title="Meu Título"
  description="Minha descrição"
/>
```

### Ocultar Footer

```tsx
<ChartBarAuditoriasTipo
  data={data}
  showFooter={false}
/>
```

## Tecnologias

- **React 19** - Library UI
- **TypeScript** - Type safety
- **Inertia.js** - SPA framework for Laravel
- **Shadcn UI** - Component library
- **Recharts** - Chart library
- **Tailwind CSS** - Utility-first CSS
- **Lucide React** - Icon library

## Compatibilidade

- ✅ React 19+
- ✅ TypeScript 5+
- ✅ Node.js 18+
- ✅ Laravel 10+
- ✅ Browsers modernos (Chrome, Firefox, Safari, Edge)

## Responsividade

O dashboard é totalmente responsivo:

- **Mobile** (< 768px): Layout de 1 coluna
- **Tablet** (768px - 1024px): Layout de 2 colunas
- **Desktop** (> 1024px): Layout de 4 colunas

## Performance

- ✅ Loading lazy de componentes
- ✅ Memoização onde apropriado
- ✅ Gráficos com ResponsiveContainer
- ✅ Cache de dados no backend
- ✅ Bundle otimizado com Vite

## Acessibilidade

- ✅ ARIA labels apropriados
- ✅ Contraste de cores adequado
- ✅ Estrutura semântica HTML
- ✅ Navegação por teclado
- ✅ Screen reader friendly

## Troubleshooting

### Problema: Gráficos não aparecem

**Solução**: Instale o Recharts
```bash
npm install recharts
```

### Problema: Dados não carregam

**Solução**: Verifique se o endpoint `/dashboard` retorna JSON válido
```bash
curl -H "Accept: application/json" http://localhost/dashboard
```

### Problema: Erro de compilação

**Solução**: Limpe cache e recompile
```bash
rm -rf node_modules package-lock.json
npm install
npm run build
```

## Próximos Passos (Opcional)

- [ ] Adicionar testes unitários
- [ ] Implementar filtros de data
- [ ] Adicionar exportação PDF/Excel
- [ ] Implementar WebSockets para real-time
- [ ] Adicionar drill-down em gráficos
- [ ] Comparação entre períodos
- [ ] Dark mode

## Suporte

### Documentação
Consulte os arquivos de documentação na raiz do projeto:
- `DASHBOARD_IMPLEMENTATION_SUMMARY.md`
- `DASHBOARD_FRONTEND_DOCUMENTATION.md`
- `DASHBOARD_INTEGRATION_GUIDE.md`
- `DASHBOARD_COMPONENTS_USAGE.md`

### Links Úteis
- [Recharts Documentation](https://recharts.org/)
- [Shadcn UI](https://ui.shadcn.com/)
- [Lucide Icons](https://lucide.dev/)
- [Inertia.js](https://inertiajs.com/)

## Licença

Este código foi implementado como parte do projeto Auditor Laravel.

## Autor

Implementado por Claude Code em 2025-11-05

---

**Status**: ✅ Pronto para Produção
**Versão**: 1.0.0
**Última Atualização**: 2025-11-05

# Dashboard Backend - Guia de Uso

## Introducao

Este documento descreve como usar o endpoint do dashboard implementado e como integrar com o frontend.

## Endpoint

### GET /dashboard

Retorna todas as metricas do dashboard em um unico request.

**URL:** `http://localhost:8000/dashboard`

**Metodo:** GET

**Autenticacao:** Nenhuma (adicione se necessario)

**Headers:**
```
Accept: application/json
```

**Response:**
```json
{
    "total_auditorias": 150,
    "auditorias_por_tipo": [
        {
            "tipo": "Qualidade",
            "quantidade": 75
        },
        {
            "tipo": "Seguranca",
            "quantidade": 50
        }
    ],
    "distribuicao_nao_conformidades": [
        {
            "label": "0 NC",
            "quantidade": 30
        },
        {
            "label": "1-3 NC",
            "quantidade": 80
        },
        {
            "label": "4-6 NC",
            "quantidade": 30
        },
        {
            "label": "7+ NC",
            "quantidade": 10
        }
    ],
    "nao_conformidades_por_tipo": [
        {
            "tipo": "Qualidade",
            "total_nao_conformidades": 120
        }
    ],
    "media_processo_produto_por_tipo": [
        {
            "tipo": "Qualidade",
            "media_processo": 85.50,
            "media_produto": 90.20
        }
    ],
    "timeline_auditorias": [
        {
            "mes": "2025-05",
            "quantidade": 25
        },
        {
            "mes": "2025-06",
            "quantidade": 30
        },
        {
            "mes": "2025-07",
            "quantidade": 28
        },
        {
            "mes": "2025-08",
            "quantidade": 35
        },
        {
            "mes": "2025-09",
            "quantidade": 20
        },
        {
            "mes": "2025-10",
            "quantidade": 12
        }
    ],
    "top_nao_conformidades": [
        {
            "sigla": "NC-001",
            "descricao": "Falta de documentacao",
            "tipo_auditoria": "Qualidade",
            "ocorrencias": 45
        }
    ],
    "distribuicao_por_analista": [
        {
            "analista": "Joao Silva",
            "quantidade": 60
        },
        {
            "analista": "Maria Santos",
            "quantidade": 45
        }
    ]
}
```

## Cache

O endpoint implementa cache automatico de **15 minutos**:

- Primeira requisicao: Busca dados do banco (lento)
- Requisicoes subsequentes: Retorna do cache (rapido ~1ms)
- Apos 15 minutos: Cache expira automaticamente

### Limpando Cache Manualmente

Se voce precisar limpar o cache antes dos 15 minutos:

```bash
# Via artisan
php artisan cache:forget dashboard_metrics

# Ou limpar todo o cache
php artisan cache:clear
```

### Invalidacao Automatica

Para invalidar o cache automaticamente quando houver alteracoes, ative o Observer:

**Arquivo:** `app/Providers/AppServiceProvider.php`

```php
use App\Models\Auditoria;
use App\Observers\DashboardCacheObserver;

public function boot()
{
    Auditoria::observe(DashboardCacheObserver::class);
}
```

Agora o cache sera limpo automaticamente quando:
- Uma auditoria for criada
- Uma auditoria for atualizada
- Uma auditoria for deletada
- Uma auditoria for restaurada

## Integracao com Frontend

### React/TypeScript

```typescript
// types/dashboard.ts
export interface DashboardMetrics {
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
    mes: string;
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

// hooks/useDashboard.ts
import { useState, useEffect } from 'react';
import { DashboardMetrics } from '../types/dashboard';

export function useDashboard() {
  const [data, setData] = useState<DashboardMetrics | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<Error | null>(null);

  useEffect(() => {
    fetch('/dashboard', {
      headers: {
        'Accept': 'application/json',
      },
    })
      .then(response => response.json())
      .then(data => {
        setData(data);
        setLoading(false);
      })
      .catch(err => {
        setError(err);
        setLoading(false);
      });
  }, []);

  return { data, loading, error };
}

// components/Dashboard.tsx
import { useDashboard } from '../hooks/useDashboard';

export function Dashboard() {
  const { data, loading, error } = useDashboard();

  if (loading) return <div>Carregando...</div>;
  if (error) return <div>Erro: {error.message}</div>;
  if (!data) return null;

  return (
    <div>
      <h1>Dashboard de Auditorias</h1>
      <div>Total: {data.total_auditorias}</div>
      {/* Renderizar outros componentes */}
    </div>
  );
}
```

### Vue 3

```typescript
// composables/useDashboard.ts
import { ref, onMounted } from 'vue';
import type { DashboardMetrics } from '../types/dashboard';

export function useDashboard() {
  const data = ref<DashboardMetrics | null>(null);
  const loading = ref(true);
  const error = ref<Error | null>(null);

  const fetchDashboard = async () => {
    try {
      const response = await fetch('/dashboard', {
        headers: {
          'Accept': 'application/json',
        },
      });
      data.value = await response.json();
    } catch (err) {
      error.value = err as Error;
    } finally {
      loading.value = false;
    }
  };

  onMounted(() => {
    fetchDashboard();
  });

  return { data, loading, error, refresh: fetchDashboard };
}

// components/Dashboard.vue
<script setup lang="ts">
import { useDashboard } from '../composables/useDashboard';

const { data, loading, error } = useDashboard();
</script>

<template>
  <div v-if="loading">Carregando...</div>
  <div v-else-if="error">Erro: {{ error.message }}</div>
  <div v-else-if="data">
    <h1>Dashboard de Auditorias</h1>
    <div>Total: {{ data.total_auditorias }}</div>
  </div>
</template>
```

## Graficos

### Com Recharts (React)

```typescript
import { LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip } from 'recharts';

function TimelineChart({ data }: { data: DashboardMetrics }) {
  return (
    <LineChart width={600} height={300} data={data.timeline_auditorias}>
      <CartesianGrid strokeDasharray="3 3" />
      <XAxis dataKey="mes" />
      <YAxis />
      <Tooltip />
      <Line type="monotone" dataKey="quantidade" stroke="#8884d8" />
    </LineChart>
  );
}
```

### Com Chart.js (Vue)

```typescript
// components/TimelineChart.vue
<script setup lang="ts">
import { Line } from 'vue-chartjs';
import { computed } from 'vue';

const props = defineProps<{
  data: DashboardMetrics;
}>();

const chartData = computed(() => ({
  labels: props.data.timeline_auditorias.map(item => item.mes),
  datasets: [
    {
      label: 'Auditorias',
      data: props.data.timeline_auditorias.map(item => item.quantidade),
      borderColor: '#8884d8',
      tension: 0.1,
    },
  ],
}));

const chartOptions = {
  responsive: true,
  plugins: {
    legend: {
      display: true,
    },
  },
};
</script>

<template>
  <Line :data="chartData" :options="chartOptions" />
</template>
```

## Testes

### Executar Testes

```bash
# Todos os testes
php artisan test

# Apenas testes do dashboard
php artisan test --filter DashboardControllerTest

# Teste especifico
php artisan test --filter test_dashboard_endpoint_returns_success
```

### Coverage

```bash
# Com coverage
php artisan test --coverage
```

## Performance

### Benchmarks

```bash
# Requisicao fria (sem cache)
ab -n 100 -c 10 http://localhost:8000/dashboard

# Requisicao quente (com cache)
# Execute novamente apos a primeira
ab -n 1000 -c 100 http://localhost:8000/dashboard
```

### Monitoramento

Com Laravel Telescope:

```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

Acesse: `http://localhost:8000/telescope`

## Troubleshooting

### Cache nao esta funcionando

```bash
# Verificar configuracao do cache
php artisan config:cache

# Verificar driver de cache
php artisan tinker
>>> config('cache.default')
```

### Queries muito lentas

```bash
# Ver indices criados
php artisan tinker
>>> DB::select("SELECT * FROM pg_indexes WHERE tablename = 'auditorias'")

# Executar EXPLAIN nas queries
>>> DB::enableQueryLog();
>>> (new \App\Http\Controllers\DashboardController())->index();
>>> DB::getQueryLog();
```

### Dados incorretos

```bash
# Limpar cache e revalidar
php artisan cache:clear

# Verificar dados no banco
php artisan tinker
>>> \App\Models\Auditoria::count()
```

## Customizacao

### Alterar TTL do Cache

Em `DashboardController.php`:

```php
// Alterar de 900 (15 min) para outro valor
private const CACHE_TTL = 1800; // 30 minutos
```

### Adicionar Novas Metricas

1. Criar metodo privado no controller:
```php
private function getMinhaMetrica(): array
{
    return [...];
}
```

2. Adicionar ao metodo `index()`:
```php
return response()->json([
    // ... outras metricas
    'minha_metrica' => $this->getMinhaMetrica(),
]);
```

3. Adicionar teste:
```php
public function test_minha_metrica(): void
{
    $response = $this->get('/dashboard');
    $response->assertJsonStructure(['minha_metrica']);
}
```

## Seguranca

### Adicionar Autenticacao

Em `routes/web.php`:

```php
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');
```

### Adicionar Rate Limiting

```php
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('throttle:60,1') // 60 requests por minuto
    ->name('dashboard');
```

### Adicionar CORS

Em `config/cors.php`:

```php
'paths' => ['dashboard'],
'allowed_origins' => ['https://frontend.exemplo.com'],
```

## Proximos Passos

1. Implementar autenticacao e autorizacao
2. Adicionar filtros por periodo
3. Criar endpoint de exportacao (CSV/PDF)
4. Implementar WebSockets para atualizacao em tempo real
5. Adicionar metricas de performance com APM

## Suporte

Para questoes ou problemas, consulte:
- Documentacao Laravel: https://laravel.com/docs
- Repositorio do projeto: [URL do repositorio]
- Issues: [URL das issues]

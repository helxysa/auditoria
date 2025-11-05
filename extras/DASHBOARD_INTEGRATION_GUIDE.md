# Guia de Integração do Dashboard

Este guia mostra como integrar a página do dashboard no seu projeto Laravel com Inertia.js.

## 1. Adicionar Rota no Laravel

Adicione a seguinte rota no arquivo `routes/web.php`:

```php
use App\Http\Controllers\DashboardController;

Route::get('/dashboard-view', function () {
    return Inertia::render('dashboard/main-dashboard');
})->name('dashboard.view');
```

Ou se você já tem o DashboardController:

```php
Route::get('/dashboard-view', [DashboardController::class, 'view'])
    ->name('dashboard.view');
```

E no controller:

```php
public function view()
{
    return Inertia::render('dashboard/main-dashboard');
}
```

## 2. Verificar Endpoint da API

Certifique-se de que o endpoint `/dashboard` já está implementado e retorna JSON:

```php
// routes/web.php
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard.data');
```

O método `index` deve retornar JSON quando a requisição for AJAX:

```php
public function index(Request $request)
{
    $data = [
        'total_auditorias' => Auditoria::count(),
        'auditorias_por_tipo' => $this->getAuditoriasPorTipo(),
        // ... resto dos dados
    ];

    if ($request->wantsJson() || $request->ajax()) {
        return response()->json($data);
    }

    // Se não for AJAX, retorna a view Inertia
    return Inertia::render('dashboard/main-dashboard');
}
```

## 3. Configurar Navegação

Adicione um link no seu menu/navbar para acessar o dashboard:

```tsx
// Em seu componente de navegação
import { Link } from '@inertiajs/react';

<Link href="/dashboard-view" className="nav-link">
  Dashboard
</Link>
```

## 4. Verificar Dependências

Certifique-se de que todas as dependências estão instaladas:

```bash
npm install recharts lucide-react
```

Verifique no `package.json`:

```json
{
  "dependencies": {
    "react": "^19.x",
    "recharts": "^2.x",
    "lucide-react": "^0.x"
  }
}
```

## 5. Build do Frontend

Compile os assets:

```bash
npm run build
```

Ou para desenvolvimento:

```bash
npm run dev
```

## 6. Testar a Integração

### Teste o Endpoint da API

```bash
curl -H "Accept: application/json" http://localhost/dashboard
```

Deve retornar um JSON válido com todas as métricas.

### Teste a Página

Acesse no navegador:
```
http://localhost/dashboard-view
```

Você deve ver o dashboard completo carregando.

## 7. Configuração Avançada

### Cache de Dados

Para melhor performance, considere cachear os dados do dashboard:

```php
public function index(Request $request)
{
    $data = Cache::remember('dashboard_metrics', now()->addMinutes(5), function () {
        return [
            'total_auditorias' => Auditoria::count(),
            'auditorias_por_tipo' => $this->getAuditoriasPorTipo(),
            // ... resto dos dados
        ];
    });

    if ($request->wantsJson() || $request->ajax()) {
        return response()->json($data);
    }

    return Inertia::render('dashboard/main-dashboard');
}
```

### Middleware de Autenticação

Proteja as rotas com middleware:

```php
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard-view', [DashboardController::class, 'view'])
        ->name('dashboard.view');
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard.data');
});
```

### Permissões

Se você tem sistema de permissões:

```php
Route::middleware(['auth', 'can:view-dashboard'])->group(function () {
    Route::get('/dashboard-view', [DashboardController::class, 'view']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
```

## 8. Exemplo de Controller Completo

```php
<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\NaoConformidade;
use App\Models\TipoAuditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function view()
    {
        return Inertia::render('dashboard/main-dashboard');
    }

    public function index(Request $request)
    {
        $data = Cache::remember('dashboard_metrics', now()->addMinutes(5), function () {
            return [
                'total_auditorias' => $this->getTotalAuditorias(),
                'auditorias_por_tipo' => $this->getAuditoriasPorTipo(),
                'distribuicao_nao_conformidades' => $this->getDistribuicaoNaoConformidades(),
                'nao_conformidades_por_tipo' => $this->getNaoConformidadesPorTipo(),
                'media_processo_produto_por_tipo' => $this->getMediaProcessoProdutoPorTipo(),
                'timeline_auditorias' => $this->getTimelineAuditorias(),
                'top_nao_conformidades' => $this->getTopNaoConformidades(),
                'distribuicao_por_analista' => $this->getDistribuicaoPorAnalista(),
            ];
        });

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($data);
        }

        return Inertia::render('dashboard/main-dashboard');
    }

    private function getTotalAuditorias()
    {
        return Auditoria::count();
    }

    private function getAuditoriasPorTipo()
    {
        return TipoAuditoria::withCount('auditorias')
            ->get()
            ->map(function ($tipo) {
                return [
                    'tipo' => $tipo->nome,
                    'quantidade' => $tipo->auditorias_count,
                ];
            })
            ->toArray();
    }

    private function getDistribuicaoNaoConformidades()
    {
        $auditorias = Auditoria::withCount('naoConformidades')->get();

        $distribuicao = [
            '0 NC' => 0,
            '1-3 NC' => 0,
            '4-6 NC' => 0,
            '7+ NC' => 0,
        ];

        foreach ($auditorias as $auditoria) {
            $count = $auditoria->nao_conformidades_count;

            if ($count === 0) {
                $distribuicao['0 NC']++;
            } elseif ($count >= 1 && $count <= 3) {
                $distribuicao['1-3 NC']++;
            } elseif ($count >= 4 && $count <= 6) {
                $distribuicao['4-6 NC']++;
            } else {
                $distribuicao['7+ NC']++;
            }
        }

        return collect($distribuicao)->map(function ($quantidade, $label) {
            return [
                'label' => $label,
                'quantidade' => $quantidade,
            ];
        })->values()->toArray();
    }

    private function getNaoConformidadesPorTipo()
    {
        return TipoAuditoria::withCount('naoConformidades')
            ->get()
            ->map(function ($tipo) {
                return [
                    'tipo' => $tipo->nome,
                    'total_nao_conformidades' => $tipo->nao_conformidades_count,
                ];
            })
            ->toArray();
    }

    private function getMediaProcessoProdutoPorTipo()
    {
        return TipoAuditoria::with('auditorias')
            ->get()
            ->map(function ($tipo) {
                return [
                    'tipo' => $tipo->nome,
                    'media_processo' => $tipo->auditorias->avg('processo') ?? 0,
                    'media_produto' => $tipo->auditorias->avg('produto') ?? 0,
                ];
            })
            ->toArray();
    }

    private function getTimelineAuditorias()
    {
        return Auditoria::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mes'),
                DB::raw('COUNT(*) as quantidade')
            )
            ->groupBy('mes')
            ->orderBy('mes', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'mes' => $item->mes,
                    'quantidade' => $item->quantidade,
                ];
            })
            ->toArray();
    }

    private function getTopNaoConformidades()
    {
        return NaoConformidade::with(['tipoAuditoria', 'auditorias'])
            ->get()
            ->map(function ($nc) {
                return [
                    'sigla' => $nc->sigla,
                    'descricao' => $nc->descricao ?? 'Sem descrição',
                    'tipo_auditoria' => $nc->tipoAuditoria->nome ?? 'N/A',
                    'ocorrencias' => $nc->auditorias->count(),
                ];
            })
            ->sortByDesc('ocorrencias')
            ->take(10)
            ->values()
            ->toArray();
    }

    private function getDistribuicaoPorAnalista()
    {
        return Auditoria::select('analista_responsavel', DB::raw('COUNT(*) as quantidade'))
            ->groupBy('analista_responsavel')
            ->orderBy('quantidade', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'analista' => $item->analista_responsavel,
                    'quantidade' => $item->quantidade,
                ];
            })
            ->toArray();
    }
}
```

## 9. Limpar Cache

Se fez alterações e o dashboard não atualiza:

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

E recompile o frontend:

```bash
npm run build
```

## 10. Debug

### Verificar se a página está registrada no Inertia

```bash
php artisan route:list | grep dashboard
```

Deve mostrar:
```
GET|HEAD  dashboard ......... dashboard.data › DashboardController@index
GET|HEAD  dashboard-view .... dashboard.view › DashboardController@view
```

### Verificar erros no console do navegador

Abra o DevTools (F12) e verifique:
- Console: erros JavaScript
- Network: status das requisições
- Response: formato do JSON retornado

### Verificar logs do Laravel

```bash
tail -f storage/logs/laravel.log
```

## 11. Troubleshooting

### Problema: "Module not found"

Verifique os alias no `tsconfig.json`:

```json
{
  "compilerOptions": {
    "baseUrl": ".",
    "paths": {
      "@/*": ["./resources/js/*"]
    }
  }
}
```

E no `vite.config.ts`:

```typescript
import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.tsx'],
            refresh: true,
        }),
        react(),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
        },
    },
});
```

### Problema: Dados não carregam

1. Verifique se o endpoint retorna JSON:
```bash
curl -H "Accept: application/json" http://localhost/dashboard
```

2. Verifique o CORS no Laravel
3. Verifique se não há erro 500 no backend

### Problema: Gráficos não renderizam

1. Verifique se o Recharts está instalado
2. Limpe o cache do npm: `npm cache clean --force`
3. Reinstale dependências: `rm -rf node_modules package-lock.json && npm install`

## 12. Pronto!

Agora seu dashboard deve estar funcionando perfeitamente. Acesse:

```
http://localhost/dashboard-view
```

E você verá todas as métricas e gráficos renderizados!

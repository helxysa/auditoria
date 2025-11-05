# Checklist de Integração do Dashboard

Use este checklist para garantir que o dashboard foi integrado corretamente ao projeto.

## 📋 Pré-requisitos

### Dependências NPM

- [ ] Recharts instalado
  ```bash
  npm install recharts
  ```
- [ ] Lucide React instalado (provavelmente já está)
  ```bash
  npm install lucide-react
  ```
- [ ] Verificar `package.json` contém:
  ```json
  {
    "dependencies": {
      "recharts": "^2.x",
      "lucide-react": "^0.x"
    }
  }
  ```

### Arquivos Frontend Criados

Verifique se todos estes arquivos existem:

#### Types
- [ ] `resources/js/types/dashboard.ts`

#### Hooks
- [ ] `resources/js/hooks/useDashboard.ts`

#### Componentes Dashboard
- [ ] `resources/js/components/dashboard/metric-card.tsx`
- [ ] `resources/js/components/dashboard/dashboard-skeleton.tsx`

#### Componentes UI
- [ ] `resources/js/components/ui/skeleton.tsx`

#### Componentes de Gráficos
- [ ] `resources/js/components/charts/chart-bar-auditorias-tipo.tsx`
- [ ] `resources/js/components/charts/chart-bar-distribuicao-nc.tsx`
- [ ] `resources/js/components/charts/chart-bar-nc-por-tipo.tsx`
- [ ] `resources/js/components/charts/chart-bar-media-processo-produto.tsx`
- [ ] `resources/js/components/charts/chart-line-timeline.tsx`
- [ ] `resources/js/components/charts/chart-table-top-nc.tsx`
- [ ] `resources/js/components/charts/chart-bar-analistas.tsx`

#### Páginas
- [ ] `resources/js/pages/dashboard/main-dashboard.tsx`

## ⚙️ Configuração Backend

### Rotas Laravel

- [ ] Adicionar rota para visualização do dashboard em `routes/web.php`:
  ```php
  Route::get('/dashboard-view', function () {
      return Inertia::render('dashboard/main-dashboard');
  })->name('dashboard.view');
  ```

- [ ] Verificar se rota da API existe:
  ```php
  Route::get('/dashboard', [DashboardController::class, 'index'])
      ->name('dashboard.data');
  ```

- [ ] Executar e verificar rotas:
  ```bash
  php artisan route:list | grep dashboard
  ```

### Controller

- [ ] `app/Http/Controllers/DashboardController.php` existe
- [ ] Método `index()` retorna JSON quando requisição é AJAX
- [ ] Todos os métodos privados de cálculo implementados:
  - [ ] `getTotalAuditorias()`
  - [ ] `getAuditoriasPorTipo()`
  - [ ] `getDistribuicaoNaoConformidades()`
  - [ ] `getNaoConformidadesPorTipo()`
  - [ ] `getMediaProcessoProdutoPorTipo()`
  - [ ] `getTimelineAuditorias()`
  - [ ] `getTopNaoConformidades()`
  - [ ] `getDistribuicaoPorAnalista()`

### Models e Relacionamentos

- [ ] Model `Auditoria` tem relacionamentos:
  - [ ] `belongsTo(TipoAuditoria::class)`
  - [ ] `belongsToMany(NaoConformidade::class)`
  - [ ] `belongsTo(Analista::class)` ou campo `analista_responsavel`

- [ ] Model `NaoConformidade` tem relacionamentos:
  - [ ] `belongsTo(TipoAuditoria::class)`
  - [ ] `belongsToMany(Auditoria::class)`

- [ ] Model `TipoAuditoria` tem relacionamentos:
  - [ ] `hasMany(Auditoria::class)`
  - [ ] `hasMany(NaoConformidade::class)`

## 🔨 Build e Compilação

### Compilar Frontend

- [ ] Executar build para produção:
  ```bash
  npm run build
  ```

- [ ] OU executar em modo desenvolvimento:
  ```bash
  npm run dev
  ```

- [ ] Verificar se não há erros de compilação
- [ ] Verificar se arquivos foram gerados em `public/build/`

### Limpar Cache Laravel

- [ ] Limpar cache de configuração:
  ```bash
  php artisan config:clear
  ```

- [ ] Limpar cache de rotas:
  ```bash
  php artisan route:clear
  ```

- [ ] Limpar cache de views:
  ```bash
  php artisan view:clear
  ```

- [ ] (Opcional) Limpar cache de dados:
  ```bash
  php artisan cache:clear
  ```

## 🧪 Testes

### Testar Endpoint da API

- [ ] Testar endpoint via cURL:
  ```bash
  curl -H "Accept: application/json" http://localhost/dashboard
  ```

- [ ] Verificar se retorna JSON válido
- [ ] Verificar se todas as chaves estão presentes:
  - [ ] `total_auditorias`
  - [ ] `auditorias_por_tipo`
  - [ ] `distribuicao_nao_conformidades`
  - [ ] `nao_conformidades_por_tipo`
  - [ ] `media_processo_produto_por_tipo`
  - [ ] `timeline_auditorias`
  - [ ] `top_nao_conformidades`
  - [ ] `distribuicao_por_analista`

### Testar Página Frontend

- [ ] Acessar `http://localhost/dashboard-view` no navegador
- [ ] Verificar se a página carrega
- [ ] Verificar se o skeleton de loading aparece brevemente
- [ ] Verificar se todos os gráficos renderizam

### Testar Funcionalidades

- [ ] **Loading State**
  - [ ] Skeleton aparece ao carregar
  - [ ] Transição suave para dados reais

- [ ] **Error State**
  - [ ] Simular erro (desligar backend)
  - [ ] Verificar se mensagem de erro aparece
  - [ ] Verificar se botão "Tentar novamente" funciona

- [ ] **Métricas**
  - [ ] 4 cards de métricas aparecem
  - [ ] Valores estão corretos
  - [ ] Ícones aparecem

- [ ] **Gráficos**
  - [ ] Gráfico "Auditorias por Tipo" renderiza
  - [ ] Gráfico "Distribuição de NCs" renderiza
  - [ ] Gráfico "NCs por Tipo" renderiza
  - [ ] Gráfico "Média Processo vs Produto" renderiza
  - [ ] Gráfico de Timeline renderiza
  - [ ] Tabela "Top NCs" renderiza
  - [ ] Gráfico "Analistas" renderiza

- [ ] **Tooltips**
  - [ ] Hover em barras mostra tooltip
  - [ ] Tooltips têm informações corretas
  - [ ] Formatação está adequada

- [ ] **Responsividade**
  - [ ] Testar em mobile (< 768px)
  - [ ] Testar em tablet (768px - 1024px)
  - [ ] Testar em desktop (> 1024px)
  - [ ] Layout se ajusta corretamente

- [ ] **Botão Refresh**
  - [ ] Botão "Atualizar" aparece
  - [ ] Clique recarrega dados
  - [ ] Loading é mostrado durante refresh

## 🎨 Verificações Visuais

### Cores e Estilo

- [ ] Cores dos gráficos são consistentes
- [ ] Texto é legível em todos os fundos
- [ ] Cards têm sombra e bordas apropriadas
- [ ] Espaçamento entre elementos é adequado
- [ ] Fonte e tamanhos são consistentes

### Animações

- [ ] Skeleton tem animação de pulse
- [ ] Transições são suaves
- [ ] Hover effects funcionam
- [ ] Loading spinner funciona

## 🔐 Segurança e Permissões

### Autenticação

- [ ] (Se aplicável) Rota requer autenticação:
  ```php
  Route::middleware(['auth'])->group(function () {
      Route::get('/dashboard-view', ...);
  });
  ```

### Permissões

- [ ] (Se aplicável) Verificar permissões do usuário:
  ```php
  Route::middleware(['can:view-dashboard'])->group(function () {
      Route::get('/dashboard-view', ...);
  });
  ```

## 📱 Browser Testing

### Navegadores

Testar em múltiplos navegadores:

- [ ] Chrome/Chromium
- [ ] Firefox
- [ ] Safari (se disponível)
- [ ] Edge

### DevTools

- [ ] Abrir DevTools (F12)
- [ ] Verificar Console - sem erros JavaScript
- [ ] Verificar Network - requisições com status 200
- [ ] Verificar Response - JSON válido

## ⚡ Performance

### Métricas

- [ ] Tempo de carregamento < 3 segundos
- [ ] Gráficos renderizam rapidamente
- [ ] Sem lag ao interagir
- [ ] Memory usage é aceitável

### Otimizações (Opcional)

- [ ] Cache implementado no backend:
  ```php
  Cache::remember('dashboard_metrics', now()->addMinutes(5), ...);
  ```

- [ ] Queries otimizadas com `eager loading`:
  ```php
  Auditoria::with('tipoAuditoria', 'naoConformidades')->get();
  ```

## 🐛 Debug

### Problemas Comuns

Se algo não funcionar:

- [ ] Verificar logs do Laravel:
  ```bash
  tail -f storage/logs/laravel.log
  ```

- [ ] Verificar console do navegador (F12)
- [ ] Verificar tab Network do DevTools
- [ ] Verificar se arquivo `vite.config.ts` tem alias correto:
  ```typescript
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './resources/js'),
    },
  }
  ```

- [ ] Verificar `tsconfig.json`:
  ```json
  {
    "compilerOptions": {
      "paths": {
        "@/*": ["./resources/js/*"]
      }
    }
  }
  ```

## 📖 Documentação

### Ler Documentação

- [ ] Ler `DASHBOARD_README.md`
- [ ] Ler `DASHBOARD_IMPLEMENTATION_SUMMARY.md`
- [ ] Ler `DASHBOARD_INTEGRATION_GUIDE.md`
- [ ] Ler `DASHBOARD_COMPONENTS_USAGE.md`
- [ ] Ler `DASHBOARD_FRONTEND_DOCUMENTATION.md`

## 🚀 Deploy para Produção

### Preparação

- [ ] Compilar assets para produção:
  ```bash
  npm run build
  ```

- [ ] Verificar arquivo `.env` está configurado corretamente
- [ ] Otimizar autoload:
  ```bash
  composer dump-autoload --optimize
  ```

- [ ] Cachear configurações:
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```

### Deploy

- [ ] Commit dos arquivos frontend
- [ ] Push para repositório
- [ ] Deploy no servidor
- [ ] Executar migrations (se houver)
- [ ] Testar no ambiente de produção

## ✅ Checklist Final

Antes de considerar completo:

- [ ] ✅ Todos os arquivos frontend criados
- [ ] ✅ Backend configurado e funcionando
- [ ] ✅ Rotas configuradas
- [ ] ✅ Compilação sem erros
- [ ] ✅ Página carrega corretamente
- [ ] ✅ Todos os gráficos renderizam
- [ ] ✅ Dados são exibidos corretamente
- [ ] ✅ Responsivo em todos os tamanhos
- [ ] ✅ Sem erros no console
- [ ] ✅ Performance aceitável
- [ ] ✅ Documentação lida e compreendida

## 🎉 Conclusão

Se todos os itens acima estão marcados, o dashboard está **pronto para uso**!

### Próximos Passos

1. Compartilhar com a equipe
2. Coletar feedback
3. Implementar melhorias (veja seção "Próximos Passos" no README)
4. Adicionar mais funcionalidades conforme necessário

---

**Importante**: Mantenha este checklist atualizado conforme faz alterações no dashboard.

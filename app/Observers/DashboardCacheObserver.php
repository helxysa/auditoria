<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;

/**
 * Observer para invalidar cache do dashboard quando houver mudanças
 *
 * Para ativar, adicione no AppServiceProvider:
 *
 * use App\Models\Auditoria;
 * use App\Observers\DashboardCacheObserver;
 *
 * public function boot()
 * {
 *     Auditoria::observe(DashboardCacheObserver::class);
 * }
 */
class DashboardCacheObserver
{
    /**
     * Limpa o cache do dashboard após criar uma auditoria
     *
     * @param  mixed  $model
     * @return void
     */
    public function created($model)
    {
        $this->clearDashboardCache();
    }

    /**
     * Limpa o cache do dashboard após atualizar uma auditoria
     *
     * @param  mixed  $model
     * @return void
     */
    public function updated($model)
    {
        $this->clearDashboardCache();
    }

    /**
     * Limpa o cache do dashboard após deletar uma auditoria
     *
     * @param  mixed  $model
     * @return void
     */
    public function deleted($model)
    {
        $this->clearDashboardCache();
    }

    /**
     * Limpa o cache do dashboard após restaurar uma auditoria
     *
     * @param  mixed  $model
     * @return void
     */
    public function restored($model)
    {
        $this->clearDashboardCache();
    }

    /**
     * Remove a chave de cache do dashboard
     *
     * @return void
     */
    private function clearDashboardCache()
    {
        Cache::forget('dashboard_metrics');
    }
}

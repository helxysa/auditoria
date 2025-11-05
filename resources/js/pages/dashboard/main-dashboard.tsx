import { usePage, router } from '@inertiajs/react';
import { Card, CardContent } from '@/components/ui/card';
import { MetricCard } from '@/components/dashboard/metric-card';
import { ChartBarAuditoriasTipo } from '@/components/charts/chart-bar-auditorias-tipo';
import { ChartBarDistribuicaoNc } from '@/components/charts/chart-bar-distribuicao-nc';
import { ChartBarNcPorTipo } from '@/components/charts/chart-bar-nc-por-tipo';
import { ChartBarMediaProcessoProduto } from '@/components/charts/chart-bar-media-processo-produto';
import { ChartLineTimeline } from '@/components/charts/chart-line-timeline';
import { ChartTableTopNc } from '@/components/charts/chart-table-top-nc';
import { ChartBarAnalistas } from '@/components/charts/chart-bar-analistas';
import {
  BarChart3,
  AlertTriangle,
  FileCheck,
  Activity,
  RefreshCw,
  AlertCircle
} from 'lucide-react';
import { Button } from '@/components/ui/button';
import type { DashboardMetrics } from '@/types/dashboard';

export default function MainDashboard() {
  // Pega dados direto do Laravel via Inertia
  const { dashboard } = usePage().props as {
    dashboard: DashboardMetrics
  };

  // Função para atualizar página (recarrega dados do Laravel)
  const handleRefresh = () => {
    router.reload({ only: ['dashboard'] });
  };

  // Tratamento de dados vazios
  if (!dashboard || dashboard.total_auditorias === 0) {
    return (
      <div className="container mx-auto py-8">
        <Card>
          <CardContent className="pt-6 text-center text-slate-500">
            <AlertCircle className="mx-auto h-12 w-12 mb-4 text-slate-400" />
            <p className="text-lg font-medium">Nenhuma auditoria encontrada</p>
            <p className="text-sm mt-2">Comece criando sua primeira auditoria para visualizar o dashboard.</p>
          </CardContent>
        </Card>
      </div>
    );
  }

  // Calcular métricas adicionais
  const totalNaoConformidades = dashboard.nao_conformidades_por_tipo.reduce(
    (sum, item) => sum + item.total_nao_conformidades,
    0
  );
  const totalTiposAuditoria = dashboard.auditorias_por_tipo.length;
  const totalAnalistas = dashboard.distribuicao_por_analista.length;

  return (
    <div className="container mx-auto py-8 space-y-6">
      {/* Header com título e botão de atualizar */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold text-slate-900">Dashboard de Auditorias</h1>
          <p className="text-slate-600 mt-1">Visão geral e métricas do sistema de auditorias</p>
        </div>
        <Button onClick={handleRefresh} variant="outline" size="sm">
          <RefreshCw className="h-4 w-4 mr-2" />
          Atualizar Dados
        </Button>
      </div>

      {/* Métricas principais - Grid 4 colunas */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <MetricCard
          title="Total de Auditorias"
          value={dashboard.total_auditorias}
          description="Todas as auditorias registradas"
          icon={FileCheck}
        />
        <MetricCard
          title="Não Conformidades"
          value={totalNaoConformidades}
          description="Total de NCs identificadas"
          icon={AlertTriangle}
        />
        <MetricCard
          title="Tipos de Auditoria"
          value={totalTiposAuditoria}
          description="Categorias cadastradas"
          icon={BarChart3}
        />
        <MetricCard
          title="Analistas Ativos"
          value={totalAnalistas}
          description="Analistas responsáveis"
          icon={Activity}
        />
      </div>

      {/* Gráficos principais - Linha 1 */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <ChartBarAuditoriasTipo data={dashboard.auditorias_por_tipo} />
        <ChartBarDistribuicaoNc data={dashboard.distribuicao_nao_conformidades} />
      </div>

      {/* Gráficos principais - Linha 2 */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <ChartBarNcPorTipo data={dashboard.nao_conformidades_por_tipo} />
        <ChartBarMediaProcessoProduto data={dashboard.media_processo_produto_por_tipo} />
      </div>

      {/* Timeline full width */}
      <ChartLineTimeline data={dashboard.timeline_auditorias} />

      {/* Bottom section - Top NCs e Analistas */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <ChartTableTopNc data={dashboard.top_nao_conformidades} />
        <ChartBarAnalistas data={dashboard.distribuicao_por_analista} />
      </div>

      {/* Footer com informações adicionais */}
      <Card className="bg-slate-50">
        <CardContent className="pt-6">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
            <div>
              <p className="text-sm text-slate-600">Média de NCs por Auditoria</p>
              <p className="text-2xl font-bold text-slate-900">
                {dashboard.total_auditorias > 0
                  ? (totalNaoConformidades / dashboard.total_auditorias).toFixed(2)
                  : '0.00'
                }
              </p>
            </div>
            <div>
              <p className="text-sm text-slate-600">Auditorias por Analista</p>
              <p className="text-2xl font-bold text-slate-900">
                {totalAnalistas > 0
                  ? (dashboard.total_auditorias / totalAnalistas).toFixed(1)
                  : '0.0'
                }
              </p>
            </div>
            <div>
              <p className="text-sm text-slate-600">Período Analisado</p>
              <p className="text-2xl font-bold text-slate-900">
                {dashboard.timeline_auditorias.length} meses
              </p>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  );
}

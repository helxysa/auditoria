// Dashboard Types and Interfaces

export interface AuditoriaPorTipo {
  tipo: string;
  quantidade: number;
}

export interface DistribuicaoNaoConformidade {
  label: string;
  quantidade: number;
}

export interface NaoConformidadePorTipo {
  tipo: string;
  total_nao_conformidades: number;
}

export interface MediaProcessoProduto {
  tipo: string;
  media_processo: number;
  media_produto: number;
}

export interface TimelineAuditoria {
  mes: string;
  quantidade: number;
}

export interface TopNaoConformidade {
  sigla: string;
  descricao: string;
  tipo_auditoria: string;
  ocorrencias: number;
}

export interface DistribuicaoPorAnalista {
  analista: string;
  quantidade: number;
}

export interface DashboardMetrics {
  total_auditorias: number;
  auditorias_por_tipo: AuditoriaPorTipo[];
  distribuicao_nao_conformidades: DistribuicaoNaoConformidade[];
  nao_conformidades_por_tipo: NaoConformidadePorTipo[];
  media_processo_produto_por_tipo: MediaProcessoProduto[];
  timeline_auditorias: TimelineAuditoria[];
  top_nao_conformidades: TopNaoConformidade[];
  distribuicao_por_analista: DistribuicaoPorAnalista[];
}

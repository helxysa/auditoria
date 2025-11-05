import { AlertTriangle } from "lucide-react";
import { Bar, BarChart, CartesianGrid, XAxis, YAxis, Tooltip, Cell, ResponsiveContainer } from "recharts";

import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";

import type { NaoConformidadePorTipo } from "@/types/dashboard";

interface ChartBarNcPorTipoProps {
  data: NaoConformidadePorTipo[];
  title?: string;
  description?: string;
  showFooter?: boolean;
}

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

const CustomTooltip = ({ active, payload }: any) => {
  if (active && payload && payload.length) {
    return (
      <div className="bg-white border border-gray-200 rounded-lg shadow-xl p-3 max-w-xs">
        <p className="font-semibold text-gray-900 mb-1 break-words">{payload[0].payload.tipo}</p>
        <p className="text-sm text-gray-600">
          Não Conformidades: <span className="font-bold text-blue-600">{payload[0].value}</span>
        </p>
      </div>
    );
  }
  return null;
};

export function ChartBarNcPorTipo({
  data,
  title = "Não Conformidades por Tipo",
  description = "Total de não conformidades encontradas por tipo de auditoria",
  showFooter = true,
}: ChartBarNcPorTipoProps) {
  const totalCount = data.reduce((sum, item) => sum + item.total_nao_conformidades, 0);
  const maxTipo = data.length > 0 ? data.reduce((max, item) =>
    item.total_nao_conformidades > max.total_nao_conformidades ? item : max
  , data[0]) : null;

  return (
    <Card className="w-full">
      <CardHeader>
        <CardTitle className="text-xl">{title}</CardTitle>
        <CardDescription>{description}</CardDescription>
      </CardHeader>
      <CardContent className="px-2 md:px-6">
        <div className="w-full h-[400px]">
          <ResponsiveContainer width="100%" height="100%">
            <BarChart
              data={data}
              margin={{ top: 20, right: 20, left: 0, bottom: 60 }}
            >
              <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#e5e7eb" />
              <XAxis
                dataKey="tipo"
                tickLine={false}
                axisLine={{ stroke: '#d1d5db' }}
                angle={-45}
                textAnchor="end"
                height={80}
                interval={0}
                tick={{ fill: '#6b7280', fontSize: 11 }}
              />
              <YAxis
                tickLine={false}
                axisLine={{ stroke: '#d1d5db' }}
                tick={{ fill: '#6b7280', fontSize: 12 }}
                label={{ value: 'Quantidade', angle: -90, position: 'insideLeft', style: { fill: '#6b7280' } }}
              />
              <Tooltip content={<CustomTooltip />} cursor={{ fill: 'rgba(59, 130, 246, 0.1)' }} />
              <Bar dataKey="total_nao_conformidades" radius={[8, 8, 0, 0]} maxBarSize={80}>
                {data.map((entry, index) => (
                  <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
                ))}
              </Bar>
            </BarChart>
          </ResponsiveContainer>
        </div>
      </CardContent>
      {showFooter && (
        <CardFooter className="flex-col items-start gap-2 text-sm border-t pt-4">
          {maxTipo && (
            <div className="flex items-center gap-2 leading-none font-medium text-gray-700">
              <AlertTriangle className="h-4 w-4 text-orange-600" />
              <span>Tipo com mais NCs: <span className="text-blue-600">{maxTipo.tipo}</span> ({maxTipo.total_nao_conformidades})</span>
            </div>
          )}
          <div className="text-gray-500 leading-none">
            Total de {totalCount} não conformidades em {data.length} tipos de auditoria
          </div>
        </CardFooter>
      )}
    </Card>
  );
}

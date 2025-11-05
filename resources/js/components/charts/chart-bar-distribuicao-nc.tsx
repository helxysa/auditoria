import { TrendingUp } from "lucide-react";
import { Bar, BarChart, CartesianGrid, XAxis, YAxis, Tooltip, Cell, ResponsiveContainer } from "recharts";

import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";

import type { DistribuicaoNaoConformidade } from "@/types/dashboard";

interface ChartBarDistribuicaoNcProps {
  data: DistribuicaoNaoConformidade[];
  title?: string;
  description?: string;
  showFooter?: boolean;
}

const COLORS = [
  '#10b981', // verde (0 NC - bom)
  '#3b82f6', // azul (1-3 NC - normal)
  '#f59e0b', // amarelo (4-6 NC - atenção)
  '#ef4444', // vermelho (7+ NC - crítico)
];

const CustomTooltip = ({ active, payload }: any) => {
  if (active && payload && payload.length) {
    return (
      <div className="bg-white border border-gray-200 rounded-lg shadow-xl p-3 max-w-xs">
        <p className="font-semibold text-gray-900 mb-1">{payload[0].payload.label}</p>
        <p className="text-sm text-gray-600">
          Auditorias: <span className="font-bold text-blue-600">{payload[0].value}</span>
        </p>
      </div>
    );
  }
  return null;
};

export function ChartBarDistribuicaoNc({
  data,
  title = "Distribuição de Não Conformidades",
  description = "Quantidade de auditorias por faixa de não conformidades",
  showFooter = true,
}: ChartBarDistribuicaoNcProps) {
  const totalCount = data.reduce((sum, item) => sum + item.quantidade, 0);
  const maxFaixa = data.length > 0 ? data.reduce((max, item) =>
    item.quantidade > max.quantidade ? item : max
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
                dataKey="label"
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
              <Bar dataKey="quantidade" radius={[8, 8, 0, 0]} maxBarSize={80}>
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
          {maxFaixa && (
            <div className="flex items-center gap-2 leading-none font-medium text-gray-700">
              <TrendingUp className="h-4 w-4 text-green-600" />
              <span>Faixa mais comum: <span className="text-blue-600">{maxFaixa.label}</span> ({maxFaixa.quantidade} auditorias)</span>
            </div>
          )}
          <div className="text-gray-500 leading-none">
            Total de {totalCount} auditorias analisadas
          </div>
        </CardFooter>
      )}
    </Card>
  );
}

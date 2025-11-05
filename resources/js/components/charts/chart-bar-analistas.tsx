import { Users } from "lucide-react";
import { Bar, BarChart, CartesianGrid, XAxis, YAxis, Tooltip, Cell, ResponsiveContainer } from "recharts";

import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";

import type { DistribuicaoPorAnalista } from "@/types/dashboard";

interface ChartBarAnalistasProps {
  data: DistribuicaoPorAnalista[];
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
        <p className="font-semibold text-gray-900 mb-1 break-words">{payload[0].payload.analista}</p>
        <p className="text-sm text-gray-600">
          Auditorias: <span className="font-bold text-blue-600">{payload[0].value}</span>
        </p>
      </div>
    );
  }
  return null;
};

export function ChartBarAnalistas({
  data,
  title = "Distribuição por Analista",
  description = "Quantidade de auditorias por analista responsável",
  showFooter = true,
}: ChartBarAnalistasProps) {
  const totalCount = data.reduce((sum, item) => sum + item.quantidade, 0);
  const topAnalista = data.length > 0 ? data.reduce((max, item) =>
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
              layout="vertical"
              margin={{ top: 20, right: 20, left: 120, bottom: 20 }}
            >
              <CartesianGrid strokeDasharray="3 3" horizontal={false} stroke="#e5e7eb" />
              <XAxis
                type="number"
                tickLine={false}
                axisLine={{ stroke: '#d1d5db' }}
                tick={{ fill: '#6b7280', fontSize: 12 }}
              />
              <YAxis
                type="category"
                dataKey="analista"
                tickLine={false}
                axisLine={{ stroke: '#d1d5db' }}
                tick={{ fill: '#6b7280', fontSize: 11 }}
                width={120}
              />
              <Tooltip content={<CustomTooltip />} cursor={{ fill: 'rgba(59, 130, 246, 0.1)' }} />
              <Bar dataKey="quantidade" radius={[0, 8, 8, 0]} maxBarSize={40}>
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
          {topAnalista && (
            <div className="flex items-center gap-2 leading-none font-medium text-gray-700">
              <Users className="h-4 w-4 text-green-600" />
              <span>Analista mais ativo: <span className="text-blue-600">{topAnalista.analista}</span> ({topAnalista.quantidade} auditorias)</span>
            </div>
          )}
          <div className="text-gray-500 leading-none">
            Total de {totalCount} auditorias distribuídas entre {data.length} analistas
          </div>
        </CardFooter>
      )}
    </Card>
  );
}

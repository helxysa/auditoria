import { Activity } from "lucide-react";
import { Bar, BarChart, CartesianGrid, XAxis, YAxis, Tooltip, Legend, ResponsiveContainer } from "recharts";

import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";

import type { MediaProcessoProduto } from "@/types/dashboard";

interface ChartBarMediaProcessoProdutoProps {
  data: MediaProcessoProduto[];
  title?: string;
  description?: string;
  showFooter?: boolean;
}

const CustomTooltip = ({ active, payload }: any) => {
  if (active && payload && payload.length) {
    return (
      <div className="bg-white border border-gray-200 rounded-lg shadow-xl p-3 max-w-xs">
        <p className="font-semibold text-gray-900 mb-2 break-words">{payload[0].payload.tipo}</p>
        <div className="space-y-1">
          <p className="text-sm text-gray-600">
            Média Processo: <span className="font-bold text-blue-600">{payload[0].value.toFixed(2)}%</span>
          </p>
          <p className="text-sm text-gray-600">
            Média Produto: <span className="font-bold text-purple-600">{payload[1].value.toFixed(2)}%</span>
          </p>
        </div>
      </div>
    );
  }
  return null;
};

export function ChartBarMediaProcessoProduto({
  data,
  title = "Média Processo vs Produto",
  description = "Comparação das médias de processo e produto por tipo de auditoria",
  showFooter = true,
}: ChartBarMediaProcessoProdutoProps) {
  const avgProcesso = data.length > 0
    ? data.reduce((sum, item) => sum + item.media_processo, 0) / data.length
    : 0;
  const avgProduto = data.length > 0
    ? data.reduce((sum, item) => sum + item.media_produto, 0) / data.length
    : 0;

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
                label={{ value: 'Média (%)', angle: -90, position: 'insideLeft', style: { fill: '#6b7280' } }}
              />
              <Tooltip content={<CustomTooltip />} cursor={{ fill: 'rgba(59, 130, 246, 0.05)' }} />
              <Legend
                wrapperStyle={{ paddingTop: '20px' }}
                iconType="circle"
              />
              <Bar
                dataKey="media_processo"
                name="Média Processo"
                fill="#3b82f6"
                radius={[8, 8, 0, 0]}
                maxBarSize={40}
              />
              <Bar
                dataKey="media_produto"
                name="Média Produto"
                fill="#8b5cf6"
                radius={[8, 8, 0, 0]}
                maxBarSize={40}
              />
            </BarChart>
          </ResponsiveContainer>
        </div>
      </CardContent>
      {showFooter && (
        <CardFooter className="flex-col items-start gap-2 text-sm border-t pt-4">
          <div className="flex items-center gap-2 leading-none font-medium text-gray-700">
            <Activity className="h-4 w-4 text-green-600" />
            <span>Médias gerais: <span className="text-blue-600">Processo {avgProcesso.toFixed(2)}%</span> | <span className="text-purple-600">Produto {avgProduto.toFixed(2)}%</span></span>
          </div>
          <div className="text-gray-500 leading-none">
            Análise comparativa de {data.length} tipos de auditoria
          </div>
        </CardFooter>
      )}
    </Card>
  );
}

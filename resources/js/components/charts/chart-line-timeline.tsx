import { TrendingUp } from "lucide-react";
import { Line, LineChart, CartesianGrid, XAxis, YAxis, Tooltip, ResponsiveContainer } from "recharts";

import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";

import type { TimelineAuditoria } from "@/types/dashboard";

interface ChartLineTimelineProps {
  data: TimelineAuditoria[];
  title?: string;
  description?: string;
  showFooter?: boolean;
}

const CustomTooltip = ({ active, payload }: any) => {
  if (active && payload && payload.length) {
    const formatDate = (dateStr: string) => {
      const [year, month] = dateStr.split('-');
      const date = new Date(parseInt(year), parseInt(month) - 1);
      return date.toLocaleDateString('pt-BR', { month: 'long', year: 'numeric' });
    };

    return (
      <div className="bg-white border border-gray-200 rounded-lg shadow-xl p-3 max-w-xs">
        <p className="font-semibold text-gray-900 mb-1 capitalize">{formatDate(payload[0].payload.mes)}</p>
        <p className="text-sm text-gray-600">
          Auditorias: <span className="font-bold text-blue-600">{payload[0].value}</span>
        </p>
      </div>
    );
  }
  return null;
};

export function ChartLineTimeline({
  data,
  title = "Evolução Temporal de Auditorias",
  description = "Quantidade de auditorias realizadas ao longo do tempo",
  showFooter = true,
}: ChartLineTimelineProps) {
  const formatMonth = (dateStr: string) => {
    const [year, month] = dateStr.split('-');
    const date = new Date(parseInt(year), parseInt(month) - 1);
    return date.toLocaleDateString('pt-BR', { month: 'short', year: '2-digit' });
  };

  const totalCount = data.reduce((sum, item) => sum + item.quantidade, 0);
  const avgPerMonth = data.length > 0 ? (totalCount / data.length).toFixed(1) : 0;
  const maxMonth = data.length > 0 ? data.reduce((max, item) =>
    item.quantidade > max.quantidade ? item : max
  , data[0]) : null;

  const chartData = data.map(item => ({
    ...item,
    mesFormatado: formatMonth(item.mes)
  }));

  return (
    <Card className="w-full">
      <CardHeader>
        <CardTitle className="text-xl">{title}</CardTitle>
        <CardDescription>{description}</CardDescription>
      </CardHeader>
      <CardContent className="px-2 md:px-6">
        <div className="w-full h-[400px]">
          <ResponsiveContainer width="100%" height="100%">
            <LineChart
              data={chartData}
              margin={{ top: 20, right: 20, left: 0, bottom: 60 }}
            >
              <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#e5e7eb" />
              <XAxis
                dataKey="mesFormatado"
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
              <Tooltip content={<CustomTooltip />} />
              <Line
                type="monotone"
                dataKey="quantidade"
                stroke="#3b82f6"
                strokeWidth={3}
                dot={{ fill: '#3b82f6', r: 4 }}
                activeDot={{ r: 6, fill: '#2563eb' }}
              />
            </LineChart>
          </ResponsiveContainer>
        </div>
      </CardContent>
      {showFooter && (
        <CardFooter className="flex-col items-start gap-2 text-sm border-t pt-4">
          {maxMonth && (
            <div className="flex items-center gap-2 leading-none font-medium text-gray-700">
              <TrendingUp className="h-4 w-4 text-green-600" />
              <span>Pico em {formatMonth(maxMonth.mes)}: <span className="text-blue-600">{maxMonth.quantidade} auditorias</span></span>
            </div>
          )}
          <div className="text-gray-500 leading-none">
            Média de {avgPerMonth} auditorias por mês em {data.length} meses analisados
          </div>
        </CardFooter>
      )}
    </Card>
  );
}

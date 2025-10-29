import { TrendingUp } from "lucide-react"
import { Bar, BarChart, CartesianGrid, XAxis, YAxis, Tooltip, Cell, ResponsiveContainer } from "recharts"

import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"

interface ChartBarDefaultProps {
  data: Array<{
    name: string
    count: number
  }>
  title?: string
  description?: string
  showFooter?: boolean
  footerText?: string
}

// Cores frias variadas
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
]

// Componente de Tooltip customizado
const CustomTooltip = ({ active, payload }: any) => {
  if (active && payload && payload.length) {
    return (
      <div className="bg-white border border-gray-200 rounded-lg shadow-xl p-3 max-w-xs">
        <p className="font-semibold text-gray-900 mb-1 break-words">{payload[0].payload.name}</p>
        <p className="text-sm text-gray-600">
          Não Conformidades: <span className="font-bold text-blue-600">{payload[0].value}</span>
        </p>
      </div>
    )
  }
  return null
}

export function ChartBarDefault({
  data,
  title = "Não Conformidades por Projeto",
  description = "Total de não conformidades ativas agrupadas por projeto",
  showFooter = true,
  footerText,
}: ChartBarDefaultProps) {
  const totalCount = data.reduce((sum, item) => sum + item.count, 0)
  const maxProject = data.length > 0 ? data.reduce((max, item) => 
    item.count > max.count ? item : max
  , data[0]) : null

  return (
    <Card className="w-full">
      <CardHeader>
        <CardTitle className="text-xl">{title}</CardTitle>
        <CardDescription>{description}</CardDescription>
      </CardHeader>
      <CardContent className="px-2 md:px-6">
        <div className="w-full h-[500px]">
          <ResponsiveContainer width="100%" height="100%">
            <BarChart
              data={data}
              margin={{ top: 20, right: 20, left: 0, bottom: 120 }}
            >
              <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#e5e7eb" />
              <XAxis
                dataKey="name"
                tickLine={false}
                axisLine={{ stroke: '#d1d5db' }}
                angle={-45}
                textAnchor="end"
                height={120}
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
              <Bar dataKey="count" radius={[8, 8, 0, 0]} maxBarSize={80}>
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
          {maxProject && (
            <div className="flex items-center gap-2 leading-none font-medium text-gray-700">
              <TrendingUp className="h-4 w-4 text-green-600" />
              <span>Projeto com mais não conformidades: <span className="text-blue-600">{maxProject.name}</span> ({maxProject.count})</span>
            </div>
          )}
          <div className="text-gray-500 leading-none">
            {footerText || `Total de ${totalCount} não conformidades distribuídas em ${data.length} projetos`}
          </div>
        </CardFooter>
      )}
    </Card>
  )
}

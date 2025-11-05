import { AlertCircle } from "lucide-react";

import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";

import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";

import type { TopNaoConformidade } from "@/types/dashboard";

interface ChartTableTopNcProps {
  data: TopNaoConformidade[];
  title?: string;
  description?: string;
  showFooter?: boolean;
  maxItems?: number;
}

export function ChartTableTopNc({
  data,
  title = "Top Não Conformidades",
  description = "Não conformidades mais recorrentes",
  showFooter = true,
  maxItems = 10,
}: ChartTableTopNcProps) {
  const displayData = data.slice(0, maxItems);
  const totalOcorrencias = displayData.reduce((sum, item) => sum + item.ocorrencias, 0);

  return (
    <Card className="w-full">
      <CardHeader>
        <CardTitle className="text-xl">{title}</CardTitle>
        <CardDescription>{description}</CardDescription>
      </CardHeader>
      <CardContent className="px-2 md:px-6">
        <div className="w-full">
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead className="w-[100px]">Sigla</TableHead>
                <TableHead>Descrição</TableHead>
                <TableHead className="w-[150px]">Tipo</TableHead>
                <TableHead className="text-right w-[100px]">Ocorrências</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {displayData.length > 0 ? (
                displayData.map((nc, index) => (
                  <TableRow key={`${nc.sigla}-${index}`}>
                    <TableCell className="font-semibold text-blue-600">
                      {nc.sigla}
                    </TableCell>
                    <TableCell className="text-sm">
                      {nc.descricao}
                    </TableCell>
                    <TableCell>
                      <span className="inline-flex items-center rounded-md bg-purple-100 px-2 py-1 text-xs font-medium text-purple-700">
                        {nc.tipo_auditoria}
                      </span>
                    </TableCell>
                    <TableCell className="text-right">
                      <span className="inline-flex items-center justify-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                        {nc.ocorrencias}
                      </span>
                    </TableCell>
                  </TableRow>
                ))
              ) : (
                <TableRow>
                  <TableCell colSpan={4} className="text-center text-slate-500 py-8">
                    Nenhuma não conformidade encontrada
                  </TableCell>
                </TableRow>
              )}
            </TableBody>
          </Table>
        </div>
      </CardContent>
      {showFooter && displayData.length > 0 && (
        <CardFooter className="flex-col items-start gap-2 text-sm border-t pt-4">
          <div className="flex items-center gap-2 leading-none font-medium text-gray-700">
            <AlertCircle className="h-4 w-4 text-orange-600" />
            <span>Top {displayData.length} NCs representam <span className="text-blue-600">{totalOcorrencias} ocorrências</span></span>
          </div>
          <div className="text-gray-500 leading-none">
            Mostrando as {displayData.length} não conformidades mais recorrentes
          </div>
        </CardFooter>
      )}
    </Card>
  );
}

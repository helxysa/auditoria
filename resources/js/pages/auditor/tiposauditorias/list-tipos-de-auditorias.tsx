import { usePage } from '@inertiajs/react';
import { useState } from 'react';
import FormTipoDeAuditoria from './create-tipos-de-auditorias.tsx'
import { Button } from '@/components/ui/button'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Pagination } from '@/components/ui/pagination'

interface TipoAuditoria {
    id: string
    nome: string
}

interface PaginatedData {
    data: TipoAuditoria[]
    links: Array<{ url: string | null; label: string; active: boolean }>
    current_page: number
    last_page: number
    per_page: number
    total: number
}

export default function ListagemTiposDeAuditoria(){
    const { tipo_de_auditorias } = usePage().props as { tipo_de_auditorias: PaginatedData };
    const [modal, setModal] = useState(false);

    return (
        <div className="container mx-auto py-8 space-y-6">
            <Card>
                <CardHeader className="flex flex-row items-center justify-between">
                    <CardTitle>Tipos de Auditoria</CardTitle>
                    <Button onClick={() => setModal(!modal)}>
                        {modal ? 'Fechar' : 'Criar Tipo de Auditoria'}
                    </Button>
                </CardHeader>
            </Card>

            {modal && (
                <FormTipoDeAuditoria />
            )}

            <Card>
                <CardContent className="pt-6">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>ID</TableHead>
                                <TableHead>Nome</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {tipo_de_auditorias.data.length > 0 ? (
                                tipo_de_auditorias.data.map((tipo_de_auditoria) => (
                                    <TableRow key={tipo_de_auditoria.id}>
                                        <TableCell className="font-mono text-xs">{tipo_de_auditoria.id.substring(0, 8)}...</TableCell>
                                        <TableCell>{tipo_de_auditoria.nome}</TableCell>
                                    </TableRow>
                                ))
                            ) : (
                                <TableRow>
                                    <TableCell colSpan={2} className="text-center text-slate-500">
                                        Nenhum tipo de auditoria encontrado
                                    </TableCell>
                                </TableRow>
                            )}
                        </TableBody>
                    </Table>

                    {tipo_de_auditorias.links && tipo_de_auditorias.links.length > 3 && (
                        <div className="mt-4">
                            <Pagination links={tipo_de_auditorias.links} />
                        </div>
                    )}
                </CardContent>
            </Card>
        </div>
    )
}

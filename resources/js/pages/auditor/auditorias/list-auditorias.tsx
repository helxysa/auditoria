import { usePage } from '@inertiajs/react';
import { useState } from 'react';
import FormAuditoria from './create-auditorias.tsx'
import { Button } from '@/components/ui/button'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Pagination } from '@/components/ui/pagination'
import { DeleteConfirmDialog } from '@/components/ui/alert-dialog'
import { Dialog, DialogContent } from '@/components/ui/dialog'
import { Pencil, Trash2 } from 'lucide-react'

interface Auditoria {
    id: string
    tipo_auditorias_id: string
    quem_criou: string
    analista_responsavel: string
    processo: string
    produto: string
    tarefa_redmine: string
    nome_do_projeto: string
}

interface PaginatedData {
    data: Auditoria[]
    links: Array<{ url: string | null; label: string; active: boolean }>
    current_page: number
    last_page: number
    per_page: number
    total: number
}

export default function ListagemAuditorias(){
    const { auditorias } = usePage().props as { auditorias: PaginatedData };
    const [modal, setModal] = useState(false);
    const [editingAuditoria, setEditingAuditoria] = useState<Auditoria | null>(null);
    const [deletingId, setDeletingId] = useState<string | null>(null);

    const handleEdit = (auditoria: Auditoria) => {
        setEditingAuditoria(auditoria);
        setModal(false);
    };

    const handleCloseEdit = () => {
        setEditingAuditoria(null);
    };

    const handleCreateNew = () => {
        setEditingAuditoria(null);
        setModal(!modal);
    };

    return (
        <div className="container mx-auto py-8 space-y-6">
            <Card>
                <CardHeader className="flex flex-row items-center justify-between">
                    <CardTitle>Auditorias</CardTitle>
                    <Button onClick={handleCreateNew}>
                        {modal ? 'Fechar' : 'Criar Auditoria'}
                    </Button>
                </CardHeader>
            </Card>

            <Dialog open={modal} onOpenChange={(open) => !open && setModal(false)}>
                <DialogContent className="max-w-3xl">
                    <FormAuditoria onSuccess={() => setModal(false)} />
                </DialogContent>
            </Dialog>

            <Dialog open={!!editingAuditoria} onOpenChange={(open) => !open && handleCloseEdit()}>
                <DialogContent className="max-w-3xl">
                    <FormAuditoria 
                        auditoria={editingAuditoria || undefined} 
                        onSuccess={handleCloseEdit}
                    />
                </DialogContent>
            </Dialog>

            <DeleteConfirmDialog
                open={!!deletingId}
                onOpenChange={(open) => !open && setDeletingId(null)}
                resourceName="esta auditoria"
                deleteUrl={`/auditorias/${deletingId}`}
            />

            <Card>
                <CardContent className="pt-6">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>ID</TableHead>
                                <TableHead>Tipo Auditoria</TableHead>
                                <TableHead>Quem Criou</TableHead>
                                <TableHead>Analista Responsável</TableHead>
                                <TableHead>Processo</TableHead>
                                <TableHead>Produto</TableHead>
                                <TableHead>Tarefa Redmine</TableHead>
                                <TableHead>Nome do Projeto</TableHead>
                                <TableHead className="text-right">Ações</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {auditorias.data.length > 0 ? (
                                auditorias.data.map((auditoria) => (
                                    <TableRow key={auditoria.id}>
                                        <TableCell className="font-mono text-xs">{auditoria.id.substring(0, 8)}...</TableCell>
                                        <TableCell className="font-mono text-xs">{auditoria.tipo_auditorias_id.substring(0, 8)}...</TableCell>
                                        <TableCell>{auditoria.quem_criou}</TableCell>
                                        <TableCell>{auditoria.analista_responsavel}</TableCell>
                                        <TableCell>{auditoria.processo}</TableCell>
                                        <TableCell>{auditoria.produto}</TableCell>
                                        <TableCell>{auditoria.tarefa_redmine}</TableCell>
                                        <TableCell>{auditoria.nome_do_projeto}</TableCell>
                                        <TableCell>
                                            <div className="flex items-center gap-2">
                                                <button
                                                    type="button"
                                                    onClick={() => handleEdit(auditoria)}
                                                    className="inline-flex items-center justify-center rounded-md bg-blue-600 text-white hover:bg-blue-700 h-8 w-8 transition-colors"
                                                    title="Editar"
                                                >
                                                    <Pencil className="h-4 w-4" />
                                                </button>
                                                <button
                                                    type="button"
                                                    onClick={() => setDeletingId(auditoria.id)}
                                                    className="inline-flex items-center justify-center rounded-md bg-red-600 text-white hover:bg-red-700 h-8 w-8 transition-colors"
                                                    title="Excluir"
                                                >
                                                    <Trash2 className="h-4 w-4" />
                                                </button>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                ))
                            ) : (
                                <TableRow>
                                    <TableCell colSpan={9} className="text-center text-slate-500">
                                        Nenhuma auditoria encontrada
                                    </TableCell>
                                </TableRow>
                            )}
                        </TableBody>
                    </Table>

                    {auditorias.links && auditorias.links.length > 3 && (
                        <div className="mt-4">
                            <Pagination links={auditorias.links} />
                        </div>
                    )}
                </CardContent>
            </Card>
        </div>
    )
}

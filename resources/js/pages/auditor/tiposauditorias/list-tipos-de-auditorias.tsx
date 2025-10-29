import { usePage } from '@inertiajs/react';
import { useState } from 'react';
import FormTipoDeAuditoria from './create-tipos-de-auditorias.tsx'
import { Button } from '@/components/ui/button'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Pagination } from '@/components/ui/pagination'
import { DeleteConfirmDialog } from '@/components/ui/alert-dialog'
import { Dialog, DialogContent } from '@/components/ui/dialog'
import { Pencil, Trash2 } from 'lucide-react'

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
    const [editingTipoAuditoria, setEditingTipoAuditoria] = useState<TipoAuditoria | null>(null);
    const [deletingId, setDeletingId] = useState<string | null>(null);

    const handleEdit = (tipoAuditoria: TipoAuditoria) => {
        setEditingTipoAuditoria(tipoAuditoria);
        setModal(false);
    };

    const handleCloseEdit = () => {
        setEditingTipoAuditoria(null);
    };

    const handleCreateNew = () => {
        setEditingTipoAuditoria(null);
        setModal(!modal);
    };

    return (
        <div className="container mx-auto py-8 space-y-6">
            <Card>
                <CardHeader className="flex flex-row items-center justify-between">
                    <CardTitle>Tipos de Auditoria</CardTitle>
                    <Button onClick={handleCreateNew}>
                        {modal ? 'Fechar' : 'Criar Tipo de Auditoria'}
                    </Button>
                </CardHeader>
            </Card>

            <Dialog open={modal} onOpenChange={(open) => !open && setModal(false)}>
                <DialogContent className="max-w-3xl">
                    <FormTipoDeAuditoria onSuccess={() => setModal(false)} />
                </DialogContent>
            </Dialog>

            <Dialog open={!!editingTipoAuditoria} onOpenChange={(open) => !open && handleCloseEdit()}>
                <DialogContent className="max-w-3xl">
                    <FormTipoDeAuditoria 
                        tipoAuditoria={editingTipoAuditoria || undefined} 
                        onSuccess={handleCloseEdit}
                    />
                </DialogContent>
            </Dialog>

            <DeleteConfirmDialog
                open={!!deletingId}
                onOpenChange={(open) => !open && setDeletingId(null)}
                resourceName="este tipo de auditoria"
                deleteUrl={`/tipo-auditorias/${deletingId}`}
            />

            <Card>
                <CardContent className="pt-6">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>ID</TableHead>
                                <TableHead>Nome</TableHead>
                                <TableHead className="text-right">Ações</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {tipo_de_auditorias.data.length > 0 ? (
                                tipo_de_auditorias.data.map((tipo_de_auditoria) => (
                                    <TableRow key={tipo_de_auditoria.id}>
                                        <TableCell className="font-mono text-xs">{tipo_de_auditoria.id.substring(0, 8)}...</TableCell>
                                        <TableCell>{tipo_de_auditoria.nome}</TableCell>
                                        <TableCell>
                                            <div className="flex items-center justify-end gap-2">
                                                <button
                                                    type="button"
                                                    onClick={() => handleEdit(tipo_de_auditoria)}
                                                    className="inline-flex items-center justify-center rounded-md bg-blue-600 text-white hover:bg-blue-700 h-8 w-8 transition-colors"
                                                    title="Editar"
                                                >
                                                    <Pencil className="h-4 w-4" />
                                                </button>
                                                <button
                                                    type="button"
                                                    onClick={() => setDeletingId(tipo_de_auditoria.id)}
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
                                    <TableCell colSpan={3} className="text-center text-slate-500">
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

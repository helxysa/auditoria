import { usePage } from '@inertiajs/react';
import { useState } from 'react';
import FormNaoConformidade from './create-naoconformidade'
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

interface NaoConformidade {
    id: string
    sigla: string
    descricao: string | null
    auditorias?: Auditoria[]
}

interface PaginatedData {
    data: NaoConformidade[]
    links: Array<{ url: string | null; label: string; active: boolean }>
    current_page: number
    last_page: number
    per_page: number
    total: number
}

export default function ListagemNaoConformidades(){
    const { nao_conformidades } = usePage().props as { nao_conformidades: PaginatedData };
    const [modal, setModal] = useState(false);
    const [editingNaoConformidade, setEditingNaoConformidade] = useState<NaoConformidade | null>(null);
    const [deletingId, setDeletingId] = useState<string | null>(null);

    const handleEdit = (naoConformidade: NaoConformidade) => {
        setEditingNaoConformidade(naoConformidade);
        setModal(false);
    };

    const handleCloseEdit = () => {
        setEditingNaoConformidade(null);
    };

    const handleCreateNew = () => {
        setEditingNaoConformidade(null);
        setModal(!modal);
    };

    return (
        <div className="container mx-auto py-8 space-y-6">
            <Card>
                <CardHeader className="flex flex-row items-center justify-between">
                    <CardTitle>Não Conformidades</CardTitle>
                    <Button onClick={handleCreateNew}>
                        {modal ? 'Fechar' : 'Criar Não Conformidade'}
                    </Button>
                </CardHeader>
            </Card>

            <Dialog open={modal} onOpenChange={(open) => !open && setModal(false)}>
                <DialogContent className="max-w-3xl">
                    <FormNaoConformidade onSuccess={() => setModal(false)} />
                </DialogContent>
            </Dialog>

            <Dialog open={!!editingNaoConformidade} onOpenChange={(open) => !open && handleCloseEdit()}>
                <DialogContent className="max-w-3xl">
                    <FormNaoConformidade
                        naoConformidade={editingNaoConformidade || undefined}
                        onSuccess={handleCloseEdit}
                    />
                </DialogContent>
            </Dialog>

            <DeleteConfirmDialog
                open={!!deletingId}
                onOpenChange={(open) => !open && setDeletingId(null)}
                resourceName="esta não conformidade"
                deleteUrl={`/nao-conformidades/${deletingId}`}
            />

            <Card>
                <CardContent className="pt-6">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>ID</TableHead>
                                <TableHead>Sigla</TableHead>
                                <TableHead>Descrição</TableHead>
                                <TableHead>Auditorias Vinculadas</TableHead>
                                <TableHead className="text-right">Ações</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {nao_conformidades.data.length > 0 ? (
                                nao_conformidades.data.map((naoConformidade) => (
                                    <TableRow key={naoConformidade.id}>
                                        <TableCell className="font-mono text-xs">{naoConformidade.id.substring(0, 8)}...</TableCell>
                                        <TableCell className="font-semibold">{naoConformidade.sigla}</TableCell>
                                        <TableCell>{naoConformidade.descricao || '-'}</TableCell>
                                        <TableCell>
                                            <span className="inline-flex items-center justify-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                                {naoConformidade.auditorias?.length || 0}
                                            </span>
                                        </TableCell>
                                        <TableCell>
                                            <div className="flex items-center gap-2 justify-end">
                                                <button
                                                    type="button"
                                                    onClick={() => handleEdit(naoConformidade)}
                                                    className="inline-flex items-center justify-center rounded-md bg-blue-600 text-white hover:bg-blue-700 h-8 w-8 transition-colors"
                                                    title="Editar"
                                                >
                                                    <Pencil className="h-4 w-4" />
                                                </button>
                                                <button
                                                    type="button"
                                                    onClick={() => setDeletingId(naoConformidade.id)}
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
                                    <TableCell colSpan={5} className="text-center text-slate-500">
                                        Nenhuma não conformidade encontrada
                                    </TableCell>
                                </TableRow>
                            )}
                        </TableBody>
                    </Table>

                    {nao_conformidades.links && nao_conformidades.links.length > 3 && (
                        <div className="mt-4">
                            <Pagination links={nao_conformidades.links} />
                        </div>
                    )}
                </CardContent>
            </Card>
        </div>
    )
}

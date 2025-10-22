import { router } from '@inertiajs/react'
import { Button } from './button'
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from './dialog'

interface AlertDialogProps {
    open: boolean
    onOpenChange: (open: boolean) => void
    title: string
    description: string
    onConfirm: () => void
    confirmText?: string
    cancelText?: string
    variant?: 'danger' | 'default'
}

export function AlertDialog({
    open,
    onOpenChange,
    title,
    description,
    onConfirm,
    confirmText = 'Confirmar',
    cancelText = 'Cancelar',
    variant = 'default'
}: AlertDialogProps) {
    return (
        <Dialog open={open} onOpenChange={onOpenChange}>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>{title}</DialogTitle>
                    <DialogDescription>{description}</DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button
                        onClick={() => onOpenChange(false)}
                        className="bg-slate-200 text-slate-900 hover:bg-slate-300"
                    >
                        {cancelText}
                    </Button>
                    <Button
                        onClick={() => {
                            onConfirm()
                            onOpenChange(false)
                        }}
                        className={
                            variant === 'danger' 
                                ? 'bg-red-600 hover:bg-red-700' 
                                : ''
                        }
                    >
                        {confirmText}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    )
}

interface DeleteConfirmDialogProps {
    open: boolean
    onOpenChange: (open: boolean) => void
    resourceName: string
    deleteUrl: string
}

export function DeleteConfirmDialog({
    open,
    onOpenChange,
    resourceName,
    deleteUrl
}: DeleteConfirmDialogProps) {
    const handleDelete = () => {
        router.delete(deleteUrl, {
            preserveScroll: true,
        })
    }

    return (
        <AlertDialog
            open={open}
            onOpenChange={onOpenChange}
            title="Confirmar Exclusão"
            description={`Tem certeza que deseja excluir ${resourceName}? Esta ação não pode ser desfeita.`}
            onConfirm={handleDelete}
            confirmText="Excluir"
            cancelText="Cancelar"
            variant="danger"
        />
    )
}

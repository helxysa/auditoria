import { Button } from './button'
import { X } from 'lucide-react'

interface DialogProps {
    open: boolean
    onOpenChange: (open: boolean) => void
    children: React.ReactNode
}

interface DialogContentProps {
    children: React.ReactNode
    className?: string
    showClose?: boolean
}

interface DialogHeaderProps {
    children: React.ReactNode
}

interface DialogTitleProps {
    children: React.ReactNode
}

interface DialogDescriptionProps {
    children: React.ReactNode
}

interface DialogFooterProps {
    children: React.ReactNode
}

export function Dialog({ open, onOpenChange, children }: DialogProps) {
    if (!open) return null

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center">
            <div 
                className="fixed inset-0 bg-black/50 backdrop-blur-sm" 
                onClick={() => onOpenChange(false)}
            />
            {children}
        </div>
    )
}

export function DialogContent({ children, className = '', showClose = true }: DialogContentProps) {
    return (
        <div className={`relative z-50 bg-white rounded-lg shadow-xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto ${className}`}>
            {children}
        </div>
    )
}

export function DialogHeader({ children }: DialogHeaderProps) {
    return (
        <div className="px-6 pt-6 pb-4">
            {children}
        </div>
    )
}

export function DialogTitle({ children }: DialogTitleProps) {
    return (
        <h2 className="text-lg font-semibold text-slate-900">
            {children}
        </h2>
    )
}

export function DialogDescription({ children }: DialogDescriptionProps) {
    return (
        <p className="text-sm text-slate-500 mt-2">
            {children}
        </p>
    )
}

export function DialogFooter({ children }: DialogFooterProps) {
    return (
        <div className="px-6 py-4 flex justify-end gap-2 border-t border-slate-200">
            {children}
        </div>
    )
}

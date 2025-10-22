import { Link } from '@inertiajs/react'
import { cn } from '@/lib/utils'

interface PaginationLink {
    url: string | null
    label: string
    active: boolean
}

interface PaginationProps {
    links: PaginationLink[]
    className?: string
}

export function Pagination({ links, className }: PaginationProps) {
    return (
        <div className={cn("flex items-center justify-center space-x-1", className)}>
            {links.map((link, index) => {
                const label = link.label
                    .replace('Próximo', '→')
                    .replace('Próximo', '→')

                if (!link.url) {
                    return (
                        <span
                            key={index}
                            className="px-3 py-2 text-sm text-slate-400 cursor-not-allowed"
                        >
                            {label}
                        </span>
                    )
                }

                return (
                    <Link
                        key={index}
                        href={link.url}
                        className={cn(
                            "px-3 py-2 text-sm rounded-md transition-colors",
                            link.active
                                ? "bg-slate-900 text-white font-medium"
                                : "text-slate-700 hover:bg-slate-100"
                        )}
                        preserveScroll
                    >
                        {label}
                    </Link>
                )
            })}
        </div>
    )
}

import { Link, usePage } from '@inertiajs/react'
import { cn } from '@/lib/utils'
import {
    FileCheck,
    ClipboardList,
    AlertCircle,
    Home,
    ChevronRight,
    Menu,
    X
} from 'lucide-react'
import { useState } from 'react'

interface SidebarItem {
    name: string
    href: string
    icon: React.ComponentType<{ className?: string }>
    label: string
}

const navigation: SidebarItem[] = [
    {
        name: 'home',
        href: '/',
        icon: Home,
        label: 'Início'
    },
    {
        name: 'auditorias',
        href: '/auditorias',
        icon: FileCheck,
        label: 'Auditorias'
    },
    {
        name: 'redmine',
        href: '/redmine',
        icon: FileCheck,
        label: 'Redmine'

    },
    {
        name: 'tipos-auditorias',
        href: '/tipo-auditorias',
        icon: ClipboardList,
        label: 'Tipos de Auditorias'
    },
    {
        name: 'nao-conformidades',
        href: '/nao-conformidades',
        icon: AlertCircle,
        label: 'Não Conformidades'
    },
]

interface SidebarProps {
    children: React.ReactNode
}

export default function Sidebar({ children }: SidebarProps) {
    const { url } = usePage()
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false)

    const isActive = (href: string) => {
        if (href === '/') {
            return url === '/'
        }
        return url.startsWith(href)
    }

    return (
        <div className="min-h-screen bg-slate-50">
            <div className="lg:hidden fixed top-0 left-0 right-0 z-50 bg-white border-b border-slate-200">
                <div className="flex items-center justify-between px-4 py-3">
                    <h1 className="text-lg font-semibold text-slate-900">Auditor</h1>
                    <button
                        onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
                        className="p-2 rounded-md text-slate-600 hover:bg-slate-100 transition-colors"
                    >
                        {isMobileMenuOpen ? (
                            <X className="h-6 w-6" />
                        ) : (
                            <Menu className="h-6 w-6" />
                        )}
                    </button>
                </div>
            </div>

            <aside
                className={cn(
                    "fixed top-0 left-0 z-40 h-screen w-64 bg-white border-r border-slate-200 transition-transform duration-200 ease-in-out",
                    "lg:translate-x-0",
                    isMobileMenuOpen ? "translate-x-0" : "-translate-x-full"
                )}
            >
                <div className="flex flex-col h-full">
                    <div className="flex items-center justify-between h-16 px-6 border-b border-slate-200">
                        <h1 className="text-xl font-bold text-slate-900">Auditor</h1>
                    </div>

                    <nav className="flex-1 overflow-y-auto py-4 px-3">
                        <ul className="space-y-1">
                            {navigation.map((item) => {
                                const Icon = item.icon
                                const active = isActive(item.href)

                                return (
                                    <li key={item.name}>
                                        <Link
                                            href={item.href}
                                            className={cn(
                                                "group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors",
                                                active
                                                    ? "bg-slate-900 text-white"
                                                    : "text-slate-700 hover:bg-slate-100 hover:text-slate-900"
                                            )}
                                            onClick={() => setIsMobileMenuOpen(false)}
                                        >
                                            <Icon className={cn(
                                                "h-5 w-5 shrink-0 transition-colors",
                                                active ? "text-white" : "text-slate-500 group-hover:text-slate-700"
                                            )} />
                                            <span className="flex-1">{item.label}</span>
                                            {active && (
                                                <ChevronRight className="h-4 w-4" />
                                            )}
                                        </Link>
                                    </li>
                                )
                            })}
                        </ul>
                    </nav>

                    <div className="border-t border-slate-200 px-6 py-4">
                        <div className="flex items-center gap-3">
                            <div className="flex h-9 w-9 items-center justify-center rounded-full bg-slate-200 text-sm font-medium text-slate-700">
                                U
                            </div>
                            <div className="flex-1 min-w-0">
                                <p className="text-sm font-medium text-slate-900 truncate">
                                    Usuário
                                </p>
                                <p className="text-xs text-slate-500 truncate">
                                    usuario@example.com
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            {isMobileMenuOpen && (
                <div
                    className="fixed inset-0 z-30 bg-slate-900/50 lg:hidden"
                    onClick={() => setIsMobileMenuOpen(false)}
                />
            )}

            <main className={cn(
                "transition-all duration-200 ease-in-out",
                "lg:ml-64 pt-16 lg:pt-0"
            )}>
                {children}
            </main>
        </div>
    )
}

import { useEffect, useState } from 'react'
import { router, usePage } from '@inertiajs/react'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { ChartBarDefault } from '@/components/charts/chart-bar-default'

interface IssueStats {
    name: string
    count: number
}

interface PageProps {
    projectsCount?: number
    issuesStats?: IssueStats[]
}

export default function Redmine() {
    const { projectsCount, issuesStats } = usePage<PageProps>().props

    const [loadingProjects, setLoadingProjects] = useState(projectsCount === undefined)
    const [loadingIssues, setLoadingIssues] = useState(issuesStats === undefined)

    useEffect(() => {
        let cancelled = false

        // Carrega contagem de projetos
        if (projectsCount === undefined && !cancelled) {
            router.reload({
                only: ['projectsCount'],
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => !cancelled && setLoadingProjects(false),
                onError: () => !cancelled && setLoadingProjects(false),
            })
        }

        // Carrega estatísticas de issues
        if (issuesStats === undefined && !cancelled) {
            router.reload({
                only: ['issuesStats'],
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => !cancelled && setLoadingIssues(false),
                onError: () => !cancelled && setLoadingIssues(false),
            })
        }

        // Cleanup: cancela requisições quando sair da página
        return () => {
            cancelled = true
        }
    }, [])

    const totalIssues = issuesStats?.reduce((sum, item) => sum + item.count, 0) ?? 0

    return (
        <div className="min-h-screen bg-gray-50 p-4 md:p-6 lg:p-8">
            <div className="w-full mx-auto space-y-6">
                <h1 className="text-2xl md:text-3xl font-bold text-gray-900">
                    Dashboard Redmine
                </h1>

                {/* Cards de Estatísticas */}
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                    {/* Card de Projetos */}
                    <Card className="hover:shadow-lg transition-shadow">
                        <CardHeader className="pb-3">
                            <CardTitle className="text-lg">Total de Projetos</CardTitle>
                        </CardHeader>
                        <CardContent>
                            {loadingProjects ? (
                                <div className="space-y-2">
                                    <div className="h-10 bg-gray-200 rounded animate-pulse"></div>
                                    <div className="h-3 bg-gray-200 rounded w-3/4 animate-pulse"></div>
                                </div>
                            ) : (
                                <div className="space-y-1">
                                    <p className="text-3xl md:text-4xl font-bold text-blue-600">{projectsCount ?? 0}</p>
                                    <p className="text-xs md:text-sm text-gray-500">projetos ativos principais</p>
                                </div>
                            )}
                        </CardContent>
                    </Card>

                    {/* Card de Não Conformidades */}
                    <Card className="hover:shadow-lg transition-shadow">
                        <CardHeader className="pb-3">
                            <CardTitle className="text-lg">Total de Não Conformidades</CardTitle>
                        </CardHeader>
                        <CardContent>
                            {loadingIssues ? (
                                <div className="space-y-2">
                                    <div className="h-10 bg-gray-200 rounded animate-pulse"></div>
                                    <div className="h-3 bg-gray-200 rounded w-3/4 animate-pulse"></div>
                                </div>
                            ) : (
                                <div className="space-y-1">
                                    <p className="text-3xl md:text-4xl font-bold text-purple-600">{totalIssues}</p>
                                    <p className="text-xs md:text-sm text-gray-500">não conformidades ativas</p>
                                </div>
                            )}
                        </CardContent>
                    </Card>
                </div>

                {/* Gráfico de Não Conformidades por Projeto */}
                <div className="w-full">
                    {loadingIssues ? (
                        <Card>
                            <CardHeader>
                                <div className="space-y-2">
                                    <div className="h-6 bg-gray-200 rounded w-1/3 animate-pulse"></div>
                                    <div className="h-4 bg-gray-200 rounded w-1/2 animate-pulse"></div>
                                </div>
                            </CardHeader>
                            <CardContent>
                                <div className="h-96 bg-gray-200 rounded animate-pulse"></div>
                            </CardContent>
                        </Card>
                    ) : issuesStats && issuesStats.length > 0 ? (
                        <ChartBarDefault data={issuesStats} />
                    ) : (
                        <Card>
                            <CardContent className="pt-6">
                                <p className="text-gray-500 text-center py-8">
                                    Nenhuma não conformidade encontrada
                                </p>
                            </CardContent>
                        </Card>
                    )}
                </div>
            </div>
        </div>
    )
}

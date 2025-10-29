import { usePage } from '@inertiajs/react'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { AutoForm, AutoFormFieldConfig } from '@/components/ui/auto-form'

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

interface FormAuditoriaProps {
    auditoria?: Auditoria
    onSuccess?: () => void
}

export default function FormAuditoria({ auditoria, onSuccess }: FormAuditoriaProps) {
    const { tipos_auditorias = [] } = usePage().props as { tipos_auditorias?: any[] };
    const isEditing = !!auditoria

    const fields: AutoFormFieldConfig[] = [
        {
            name: 'tipo_auditorias_id',
            label: 'Tipo de Auditoria',
            type: 'select',
            required: true,
            options: tipos_auditorias.map((tipo: any) => ({
                value: tipo.id,
                label: tipo.nome
            }))
        },
        {
            name: 'quem_criou',
            label: 'Quem Criou',
            type: 'text',
            required: true,
        },
        {
            name: 'analista_responsavel',
            label: 'Analista Responsável',
            type: 'text',
            required: true,
        },
        {
            name: 'processo',
            label: 'Processo',
            type: 'text',
            required: true,
        },
        {
            name: 'produto',
            label: 'Produto',
            type: 'text',
            required: true,
        },
        {
            name: 'tarefa_redmine',
            label: 'Tarefa Redmine',
            type: 'text',
            required: true,
        },
        {
            name: 'nome_do_projeto',
            label: 'Nome do Projeto',
            type: 'text',
            required: true,
        },
    ]

    const initialData = isEditing ? {
        tipo_auditorias_id: auditoria.tipo_auditorias_id,
        quem_criou: auditoria.quem_criou,
        analista_responsavel: auditoria.analista_responsavel,
        processo: auditoria.processo,
        produto: auditoria.produto,
        tarefa_redmine: auditoria.tarefa_redmine,
        nome_do_projeto: auditoria.nome_do_projeto
    } : {
        tipo_auditorias_id: '',
        quem_criou: '',
        analista_responsavel: '',
        processo: '',
        produto: '',
        tarefa_redmine: '',
        nome_do_projeto: ''
    }

    return (
        <div className="w-full p-4 md:p-6 lg:p-8">
            <Card className="w-full max-w-5xl mx-auto shadow-lg">
                <CardHeader className="border-b bg-gradient-to-r from-blue-50 to-indigo-50">
                    <CardTitle className="text-xl md:text-2xl">
                        {isEditing ? 'Editar Auditoria' : 'Criar Nova Auditoria'}
                    </CardTitle>
                </CardHeader>
                <CardContent className="p-4 md:p-6 lg:p-8">
                    <AutoForm
                        fields={fields}
                        initialData={initialData}
                        onSubmit={isEditing ? `/auditorias/${auditoria.id}` : '/auditorias'}
                        method={isEditing ? 'put' : 'post'}
                        submitText={isEditing ? 'Atualizar' : 'Criar'}
                        onSuccess={onSuccess}
                    />
                </CardContent>
            </Card>
        </div>
    )
}

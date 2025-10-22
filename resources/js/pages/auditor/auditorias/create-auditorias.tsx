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

    const initialData = auditoria || {
        tipo_auditorias_id: '',
        quem_criou: '',
        analista_responsavel: '',
        processo: '',
        produto: '',
        tarefa_redmine: '',
        nome_do_projeto: ''
    }

    return (
        <Card className="w-full max-w-2xl mx-auto">
            <CardHeader>
                <CardTitle>{isEditing ? 'Editar Auditoria' : 'Criar Nova Auditoria'}</CardTitle>
            </CardHeader>
            <CardContent>
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
    )
}

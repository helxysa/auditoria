import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { AutoForm, AutoFormFieldConfig } from '@/components/ui/auto-form'

interface TipoAuditoria {
    id: string
    nome: string
}

interface NaoConformidade {
    id: string
    sigla: string
    descricao: string | null
    tipo_auditoria_id?: string | null
}

interface FormNaoConformidadeProps {
    naoConformidade?: NaoConformidade
    tiposAuditoria: TipoAuditoria[]
    onSuccess?: () => void
}

export default function FormNaoConformidade({ naoConformidade, tiposAuditoria, onSuccess }: FormNaoConformidadeProps) {
    const isEditing = !!naoConformidade

    const fields: AutoFormFieldConfig[] = [
        {
            name: 'sigla',
            label: 'Sigla',
            type: 'text',
            required: true,
            placeholder: 'Ex: NC-001',
        },
        {
            name: 'descricao',
            label: 'Descrição',
            type: 'textarea',
            required: false,
            placeholder: 'Descreva a não conformidade...',
        },
        {
            name: 'tipo_auditoria_id',
            label: 'Tipo de Auditoria',
            type: 'select',
            required: false,
            options: tiposAuditoria.map(tipo => ({
                value: tipo.id,
                label: tipo.nome
            })),
            placeholder: 'Selecione o tipo de auditoria...',
        },
    ]

    const initialData = isEditing ? {
        sigla: naoConformidade.sigla,
        descricao: naoConformidade.descricao || '',
        tipo_auditoria_id: naoConformidade.tipo_auditoria_id || ''
    } : {
        sigla: '',
        descricao: '',
        tipo_auditoria_id: ''
    }

    return (
        <div className="w-full p-4 md:p-6 lg:p-8">
            <Card className="w-full max-w-4xl mx-auto shadow-lg">
                <CardHeader className="border-b bg-gradient-to-r from-blue-50 to-indigo-50">
                    <CardTitle className="text-xl md:text-2xl">
                        {isEditing ? 'Editar Não Conformidade' : 'Criar Nova Não Conformidade'}
                    </CardTitle>
                </CardHeader>
                <CardContent className="p-4 md:p-6 lg:p-8">
                    <AutoForm
                        fields={fields}
                        initialData={initialData}
                        onSubmit={isEditing ? `/nao-conformidades/${naoConformidade.id}` : '/nao-conformidades'}
                        method={isEditing ? 'put' : 'post'}
                        submitText={isEditing ? 'Atualizar' : 'Criar'}
                        onSuccess={onSuccess}
                    />
                </CardContent>
            </Card>
        </div>
    )
}

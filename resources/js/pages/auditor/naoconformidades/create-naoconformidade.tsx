import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { AutoForm, AutoFormFieldConfig } from '@/components/ui/auto-form'

interface NaoConformidade {
    id: string
    sigla: string
    descricao: string | null
}

interface FormNaoConformidadeProps {
    naoConformidade?: NaoConformidade
    onSuccess?: () => void
}

export default function FormNaoConformidade({ naoConformidade, onSuccess }: FormNaoConformidadeProps) {
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
    ]

    const initialData = isEditing ? {
        sigla: naoConformidade.sigla,
        descricao: naoConformidade.descricao || ''
    } : {
        sigla: '',
        descricao: ''
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

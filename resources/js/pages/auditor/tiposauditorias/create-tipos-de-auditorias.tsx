import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { AutoForm, AutoFormFieldConfig } from '@/components/ui/auto-form'

interface TipoAuditoria {
    id: string
    nome: string
}

interface FormTipoDeAuditoriaProps {
    tipoAuditoria?: TipoAuditoria
    onSuccess?: () => void
}

export default function FormTipoDeAuditoria({ tipoAuditoria, onSuccess }: FormTipoDeAuditoriaProps) {
    const isEditing = !!tipoAuditoria

    const fields: AutoFormFieldConfig[] = [
        {
            name: 'nome',
            label: 'Nome',
            type: 'text',
            required: true,
        },
    ]

    const initialData = isEditing ? {
        nome: tipoAuditoria.nome
    } : {
        nome: ''
    }

    return (
        <div className="w-full p-4 md:p-6 lg:p-8">
            <Card className="w-full max-w-4xl mx-auto shadow-lg">
                <CardHeader className="border-b bg-gradient-to-r from-blue-50 to-indigo-50">
                    <CardTitle className="text-xl md:text-2xl">
                        {isEditing ? 'Editar Tipo de Auditoria' : 'Criar Novo Tipo de Auditoria'}
                    </CardTitle>
                </CardHeader>
                <CardContent className="p-4 md:p-6 lg:p-8">
                    <AutoForm
                        fields={fields}
                        initialData={initialData}
                        onSubmit={isEditing ? `/tipo-auditorias/${tipoAuditoria.id}` : '/tipo-auditorias'}
                        method={isEditing ? 'put' : 'post'}
                        submitText={isEditing ? 'Atualizar' : 'Criar'}
                        onSuccess={onSuccess}
                    />
                </CardContent>
            </Card>
        </div>
    )
}

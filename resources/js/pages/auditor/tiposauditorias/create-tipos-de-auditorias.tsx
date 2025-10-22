import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { AutoForm, AutoFormFieldConfig } from '@/components/ui/auto-form'

export default function FormTipoDeAuditoria() {
    const fields: AutoFormFieldConfig[] = [
        {
            name: 'nome',
            label: 'Nome',
            type: 'text',
            required: true,
        },
    ]

    const initialData = {
        nome: ''
    }

    return (
        <Card className="w-full max-w-2xl mx-auto">
            <CardHeader>
                <CardTitle>Criar Novo Tipo de Auditoria</CardTitle>
            </CardHeader>
            <CardContent>
                <AutoForm
                    fields={fields}
                    initialData={initialData}
                    onSubmit="/tipo-auditorias"
                    submitText="Criar"
                />
            </CardContent>
        </Card>
    )
}

import * as React from "react"
import { useForm, usePage } from "@inertiajs/react"
import { cn } from "@/lib/utils"
import { Input } from "./input"
import { Select } from "./select"
import { Label } from "./label"
import { Button } from "./button"

export interface AutoFormFieldConfig {
    name: string
    label: string
    type?: 'text' | 'email' | 'password' | 'number' | 'select' | 'textarea'
    required?: boolean
    placeholder?: string
    options?: Array<{ value: string; label: string }>
}

export interface AutoFormProps {
    fields: AutoFormFieldConfig[]
    initialData: Record<string, any>
    onSubmit: string
    method?: 'post' | 'put' | 'patch'
    submitText?: string
    className?: string
    onSuccess?: () => void
}

const AutoFormField = ({
    field,
    value,
    onChange,
    error,
}: {
    field: AutoFormFieldConfig
    value: any
    onChange: (value: any) => void
    error?: string
}) => {
    const inputClasses = cn(
        error && "border-red-500 focus-visible:ring-red-500"
    )

    return (
        <div className="space-y-2">
            <Label htmlFor={field.name}>
                {field.label}
                {field.required && <span className="text-red-500 ml-1">*</span>}
            </Label>

            {field.type === 'select' ? (
                <Select
                    id={field.name}
                    value={value || ''}
                    onChange={(e) => onChange(e.target.value)}
                    required={field.required}
                    className={inputClasses}
                >
                    <option value="">Selecione</option>
                    {field.options?.map((option) => (
                        <option key={option.value} value={option.value}>
                            {option.label}
                        </option>
                    ))}
                </Select>
            ) : field.type === 'textarea' ? (
                <textarea
                    id={field.name}
                    value={value || ''}
                    onChange={(e) => onChange(e.target.value)}
                    required={field.required}
                    placeholder={field.placeholder}
                    className={cn(
                        "flex min-h-[80px] w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm placeholder:text-slate-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-950 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50",
                        inputClasses
                    )}
                />
            ) : (
                <Input
                    id={field.name}
                    type={field.type || 'text'}
                    value={value || ''}
                    onChange={(e) => onChange(e.target.value)}
                    required={field.required}
                    placeholder={field.placeholder}
                    className={inputClasses}
                />
            )}

            {error && (
                <p className="text-sm font-medium text-red-500">
                    {error}
                </p>
            )}
        </div>
    )
}

export const AutoForm = ({
    fields,
    initialData,
    onSubmit,
    method = 'post',
    submitText = "Enviar",
    className,
    onSuccess,
}: AutoFormProps) => {
    const pageProps = usePage().props as any
    const { data, setData, post, put, patch, processing, errors: inertiaErrors, reset } = useForm(initialData)

    // pega os erros do Inertia (vindos do backend)
    const errors = (pageProps.errors || inertiaErrors || {}) as Record<string, string>

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault()

        const submitMethod = method === 'put' ? put : method === 'patch' ? patch : post

        submitMethod(onSubmit, {
            preserveScroll: true,
            onSuccess: () => {
                if (onSuccess) {
                    onSuccess()
                }
                // limpa todos os campos do formulário retornando aos valores iniciais
                if (method === 'post') {
                    reset()
                }
            },
        })
    }

    return (
        <form onSubmit={handleSubmit} className={cn("space-y-4", className)}>
            {fields.map((field) => (
                <AutoFormField
                    key={field.name}
                    field={field}
                    value={data[field.name]}
                    onChange={(value) => setData(field.name, value)}
                    error={errors[field.name]}
                />
            ))}

            <Button type="submit" disabled={processing} className="w-full">
                {processing ? 'Processando...' : submitText}
            </Button>
        </form>
    )
}

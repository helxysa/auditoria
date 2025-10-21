import { useForm } from '@inertiajs/react'

export default function FormTipoDeAuditoria() {
    const { data, setData, post, processing, errors } = useForm({
        nome: ''
    })

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/auditorias');
    }

    return (
        <div>

            <h2>Criar Novo Tipo de Auditoria</h2>
            <form onSubmit={submit}>
                <div>
                    <label htmlFor="nome">Nome</label>
                    <input
                        id='nome'
                        value={data.nome}
                        onChange={(e) => setData('nome', e.target.value)}
                        required
                    />
                    {errors.nome && <span style={{color: 'red'}}>{errors.nome}</span>}
                </div>
                <button type="submit" disabled={processing}>
                    {processing ? 'Criando...' : 'Criar'}
                </button>
            </form>
        </div>
    )
}

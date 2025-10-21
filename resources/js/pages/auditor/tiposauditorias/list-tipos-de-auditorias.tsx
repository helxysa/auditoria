
import {Link, router, usePage} from '@inertiajs/react';
import {useState} from 'react';
import FormTipoDeAuditoria from './create-tipos-de-auditorias.tsx'

export default function ListagemTiposDeAuditoria(){
    const {tipo_de_auditorias} = usePage().props;
    const [modal, setModal] = useState(false);



    return (
        <>
            <div>

                <div >
                <button onClick={() => setModal(!modal)}>Criar Auditoria</button>
                        {modal ? <FormTipoDeAuditoria/> : 'Nada'}
                </div>

                <h1>Tipos de Auditoria</h1>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th> <th>Nome</th> </tr>
                    </thead>
                    <tbody>
                        {tipo_de_auditorias.data.map((tipo_de_auditoria: any) => (
                            <tr key={tipo_de_auditoria.id}>
                                <td>{tipo_de_auditoria.id}</td>
                                <td>{tipo_de_auditoria.nome}</td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </>
    )
}

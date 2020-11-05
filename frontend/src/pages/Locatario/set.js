import React, { useEffect, useState } from 'react';

import api from '../../services/api';

import Container from '../../components/Container';
import Menu from '../../components/Menu';
import ContentMain from '../../components/ContentMain';
import Unform from '../../components/Unform';

import InputMask from 'react-input-mask';

import { toast } from 'react-toastify';

export default function SetLocatario({match}) {

    const id = match.params.id || '';

    const [nome, setNome] = useState('');
    const [email, setEmail] = useState('');
    const [fone, setFone] = useState('');
    const [subTitle, setSubTitle] = useState('Cadastro de Novo Locatário');

    function reserForm() {
        setNome('');
        setEmail('');
        setFone('');
    }

    async function loadLocatario(id){
        let response = await api.get(`/locatario/index.php?id=${id}`);

        let {nome, email, fone} = response.data[0];

        setNome(nome);
        setEmail(email);
        setFone(fone);
    }

    useEffect(() => {
        if(id) {
            setSubTitle('Edição Locatário');
            loadLocatario(id);
        }
    },[]);

    async function handleSubmit() {

        try {
            let response = {};

            if(id) {
                response = await api.put(`/locatario/index.php?id=${id}`, {nome, email, fone});
            } else {
                response = await api.post(`/locatario/index.php`, {nome, email, fone});
            }

            const {status, message, error} = response.data;

            if(status === 200) {
                toast.success(message);
                
                if(!id) {
                    reserForm();
                }
            } else {
                toast.error(error)
            }
        } catch(err) {
            if(id) {
                toast.error("Erro ao editar locatário!");
            }else{
                toast.error("Erro ao cadastrar novo locatário!");
            }
        }
    }

    return (
        <Container>
            <Menu/>
            <ContentMain>
                <h2>{subTitle + (nome != '' ? ` - ${nome}` : '')}</h2>
                <Unform onSubmit={handleSubmit}>
                    <div>
                        <label>Nome</label>
                        <input type="text" value={nome} onChange={e => setNome(e.target.value)} />
                    </div>
                    <div>
                        <label>E-mail</label>
                        <input type="email" value={email} onChange={e => setEmail(e.target.value)} />
                    </div>
                    <div>
                        <label>Telefone</label>
                        <InputMask mask="(99) 99999 9999" value={fone} onChange={e => setFone(e.target.value)} />
                    </div>
                    <div className="_100 right">
                        <button type="button" onClick={handleSubmit}>Salvar</button>
                    </div>
                </Unform>
            </ContentMain>
        </Container>
    );
}

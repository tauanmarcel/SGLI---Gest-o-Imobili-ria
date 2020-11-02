import React, { useEffect, useState } from 'react';

import api from '../../services/api';

import Container from '../../components/Container';
import Menu from '../../components/Menu';
import ContentMain from '../../components/ContentMain';
import Unform from '../../components/Unform';

import { toast } from 'react-toastify';

export default function SetLocador({match}) {

    const id = match.params.id || '';
    const newDate = new Date();
    const date = newDate.getFullYear() + '-' + ('00' + newDate.getMonth()).slice(-2) + '-' + ('00' + (newDate.getDay() + 1)).slice(-2);

    console.log(date);

    const [nome, setNome] = useState('');
    const [email, setEmail] = useState('');
    const [fone, setFone] = useState('');
    const [dtRepasse, setDtRepasse] = useState(date);
    const [subTitle, setSubTitle] = useState('Cadastro de Novo Locador');

    function reserForm() {
        setNome('');
        setEmail('');
        setFone('');
        setDtRepasse('');
    }

    async function loadLocador(id){
        let response = await api.get(`/locador/index.php?id=${id}`);

        let {nome, email, fone, data_repasse} = response.data[0];

        setNome(nome);
        setEmail(email);
        setFone(fone);
        setDtRepasse(data_repasse);

    }

    useEffect(() => {
        if(id) {
            setSubTitle('Edição Locador');
            loadLocador(id);
        }
    },[]);

    async function handleSubmit() {

        try {
            let response = {};

            if(id) {
                response = await api.put(`/locador/index.php?id=${id}`, {nome, email, fone, data_repasse: dtRepasse});
            } else {
                response = await api.post(`/locador/index.php`, {nome, email, fone, data_repasse: dtRepasse});
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
                toast.error("Erro ao editar locador!");
            }else{
                toast.error("Erro ao cadastrar novo locador!");
                console.log(err)
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
                        <input type="tel" value={fone} onChange={e => setFone(e.target.value)} />
                    </div>
                    <div>
                        <label>Data do Repasse</label>
                        <input type="date" value={dtRepasse} onChange={e => setDtRepasse(e.target.value)} />
                    </div>
                    <div className="_100 right">
                        <button type="button" onClick={handleSubmit}>Salvar</button>
                    </div>
                </Unform>
            </ContentMain>
        </Container>
    );
}

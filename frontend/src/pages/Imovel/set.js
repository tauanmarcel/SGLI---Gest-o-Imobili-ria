import React, { useEffect, useState } from 'react';

import api from '../../services/api';

import Container from '../../components/Container';
import Menu from '../../components/Menu';
import ContentMain from '../../components/ContentMain';
import BuscaImovelVista from '../../components/Busca/buscaImovelVista';
import BuscaLocador from '../../components/Busca/buscaLocador';
import Unform from '../../components/Unform';
import Popup from '../../components/Poppup';

import { toast } from 'react-toastify';

export default function SetImovel({match}) {

    const id = match.params.id || '';

    const [bairro, setBairro] = useState('');
    const [cidade, setCidade] = useState('');
    const [codApi, setCodApi] = useState(null);
    const [idLocador, setIdLocador] = useState(null);
    const [nmeLocador, setNmeLocador] = useState('');
    const [subTitle, setSubTitle] = useState('Cadastro de Novo Imóvel');
    const [displayNonePopupImovel, setDisplayNonePopupImovel] = useState(true);
    const [displayNonePopupLocador, setDisplayNonePopupLocador] = useState(true);

    function reserForm() {
        setCidade('');
        setBairro('');
        setNmeLocador('');
    }

    async function loadImovel(id){
        let response = await api.get(`/imovel/index.php?id=${id}`);

        let {bairro, cidade, nme_locador} = response.data[0];

        setBairro(bairro);
        setCidade(cidade);
        setNmeLocador(nme_locador);
    }

    function preencherCampos(cod, bairro, cidade) {
        setCodApi(cod);
        setBairro(bairro);
        setCidade(cidade);
        setDisplayNonePopupImovel(true);
    }

    function peencherLocador(id, nome) {
        setIdLocador(id);
        setNmeLocador(nome);
        setDisplayNonePopupLocador(true);
    }

    function popupBuscaImovel(str) {
        if(str === '') {
            setDisplayNonePopupImovel(false)
        }
    } 

    function popupBuscaLocador(str) {
        if(str === '') {
            setDisplayNonePopupLocador(false)
        }
    } 

    async function handleSubmit() {

        const data = {
            codigo_api: codApi,
            bairro,
            cidade,
            locador_id: idLocador
        };

        try {
            let response = {};

            if(id) {
                response = await api.put(`/imovel/index.php?id=${id}`, data);
            } else {
                response = await api.post(`/imovel/index.php`, data);
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
                toast.error("Erro ao editar imóvel!");
            }else{
                toast.error("Erro ao cadastrar novo imóvel!");
            }
        }
    }

    useEffect(() => {
        if(id) {
            setSubTitle('Edição Imóvel');
            loadImovel(id);
        }
    },[]);

    return (
        <Container>
            <Menu/>
            <ContentMain>
                <h2>{subTitle + (nmeLocador != '' ? ` - ${nmeLocador}` : '')}</h2>
                <Unform onSubmit={handleSubmit}>
                    <div>
                        <label>Bairro</label>
                        <input 
                            type="text" 
                            value={bairro} 
                            onChange={e => setBairro(e.target.value)}
                            onFocus={e => popupBuscaImovel(e.target.value)}
                        />
                    </div>
                    <div>
                        <label>Cidade</label>
                        <input 
                            type="text" 
                            value={cidade} 
                            onChange={e => setCidade(e.target.value)}
                            onFocus={e => popupBuscaImovel(e.target.value)}
                        />
                    </div>
                    <div>
                        <label>Locador</label>
                        <input 
                            type="text" 
                            value={nmeLocador} 
                            onChange={e => setNmeLocador(e.target.value)}
                            onFocus={e => popupBuscaLocador(e.target.value)}
                        />
                    </div>
                    <div className="_100 right">
                        <button type="button" onClick={handleSubmit}>Salvar</button>
                    </div>
                </Unform>
            </ContentMain>
            <Popup 
                contain={<BuscaImovelVista functionPreencher={preencherCampos}/>} 
                displayNone={displayNonePopupImovel} 
                close={() => setDisplayNonePopupImovel(true)}
            />
            <Popup 
                contain={<BuscaLocador functionPreencher={peencherLocador}/>} 
                displayNone={displayNonePopupLocador} 
                close={() => setDisplayNonePopupLocador(true)}
            />
        </Container>
    );
}

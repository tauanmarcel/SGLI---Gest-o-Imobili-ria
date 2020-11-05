import React, { useEffect, useState } from 'react';

import api from '../../services/api';

import Container from '../../components/Container';
import Menu from '../../components/Menu';
import ContentMain from '../../components/ContentMain';
import Header from '../../components/Header';
import BuscaImovel from '../../components/Busca/buscaImovel';
import BuscaLocador from '../../components/Busca/buscaLocador';
import BuscaLocatario from '../../components/Busca/buscaLocatario';
import Unform from '../../components/Unform';
import Popup from '../../components/Poppup';

import { toast } from 'react-toastify';

export default function SetLocacao({match}) {

    const id = match.params.id || '';

    const [nmeLocador, setNmeLocador] = useState('');
    const [nmeLocatario, setNmeLocatario] = useState('');
    const [imovel, setImovel] = useState('');
    const [subTitle, setSubTitle] = useState('Nova Locação');
    const [dataInicio, setDataInicio] = useState();
    const [dataFim, setDataFim] = useState();
    const [taxaAdmin, setTaxaAdmin] = useState('');
    const [vlrAluguel, setVlrAluguel] = useState('');
    const [vlrCondominio, setVlrCondominio] = useState('');
    const [vlrIptu, setVlrIptu] = useState('');
    const [imovelId, setImovelId] = useState();
    const [locadorId, setLocadorId] = useState();
    const [locatarioId, setLocatarioId] = useState();
    const [displayNonePopupImovel, setDisplayNonePopupImovel] = useState(true);
    const [displayNonePopupLocador, setDisplayNonePopupLocador] = useState(true);
    const [displayNonePopupLocatario, setDisplayNonePopupLocatario] = useState(true);
    


    function reserForm() {
        setImovel('');
        setDataInicio('');
        setDataFim('');
        setTaxaAdmin('');
        setVlrAluguel('');
        setVlrCondominio('');
        setVlrIptu('');
        setImovelId('');
        setLocadorId('');
        setNmeLocador('');
        setLocatarioId('');
        setNmeLocatario('');
    }

    async function loadContrato(id){
        let response = await api.get(`/contrato/index.php?id=${id}`);

        const {
            imovel, 
            data_inicio,
            data_fim,
            taxa_admin,
            vlr_aluguel,
            vlr_condominio,
            vlr_iptu,
            imovel_id,
            locador_id,
            nme_locador,
            locatario_id,
            nme_locatario
        } = response.data[0];

        let startDate = new Date(data_inicio);
        startDate = `${startDate.getUTCFullYear()}-${('00' + (startDate.getMonth()+1)).slice(-2)}-${('00' + startDate.getUTCDate()).slice(-2)}`;
        let endDate = new Date(data_fim);
        endDate = `${endDate.getUTCFullYear()}-${('00' + (endDate.getMonth()+1)).slice(-2)}-${('00' + endDate.getUTCDate()).slice(-2)}`;
        
        setImovel(imovel);
        setDataInicio(startDate);
        setDataFim(endDate);
        setTaxaAdmin(taxa_admin);
        setVlrAluguel(vlr_aluguel);
        setVlrCondominio(vlr_condominio);
        setVlrIptu(vlr_iptu);
        setImovelId(imovel_id);
        setLocadorId(locador_id);
        setNmeLocador(nme_locador);
        setLocatarioId(locatario_id);
        setNmeLocatario(nme_locatario);
    }

    function preencherImovel(id, imovel) {
        setImovelId(id);
        setImovel(imovel);
        setDisplayNonePopupImovel(true);
    }

    function peencherLocador(id, nome) {
        setLocadorId(id);
        setNmeLocador(nome);
        setDisplayNonePopupLocador(true);
    }

    function peencherLocatario(id, nome) {
        setLocatarioId(id);
        setNmeLocatario(nome);
        setDisplayNonePopupLocatario(true);
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

    function popupBuscaLocatario(str) {
        if(str === '') {
            setDisplayNonePopupLocatario(false)
        }
    } 

    async function handleSubmit() {

        const data = {
            data_inicio: dataInicio,
            data_fim: dataFim,
            taxa_admin: taxaAdmin,
            vlr_aluguel: vlrAluguel,
            vlr_condominio: vlrCondominio,
            vlr_iptu: vlrIptu,
            imovel_id: imovelId,
            locador_id: locadorId,
            locatario_id: locatarioId
        };

        try {
            let response = {};

            if(id) {
                response = await api.put(`/contrato/index.php?id=${id}`, data);
            } else {
                response = await api.post(`/contrato/index.php`, data);
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
                toast.error("Erro ao editar locação!");
            }else{
                toast.error("Erro ao cadastrar nova locação!");
            }
        }
    }

    useEffect(() => {
        if(id) {
            setSubTitle('Edição Locação');
            loadContrato(id);
        }
    },[]);

    return (
        <Container>
            <Menu/>
            <ContentMain>
                <h2>
                    {
                        subTitle + 
                        (nmeLocador != '' ? ` - ${nmeLocador}` : '') +
                        (nmeLocatario != '' ? ` - ${nmeLocatario}` : '') +
                        (imovel != '' ? ` - ${imovel}` : '')
                    }
                </h2>
                <Unform onSubmit={handleSubmit}>
                    <div>
                        <label>Data de Início</label>
                        <input 
                            type="date" 
                            value={dataInicio}
                            onChange={e => setDataInicio(e.target.value)}
                        />
                    </div>
                    <div>
                        <label>Data Final</label>
                        <input 
                            type="date" 
                            value={dataFim} 
                            onChange={e => setDataFim(e.target.value)}
                        />
                    </div>
                    <div>
                        <label>Taxa Administrativa</label>
                        <input 
                            type="text" 
                            value={taxaAdmin} 
                            onChange={e => setTaxaAdmin(e.target.value)}
                        />
                    </div>
                    <div>
                        <label>Valor do Aluguel</label>
                        <input 
                            type="text" 
                            value={vlrAluguel} 
                            onChange={e => setVlrAluguel(e.target.value)}
                        />
                    </div>
                    <div>
                        <label>Valor do Condomínio</label>
                        <input 
                            type="text" 
                            value={vlrCondominio} 
                            onChange={e => setVlrCondominio(e.target.value)}
                        />
                    </div>
                    <div>
                        <label>Valor do IPTU</label>
                        <input 
                            type="text" 
                            value={vlrIptu} 
                            onChange={e => setVlrIptu(e.target.value)}
                        />
                    </div>
                    <div>
                        <label>Imóvel</label>
                        <input 
                            type="text" 
                            value={imovel} 
                            onChange={e => setImovel(e.target.value)}
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
                    <div>
                        <label>Locátario</label>
                        <input 
                            type="text" 
                            value={nmeLocatario} 
                            onChange={e => setNmeLocatario(e.target.value)}
                            onFocus={e => popupBuscaLocatario(e.target.value)}
                        />
                    </div>
                    <div className="_100 right">
                        <button type="button" onClick={handleSubmit}>Salvar</button>
                    </div>
                </Unform>
                <Popup 
                    contain={<BuscaImovel functionPreencher={preencherImovel}/>} 
                    displayNone={displayNonePopupImovel} 
                    close={() => setDisplayNonePopupImovel(true)}
                />
                <Popup 
                    contain={<BuscaLocador functionPreencher={peencherLocador}/>} 
                    displayNone={displayNonePopupLocador} 
                    close={() => setDisplayNonePopupLocador(true)}
                />
                <Popup 
                    contain={<BuscaLocatario functionPreencher={peencherLocatario}/>} 
                    displayNone={displayNonePopupLocatario} 
                    close={() => setDisplayNonePopupLocatario(true)}
                />
            </ContentMain>
        </Container>
    );
}

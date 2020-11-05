import React, { useEffect, useState } from 'react';
import { FaRegMoneyBillAlt, FaMoneyBillAlt } from 'react-icons/fa';

import api from '../../services/api';

import Container from '../../components/Container';
import Menu from '../../components/Menu';
import ContentMain from '../../components/ContentMain';
import Header from '../../components/Header';

import { formatPrice } from '../../util/format';

import { toast } from 'react-toastify';
import { confirmAlert } from 'react-confirm-alert';

export default function Aluguel({match}) {

    const contratoId = match.params.contratoId;
    const [repasses, setRepasses] = useState([]);
    const [nmeLocador, setNomeLocador] = useState('');
    const [nmeLocatario, setNmeLocatario] = useState('');
    const [imovel, setImovel] = useState('');

    async function loadRepasses(){
        const response = await api.get(`/repasse/index.php?contrato_id=${contratoId}`);
        setRepasses(response.data);
        setNomeLocador(response.data[0].nme_locador);
        setNmeLocatario(response.data[0].nme_locatario);
        setImovel(response.data[0].imovel);
    }

    async function handlePagamento(id) {
        
        async function pagar() {
            try {
                const response = await api.put(`/repasse/index.php?id=${id}`, {status: 'REALIZADO'});
    
                const {status, message, error} = response.data;

                if(status === 200) {
                    loadRepasses();
                    toast.success(message);
                } else {
                    toast.error(error)
                }
            } catch(err) {
                console.log(err);
                toast.error("Erro ao realizar pagamento!");
            }
        }

        confirmAlert({
            title: 'Confirmar Pagamento', 
            message: 'Deseja realmente realizar o pagamento?',
            buttons: [
                {
                  label: 'Não',
                },
                {
                  label: 'Sim',
                  onClick: () => {pagar()}
                },
            ],
        });
    }

    useEffect(() => {
        loadRepasses();
    },[]);

    return (
        <Container>
            <Menu/>
            <ContentMain>
                <Header content={
                    <div>
                        <div>
                            <p>Locador: {nmeLocador}</p>
                            <p>Locatário: {nmeLocatario}</p>
                        </div>
                        <div>
                            <p>Imóvel: {imovel}</p>
                        </div>
                    </div>
                } />
                <h3>Lista de repasses</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Nº</th>
                            <th>DATA DO REPASSE</th>
                            <th>VALOR</th>
                            <th>STATUS</th>
                            <th>PAGAR</th>
                        </tr>
                    </thead>
                    <tbody>
                        {repasses.map(repasse => (
                            <tr key={String(repasse.id)}>
                                <td>{repasse.nro_repasse}</td>
                                <td>{repasse.parse_data_repasse}</td>
                                <td>{formatPrice(repasse.vlr_repasse)}</td>
                                <td className={repasse.status === 'REALIZADO' ? 'green' : 'red'}>{repasse.status}</td>
                                <td>
                                    <button 
                                        onClick={repasse.status === 'REALIZADO' || (() => handlePagamento(repasse.id))} 
                                        disabled={repasse.status === 'REALIZADO'}
                                    >
                                        {repasse.status === 'REALIZADO' ? <FaMoneyBillAlt size={25} color="#0c5a0c" /> : <FaRegMoneyBillAlt size={25} />}
                                    </button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </ContentMain>
        </Container>
    );
}

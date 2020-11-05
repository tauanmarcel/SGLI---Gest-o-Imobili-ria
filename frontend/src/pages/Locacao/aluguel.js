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
    const [mensalidades, setMensalidades] = useState([]);
    const [nmeLocador, setNomeLocador] = useState('');
    const [nmeLocatario, setNmeLocatario] = useState('');
    const [imovel, setImovel] = useState('');

    async function loadMensalidades(){
        const response = await api.get(`/mensalidade/index.php?contrato_id=${contratoId}`);
        setMensalidades(response.data);
        setNomeLocador(response.data[0].nme_locador);
        setNmeLocatario(response.data[0].nme_locatario);
        setImovel(response.data[0].imovel);
    }

    async function handlePagamento(id) {
        
        async function pagar() {
            try {
                const response = await api.put(`/mensalidade/index.php?id=${id}`, {status: 'PAGA'});
    
                const {status, message, error} = response.data;

                if(status === 200) {
                    loadMensalidades();
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
            message: 'Deseja realmente realizar o paganneto?',
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
        loadMensalidades();
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
                <h3>Lista de mensalidades</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Nº</th>
                            <th>VENCIMENTO</th>
                            <th>VALOR</th>
                            <th>STATUS</th>
                            <th>PAGAR</th>
                        </tr>
                    </thead>
                    <tbody>
                        {mensalidades.map(mensalidade => (
                            <tr key={String(mensalidade.id)}>
                                <td>{mensalidade.nro_mensalidade}</td>
                                <td>{mensalidade.parse_data_vencimento}</td>
                                <td>{formatPrice(mensalidade.vlr_mensalidade)}</td>
                                <td className={mensalidade.status === 'PAGA' ? 'green' : 'red'}>{mensalidade.status}</td>
                                <td>
                                    <button 
                                        onClick={mensalidade.status === 'PAGA' || (() => handlePagamento(mensalidade.id))} 
                                        disabled={mensalidade.status === 'PAGA'}
                                    >
                                        {mensalidade.status === 'PAGA' ? <FaMoneyBillAlt size={25} color="#0c5a0c" /> : <FaRegMoneyBillAlt size={25} />}
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

import React, { useEffect, useState } from 'react';
import { FaUserPlus, FaEdit, FaTrashAlt, FaMoneyCheckAlt } from 'react-icons/fa';

import { Link } from 'react-router-dom';
import api from '../../services/api';

import Container from '../../components/Container';
import Menu from '../../components/Menu';
import ContentMain from '../../components/ContentMain';

import { toast } from 'react-toastify';
import { confirmAlert } from 'react-confirm-alert';

export default function Locacao() {
    const [contratos, setContratos] = useState([]);

    async function loadContratos(bairro='', cidade='', nmeLocador = '', nmeLocatario='') {

        const response = await api.get(`/contrato/index.php?bairro=${bairro}&cidade=${cidade}&nme_locador=${nmeLocador}&nme_locatario=${nmeLocatario}`);

        setContratos(response.data);
    }

    function handleRemove(id) {

        async function remove() {
            try {
                const response = await api.delete(`/contrato/index.php?id=${id}`);
    
                const {status, message, error} = response.data;

                if(status === 200) {
                    loadContratos();
                    toast.success(message);
                } else {
                    toast.error(error)
                }
            } catch(err) {
                console.log(err);
                toast.error("Erro ao excluir locação!");
            }
        }

        confirmAlert({
            title: 'Confirmar Exclusão', 
            message: 'Deseja realmente excluir?',
            buttons: [
                {
                  label: 'Não',
                },
                {
                  label: 'Sim',
                  onClick: () => {remove()}
                },
            ],
        });
    }

    useEffect(() => {
        loadContratos();
    }, []);

    return (
        <Container>
            <Menu/>
            <ContentMain>
                <h2>Busca de Locação</h2>
                <form>
                    <input name="bairro" type="text" 
                        onChange={e =>
                            loadContratos(e.target.value)
                        }
                        placeholder="bairro" 
                    />
                    <input name="cidade" type="text" 
                        onChange={e =>
                            loadContratos('', e.target.value)
                        }
                        placeholder="cidade" 
                    />
                    <input name="locador" type="text" 
                        onChange={e =>
                            loadContratos('', '', e.target.value)
                        }
                        placeholder="locador" 
                    />
                    <input name="locatario" type="text" 
                        onChange={e =>
                            loadContratos('', '', '', e.target.value)
                        }
                        placeholder="locatário" 
                    />
                    <Link to={`locacao/novo`}>
                        <FaUserPlus color="#FFF" size={14} />nova
                    </Link>
                </form>
                <h3>Informações das Locações</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>IMOVEL</th>
                            <th>VIGÊNCIA</th>
                            <th>LOCADOR</th>
                            <th>LOCATARIO</th>
                            <th width="10%">ALUGUEL</th>
                            <th width="10%">REPASSE</th>
                            <th className="th_acao">AÇÃO</th>
                        </tr>
                    </thead>
                    <tbody>
                        {contratos.map(contrato => (
                            <tr key={String(contrato.id)}>
                                <td>{contrato.id}</td>
                                <td>{contrato.imovel}</td>
                                <td>{contrato.parse_data_inicio} à {contrato.parse_data_fim}</td>
                                <td>{contrato.nme_locador}</td>
                                <td>{contrato.nme_locatario}</td>
                                <td><Link to={`mensalidade/${contrato.id}`}><FaMoneyCheckAlt size={25} /></Link></td>
                                <td><Link to={`repasse/${contrato.id}`}><FaMoneyCheckAlt size={25} /></Link></td>
                                <td>
                                    {/* <Link to={`locacao/editar/${contrato.id}`}><FaEdit /></Link> */}
                                    <button title="Excluir" onClick={() => handleRemove(contrato.id)}><FaTrashAlt /></button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </ContentMain>
        </Container>
    );
}

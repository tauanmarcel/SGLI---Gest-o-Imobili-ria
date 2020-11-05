import React, { useEffect, useState } from 'react';
import { FaUserPlus, FaEdit, FaTrashAlt } from 'react-icons/fa';

import { Link } from 'react-router-dom';
import api from '../../services/api';

import Container from '../../components/Container';
import Menu from '../../components/Menu';
import ContentMain from '../../components/ContentMain';

import { toast } from 'react-toastify';
import { confirmAlert } from 'react-confirm-alert';

export default function Imovel() {
    const [imoveis, setImovel] = useState([]);

    async function loadImoveis(bairro='', cidade='', nme_locador = '') {

        const response = await api.get(`/imovel/index.php?bairro=${bairro}&cidade=${cidade}&nme_locador=${nme_locador}`);

        setImovel(response.data);
    }

    function handleRemove(id) {

        async function remove() {
            try {
                const response = await api.delete(`/imovel/index.php?id=${id}`);
    
                const {status, message, error} = response.data;

                if(status === 200) {
                    loadImoveis();
                    toast.success(message);
                } else {
                    toast.error(error)
                }
            } catch(err) {
                console.log(err);
                toast.error("Erro ao excluir imóvel!");
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
        loadImoveis();
    }, []);

    return (
        <Container>
            <Menu/>
            <ContentMain>
                <h2>Busca de Imóvel</h2>
                <form>
                    <input name="bairro" type="text" 
                        onChange={e =>
                            loadImoveis(e.target.value)
                        }
                        placeholder="bairro" 
                    />
                    <input name="cidade" type="text" 
                        onChange={e =>
                            loadImoveis('', e.target.value)
                        }
                        placeholder="cidade" 
                    />
                    <input name="locador" type="text" 
                        onChange={e =>
                            loadImoveis('', '', e.target.value)
                        }
                        placeholder="locador" 
                    />
                    <Link to={`imovel/novo`}>
                        <FaUserPlus color="#FFF" size={14} />novo
                    </Link>
                </form>
                <h3>Informações dos Imóveis</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>BAIRRO</th>
                            <th>CIDADE</th>
                            <th>LOCADOR</th>
                            <th>AÇÃO</th>
                        </tr>
                    </thead>
                    <tbody>
                        {imoveis.map(imovel => (
                            <tr key={String(imovel.id)}>
                                <td>{imovel.id}</td>
                                <td>{imovel.bairro}</td>
                                <td>{imovel.cidade}</td>
                                <td>{imovel.nme_locador}</td>
                                <td>
                                    <Link to={`imovel/editar/${imovel.id}`}><FaEdit /></Link>
                                    <button title="Excluir" onClick={() => handleRemove(imovel.id)}><FaTrashAlt /></button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </ContentMain>
        </Container>
    );
}

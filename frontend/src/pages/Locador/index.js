import React, { useEffect, useState } from 'react';
import { FaUserPlus, FaEdit, FaTrashAlt } from 'react-icons/fa';

import { Link } from 'react-router-dom';
import api from '../../services/api';

import Container from '../../components/Container';
import Menu from '../../components/Menu';
import ContentMain from '../../components/ContentMain';
import { maskPhone } from '../../components/Functions';

import { toast } from 'react-toastify';
import { confirmAlert } from 'react-confirm-alert';

export default function Locador() {
    const [locadores, setLocadore] = useState([]);

    async function loadLocadores(name='', email='') {

        const response = await api.get(`/locador/index.php?nome=${name}&email=${email}`);

        setLocadore(response.data);
    }

    function handleRemove(id) {

        async function remove() {
            try {
                const response = await api.delete(`/locador/index.php?id=${id}`);
    
                const {status, message, error} = response.data;

                if(status === 200) {
                    loadLocadores();
                    toast.success(message);
                } else {
                    toast.error(error)
                }
            } catch(err) {
                console.log(err);
                toast.error("Erro ao excluir locador!");
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
        loadLocadores();
    }, []);

    return (
        <Container>
            <Menu/>
            <ContentMain>
                <h2>Busca de Locadores</h2>
                <form>
                    <input name="nome" type="text" 
                        onChange={e =>
                            loadLocadores(e.target.value)
                        }
                        placeholder="nome do locador" 
                    />
                    <input name="email" type="email" 
                        onChange={e =>
                            loadLocadores('', e.target.value)
                        }
                        placeholder="e-mail do locador" 
                    />
                    <Link to={`locador/novo`}>
                        <FaUserPlus color="#FFF" size={14} />novo
                    </Link>
                </form>
                <h3>Informações dos Locadores</h3>
                <table>
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="20%">NOME</th>
                            <th width="20%">E-MAIL</th>
                            <th width="30%">TELEFONE</th>
                            <th width="5%">DATA DO REPASSE</th>
                            <th width="20%">AÇÃO</th>
                        </tr>
                    </thead>
                    <tbody>
                        {locadores.map(locador => (
                            <tr key={String(locador.id)}>
                                <td>{locador.id}</td>
                                <td>{locador.nome}</td>
                                <td>{locador.email}</td>
                                <td>{maskPhone(locador.fone)}</td>
                                <td>{locador.parse_data_repasse}</td>
                                <td>
                                    <Link to={`locador/editar/${locador.id}`}><FaEdit /></Link>
                                    <button title="Excluir" onClick={() => handleRemove(locador.id)}><FaTrashAlt /></button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </ContentMain>
        </Container>
    );
}

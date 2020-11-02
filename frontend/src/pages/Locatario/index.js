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

export default function Locatario() {
    const [locatarios, setLocatarios] = useState([]);

    async function loadLocatarios(name='', email='') {

        const response = await api.get(`/locatario/index.php?nome=${name}&email=${email}`);

        setLocatarios(response.data);
    }

    function handleRemove(id) {

        async function remove() {
            try {
                const response = await api.delete(`/locatario/index.php?id=${id}`);
    
                const {status, message, error} = response.data;

                if(status === 200) {
                    loadLocatarios();
                    toast.success(message);
                } else {
                    toast.error(error)
                }
            } catch(err) {
                console.log(err);
                toast.error("Erro ao excluir locatário!");
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
        loadLocatarios();
    }, []);

    return (
        <Container>
            <h1>SGLI - Sistema de Gerenciamento de Locação Imobiliária</h1>
            <Menu/>
            <ContentMain>
                <h2>Busca de Locatários</h2>
                <form>
                    <input name="nome" type="text" 
                        onChange={e =>
                            loadLocatarios(e.target.value)
                        }
                        placeholder="nome do Locatário" 
                    />
                    <input name="email" type="email" 
                        onChange={e =>
                            loadLocatarios('', e.target.value)
                        }
                        placeholder="e-mail do Locatário" 
                    />
                    <Link to={`locatario/novo`}>
                        <FaUserPlus color="#FFF" size={14} />novo
                    </Link>
                </form>
                <h2>Informações de Locatários</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NOME</th>
                            <th>E-MAIL</th>
                            <th>TELEFONE</th>
                            <th>AÇÃO</th>
                        </tr>
                    </thead>
                    <tbody>
                        {locatarios.map(locatario => (
                            <tr key={String(locatario.id)}>
                                <td>{locatario.id}</td>
                                <td>{locatario.nome}</td>
                                <td>{locatario.email}</td>
                                <td>{maskPhone(locatario.fone)}</td>
                                <td>
                                    <Link to={`locatario/editar/${locatario.id}`}><FaEdit /></Link>
                                    <button title="Excluir" onClick={() => handleRemove(locatario.id)}><FaTrashAlt /></button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </ContentMain>
        </Container>
    );
}

import React, { useState } from 'react';
import { FaCheck } from 'react-icons/fa';

import api from '../../services/api';

import Container from '../../components/Container';
import ContentMain from '../../components/ContentMain';
import Unform from '../../components/Unform';
import { maskPhone } from '../../components/Functions';

export default function BuscaLocatario({functionPreencher}) {
    const [locatarios, setLocatarios] = useState([]);

    async function loadLocatarios(name='', email='') {

        const response = await api.get(`/locatario/index.php?nome=${name}&email=${email}`);

        setLocatarios(response.data);
    }

    return (
        <Container>
            <ContentMain popup>
                <h2>Busca de Locatários</h2>
                <Unform>
                    <div>
                        <label>Nome</label>
                        <input name="nome" type="text" 
                            onChange={e =>
                                loadLocatarios(e.target.value)
                            }
                        />
                    </div>
                    <div>
                        <label>E-mail</label>
                        <input name="email" type="email" 
                            onChange={e =>
                                loadLocatarios('', e.target.value)
                            }
                        />
                    </div>
                </Unform>
                {locatarios.length == 0 ||
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>NOME</th>
                                <th>E-MAIL</th>
                                <th>TELEFONE</th>
                                <th>DATA DO REPASSE</th>
                                <th>SELECIONAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            {
                                locatarios.map(locataario => (
                                    <tr key={String(locataario.id)}>
                                        <td>{locataario.id}</td>
                                        <td>{locataario.nome}</td>
                                        <td>{locataario.email}</td>
                                        <td>{maskPhone(locataario.fone)}</td>
                                        <td>{locataario.parse_data_repasse}</td>
                                        <td><button onClick={() => functionPreencher(locataario.id, locataario.nome)}><FaCheck /></button></td>
                                    </tr>
                                )) 
                            }
                        </tbody>
                    </table>
                }
            </ContentMain>
        </Container>
    );
}

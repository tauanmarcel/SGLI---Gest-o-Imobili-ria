import React, { useState } from 'react';
import { FaCheck } from 'react-icons/fa';

import api from '../../services/api';

import Container from '../../components/Container';
import ContentMain from '../../components/ContentMain';
import Unform from '../../components/Unform';
import { maskPhone } from '../../components/Functions';

export default function BuscaLocador({functionPreencher}) {
    const [locadores, setLocadores] = useState([]);

    async function loadLocadores(name='', email='') {

        const response = await api.get(`/locador/index.php?nome=${name}&email=${email}`);

        setLocadores(response.data);
    }

    return (
        <Container>
            <ContentMain popup>
                <h2>Busca de Locadores</h2>
                <Unform>
                    <div>
                        <label>Nome</label>
                        <input name="nome" type="text" 
                            onChange={e =>
                                loadLocadores(e.target.value)
                            }
                        />
                    </div>
                    <div>
                        <label>E-mail</label>
                        <input name="email" type="email" 
                            onChange={e =>
                                loadLocadores('', e.target.value)
                            }
                        />
                    </div>
                </Unform>
                {locadores.length == 0 ||
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>NOME</th>
                                <th>E-MAIL</th>
                                <th>TELEFONE</th>
                                <th>DIA REPASSE</th>
                                <th>SELECIONAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            {
                                locadores.map(locador => (
                                    <tr key={String(locador.id)}>
                                        <td>{locador.id}</td>
                                        <td>{locador.nome}</td>
                                        <td>{locador.email}</td>
                                        <td>{maskPhone(locador.fone)}</td>
                                        <td>{('00'+locador.dia_repasse).slice(-2)}</td>
                                        <td><button onClick={() => functionPreencher(locador.id, locador.nome)}><FaCheck /></button></td>
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

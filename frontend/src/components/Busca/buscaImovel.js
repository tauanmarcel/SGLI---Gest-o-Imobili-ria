import React, { useState } from 'react';
import { FaCheck } from 'react-icons/fa';

import api from '../../services/api';

import Container from '../../components/Container';
import ContentMain from '../../components/ContentMain';
import Unform from '../../components/Unform';
import { maskPhone } from '../../components/Functions';

export default function BuscaImovel({functionPreencher}) {
    const [imoveis, setImoveis] = useState([]);

    async function loadImoveis(bairro='', cidade='') {

        const response = await api.get(`/imovel/index.php?bairro=${bairro}&cidade=${cidade}`);

        setImoveis(response.data);
    }

    return (
        <Container>
            <ContentMain popup>
                <h2>Busca de Imóveis</h2>
                <Unform>
                    <div>
                        <label>Bairro</label>
                        <input name="bairro" type="text" 
                            onChange={e =>
                                loadImoveis(e.target.value)
                            }
                        />
                    </div>
                    <div>
                        <label>Cidade</label>
                        <input name="cidade" type="email" 
                            onChange={e =>
                                loadImoveis('', e.target.value)
                            }
                        />
                    </div>
                </Unform>
                {imoveis.length == 0 ||
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>BAIRRO</th>
                                <th>CIDADE</th>
                                <th>LOCADOR</th>
                            </tr>
                        </thead>
                        <tbody>
                            {
                                imoveis.map(imovel => (
                                    <tr key={String(imovel.id)}>
                                        <td>{imovel.id}</td>
                                        <td>{imovel.bairro}</td>
                                        <td>{imovel.cidade}</td>
                                        <td>{imovel.nme_locador}</td>
                                        <td><button onClick={() => functionPreencher(imovel.id, (`${imovel.bairro}, ${imovel.cidade}`))}><FaCheck /></button></td>
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

import React, { useState } from 'react';
import { FaCheck } from 'react-icons/fa';

import apiVista from '../../services/api_vista';

import { Link } from 'react-router-dom';

import Container from '../../components/Container';
import ContentMain from '../../components/ContentMain';
import Unform from '../../components/Unform';

export default function BuscaImovel({functionPreencher}) {

    const [imoveis, setImoveis] = useState([]);
    const [searchBairro, setSearchBairro] = useState('');
    const [searchCidade, setSearchCidade] = useState('');

    async function buscarImovelVista() {
        
        const params = {
            "fields": [
                "Codigo",
                "Bairro",
                "Cidade"
            ],
            "filter": {},
            "paginacao": {
                "pagina": 1,
                "quantidade": 50
            },
            "order":{"Codigo": "asc"}
        }

        if(searchBairro !== '') {
            params.filter.Bairro = [searchBairro];
        }

        if(searchCidade !== '') {
            params.filter.Cidade = [searchCidade];
        }

        const pesquisa = JSON.stringify(params);

        var response = await apiVista.get(`/imoveis/listar?key=c9fdd79584fb8d369a6a579af1a8f681&pesquisa=${pesquisa}`);
        
        setImoveis(Object.entries(response.data));
    }

    return (
        <Container>
            <ContentMain popup>
                <h2>Busca de Imóvel</h2>

                <Unform>
                    <div>
                        <label>Bairro</label>
                        <input type="text" value={searchBairro} onChange={e => setSearchBairro(e.target.value)} />
                    </div>
                    <div>
                        <label>Cidade</label>
                        <input type="text" value={searchCidade} onChange={e => setSearchCidade(e.target.value)} />
                    </div>
                    <div className="_100 right">
                        <button type="button" onClick={buscarImovelVista}>Buscar</button>
                    </div>
                </Unform>
                { imoveis.length == 0 || 
                    <table>
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Bairro</th>
                                <th>Cidade</th>
                                <th>Detalhes</th>
                                <th>Selecionar</th>
                            </tr>
                        </thead>
                        <tbody>
                            {
                                typeof imoveis != 'undefined' && 
                                typeof imoveis[1] != 'undefined' && 
                                typeof imoveis[1][1] != 'undefined' && 
                                imoveis[1][1] == "A pesquisa não retornou resultados."
                                ?
                                <tr>
                                    <td colSpan="5">A pesquisa não retornou resultados.</td>
                                </tr>
                                :
                                imoveis.map((imovel) => (
                                    <tr key={String(imovel[0])}>
                                        <td>{imovel[0]}</td>
                                        <td>{imovel[1].Bairro != "" ? imovel[1].Bairro : "Não Informado"}</td>
                                        <td>{imovel[1].Cidade}</td>
                                        <td><Link to={`detalhes/${imovel[0]}`}>Ver detalhes</Link></td>
                                        <td><button onClick={() => functionPreencher(imovel[1].Codigo, imovel[1].Bairro, imovel[1].Cidade)}><FaCheck /></button></td>
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

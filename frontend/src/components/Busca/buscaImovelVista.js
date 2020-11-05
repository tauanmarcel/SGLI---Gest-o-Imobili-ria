import React, { useState } from 'react';
import { FaCheck, FaAngleDoubleLeft, FaPlus } from 'react-icons/fa';
import styled from 'styled-components';

import apiVista from '../../services/api_vista';

import { Link } from 'react-router-dom';

import Container from '../../components/Container';
import ContentMain from '../../components/ContentMain';
import Unform from '../../components/Unform';

export default function BuscaImovelVista({functionPreencher}) {

    const [imoveis, setImoveis] = useState([]);
    const [imovel, setImovel] = useState([]);
    const [searchBairro, setSearchBairro] = useState('');
    const [searchCidade, setSearchCidade] = useState('');
    const [displayMain, setDisplayMain] = useState(true);
    const [displayDetail, setDisplayDetail] = useState(false);

    const Toogle = styled.div.attrs(props => ({}))`
        display: ${(props) => props.display ? 'block' : 'none'};
    `;

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

    async function loadImovelVista(id){

        console.log(id)

        const params = {
            "fields": [
                "Codigo",
                "Categoria",
                "Bairro",
                "Cidade",
                "ValorLocacao",
                "Dormitorios",
                "Suites"
            ]
        }

        const pesquisa = JSON.stringify(params);

        var response = await apiVista.get(`/imoveis/detalhes?key=c9fdd79584fb8d369a6a579af1a8f681&imovel=${id}&pesquisa=${pesquisa}`);
        
        setImovel(response.data);
    }

    function handleToogle(id) {
        if(!displayDetail){
            loadImovelVista(id);
        }
        setDisplayMain(!displayMain);
        setDisplayDetail(!displayDetail);
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
                <Toogle display={displayMain}>
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
                                        <td><button onClick={() => handleToogle(imovel[0])}><FaPlus /></button></td>
                                        <td><button onClick={() => functionPreencher(imovel[1].Codigo, imovel[1].Bairro, imovel[1].Cidade)}><FaCheck /></button></td>
                                    </tr>
                                ))
                            }
                        </tbody>
                    </table>
                }
                </Toogle>
                <Toogle display={displayDetail}>
                    <table>
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Categoria</th>
                                <th>Bairro</th>
                                <th>Cidade</th>
                                <th>V. Locação</th>
                                <th>Dormitórios</th>
                                <th>Suites</th>
                                <th>Voltar</th>
                                <th>Selecionar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{imovel.Codigo}</td>
                                <td>{imovel.Categoria}</td>
                                <td>{imovel.Bairro}</td>
                                <td>{imovel.Cidade}</td>
                                <td>{imovel.ValorLocacao != '' ? imovel.ValorLocacao : 'Não Informado'}</td>
                                <td>{imovel.Dormitorios != '' ? imovel.Dormitorios : 'Não Informado'}</td>
                                <td>{imovel.Suites != '' ? imovel.Suites : 'Não Informado'}</td>
                                <td><button onClick={handleToogle}><FaAngleDoubleLeft /></button></td>
                                <td><button onClick={() => functionPreencher(imovel.Codigo, imovel.Bairro, imovel.Cidade)}><FaCheck /></button></td>
                            </tr>
                        </tbody>
                    </table>
                </Toogle>
            </ContentMain>
        </Container>
    );
}

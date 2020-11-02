import React, { useEffect, useState } from 'react';
import {Link} from 'react-router-dom';
import apiVista from '../../services/api_vista';

import Container from '../../components/Container';
import Menu from '../../components/Menu';
import ContentMain from '../../components/ContentMain';

export default function Detalhes({match}) {

    const id = match.params.id || '';

    const [imovel, setImovel] = useState({});

    async function loadImovel(){

        const params = {
            "fields": [
                "Codigo",
                "Categoria",
                "Bairro",
                "Cidade",
                "ValorLocacao",
            ]
        }

        const pesquisa = JSON.stringify(params);

        var response = await apiVista.get(`/imoveis/detalhes?key=c9fdd79584fb8d369a6a579af1a8f681&imovel=${id}&pesquisa=${pesquisa}`);
        
        setImovel(response.data);
    }

    useEffect(() => {
        loadImovel();
    },[]);
    
    return (
        <Container>
            <Menu/>
            <ContentMain>
                <h2>Detalhes do Imóvel</h2>
                <Link to={`../novo`}>Voltar</Link>
                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Categoria</th>
                            <th>Bairro</th>
                            <th>Cidade</th>
                            <th>Valor da Locacao</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{imovel.Codigo}</td>
                            <td>{imovel.Categoria}</td>
                            <td>{imovel.Bairro}</td>
                            <td>{imovel.Cidade}</td>
                            <td>{imovel.ValorLocacao != '' ? imovel.ValorLocacao : 'Não Informado'}</td>
                        </tr>
                    </tbody>
                </table>
            </ContentMain>
        </Container>
    );
}

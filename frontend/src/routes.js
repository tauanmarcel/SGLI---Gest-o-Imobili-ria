import React from 'react';
import { BrowserRouter, Switch, Route } from 'react-router-dom';

import Main from './pages/Main';
import Locatario from './pages/Locatario';
import SetLocatario from './pages/Locatario/set';
import Locador from './pages/Locador';
import SetLocador from './pages/Locador/set';
import Imovel from './pages/Imovel';
import SetImovel from './pages/Imovel/set';
import Locacao from './pages/Locacao';
import SetLocacao from './pages/Locacao/set';
import Aluguel from './pages/Locacao/aluguel';
import Repasse from './pages/Locacao/repasse';

export default function Routes() {
    return (
        <BrowserRouter>
            <Switch>
                <Route path="/" exact component={Main} />
                <Route path="/locatario" exact component={Locatario} />
                <Route path="/locatario/novo" component={SetLocatario} />
                <Route path="/locatario/editar/:id" component={SetLocatario} />
                <Route path="/locador" exact component={Locador} />
                <Route path="/locador/novo" component={SetLocador} />
                <Route path="/locador/editar/:id" component={SetLocador} />
                <Route path="/imovel" exact component={Imovel} />
                <Route path="/imovel/novo" component={SetImovel} />
                <Route path="/imovel/editar/:id" component={SetImovel} />
                <Route path="/locacao" exact component={Locacao} />
                <Route path="/locacao/novo" exact component={SetLocacao} />
                <Route path="/locacao/editar/:id" exact component={SetLocacao} />
                <Route path="/mensalidade/:contratoId" exact component={Aluguel} />
                <Route path="/repasse/:contratoId" exact component={Repasse} />
            </Switch>
        </BrowserRouter>
    );
}

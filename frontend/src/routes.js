import React from 'react';
import { BrowserRouter, Switch, Route } from 'react-router-dom';

import Locatario from './pages/Locatario';
import SetLocatario from './pages/Locatario/set';
import Locador from './pages/Locador';
import SetLocador from './pages/Locador/set';
import Imovel from './pages/Imovel';
import SetImovel from './pages/Imovel/set';
import DetalhesImovel from './pages/Imovel/detalhes';

export default function Routes() {
    return (
        <BrowserRouter>
            <Switch>
                <Route path="/" exact component={Locatario} />
                <Route path="/locatario" exact component={Locatario} />
                <Route path="/locatario/novo" component={SetLocatario} />
                <Route path="/locatario/editar/:id" component={SetLocatario} />
                <Route path="/locador" exact component={Locador} />
                <Route path="/locador/novo" component={SetLocador} />
                <Route path="/locador/editar/:id" component={SetLocador} />
                <Route path="/imovel" exact component={Imovel} />
                <Route path="/imovel/novo" component={SetImovel} />
                <Route path="/imovel/editar/:id" component={SetImovel} />
                <Route path="/imovel/detalhes/:id" component={DetalhesImovel} />
            </Switch>
        </BrowserRouter>
    );
}

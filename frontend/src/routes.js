import React from 'react';
import { BrowserRouter, Switch, Route } from 'react-router-dom';

import Locatario from './pages/Locatario';
import SetLocatario from './pages/Locatario/set';

export default function Routes() {
    return (
        <BrowserRouter>
            <Switch>
                <Route path="/" exact component={Locatario} />
                <Route path="/locatario" exact component={Locatario} />
                <Route path="/locatario/novo" component={SetLocatario} />
                <Route path="/locatario/editar/:id" component={SetLocatario} />
            </Switch>
        </BrowserRouter>
    );
}

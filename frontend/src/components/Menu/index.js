import React from 'react';
import { Link } from 'react-router-dom';
import Nav from './style';
import '../../styles/confirm.css';

import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.min.css';
import 'react-confirm-alert/src/react-confirm-alert.css';

export default function Menu() {
    return (
        <Nav>
              <ul>
                <li><Link to={`/locatario`}>LOCATÁRIO</Link></li>
                <li><Link to={`/locador`}>LOCADOR</Link></li>
                <li><Link to={`/locacao`}>LOCAÇÃO</Link></li>
              </ul>
              <ToastContainer/>
        </Nav>
    );
}

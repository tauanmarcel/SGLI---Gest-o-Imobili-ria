import React from 'react';
import { Link } from 'react-router-dom';
import ContentMenu from './style';
import '../../styles/confirm.css';

import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.min.css';
import 'react-confirm-alert/src/react-confirm-alert.css';

export default function Menu() {    
   return (
      <ContentMenu>
         <nav>
            <ul>
               <li><Link to={`/locatario`}>LOCATÁRIO</Link></li>
               <li><Link to={`/locador`}>LOCADOR</Link></li>
               <li><Link to={`/imovel`}>IMÓVEL</Link></li>
            </ul>
            <ToastContainer/>
         </nav>
      </ContentMenu>
   );
}

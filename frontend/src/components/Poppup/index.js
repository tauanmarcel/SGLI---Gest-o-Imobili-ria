import React from 'react';
import { FaRegTimesCircle } from 'react-icons/fa';

import Content from './styles';

export default function Popup({contain, displayNone, close}) {
   return (
      <Content displayNone={displayNone}>
         <div>
            <button onClick={close}><FaRegTimesCircle size={30} color="#50b792" title="Fechar" /></button>
            {contain}
         </div>
      </Content>
   ); 
}
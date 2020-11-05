import React, {useState, useEffect} from 'react';

import Menu from '../../components/Menu'
import Container from '../../components/Container'
import ContentMain from '../../components/ContentMain'
import PageMain from './styles';

import api from '../../services/api';

export default function Main() {

   const [mensalidades, setMensalidades] = useState([]); 
   const [repasses, setRepasses] = useState([]); 
   const [ateDia, setAteDia] = useState(7);

   async function loadMensalidades() {

      const response = await api.get(`/mensalidade/index.php?ate_dias=${ateDia}`);

      setMensalidades(response.data);
   }

   async function loadRepasses() {

      const response = await api.get(`/repasse/index.php?ate_dias=${ateDia}`);

      setRepasses(response.data);
   }

   useEffect(() => {
      loadMensalidades();
      loadRepasses();
   }, [ateDia])

   return (
      <Container>
         <Menu />
         <ContentMain>
            <h2>Resumo</h2>
            <PageMain>
               <div>
                  <p className="main_box_title">Mensalidades a vencer nos próximos <input type="text" className="ate_dias" value={ateDia} onChange={e => setAteDia(e.target.value)}/> dias</p>
                  <p className="main_box_content">{mensalidades.length}</p>
               </div>
               <div>
                  <p className="main_box_title">Repasses a vencer nos próximos <input type="text" className="ate_dias" value={ateDia} onChange={e => setAteDia(e.target.value)}/> dias</p>
                  <p className="main_box_content">{repasses.length}</p>
               </div>
            </PageMain>
         </ContentMain>
      </Container>
   )
}
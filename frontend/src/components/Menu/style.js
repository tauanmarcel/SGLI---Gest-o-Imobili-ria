import styled from 'styled-components';

const Nav = styled.nav`

   width: 15%;
   max-width: 200px;
   float: left;
   margin-top: 5%;

   ul {

      li {
         list-style: none;
         background-color: #203443;
         margin-bottom: 5px;
         border-radius: 5px;

         a {
            text-decoration: none;
            color: #fff;
            text-align: center;
            display: block;
            padding: 15px 25px;
         }

         a:hover {
            color: #50b792;
         }
      }

      li:hover {
         background-color: #324f64;
      }
   }

`;

export default Nav;
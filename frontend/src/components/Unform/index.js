import styled from 'styled-components';
import {Form} from '@rocketseat/unform';

const Unform = styled(Form)`
   border: solid 1px #50b792;
   box-shadow: 0 0 5px rgba(80,183,146,.5);
   border-radius: 5px;
   box-sizing: border-box;
   display: flex;
   justify-content: space-between;
   align-items: baseline;
   flex-wrap: wrap;
   padding: 5%;

   ._100 {
      width: 100% !important;
   }

   > .right {
         justify-content: flex-end;
   }

   > div {
      width: 50%;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      padding: 0 10px;

      label {
         margin-bottom: 5px;
      }

      input {
         margin-bottom: 35px;
         color: #203443;
      }

      label, input {
         display: flex;
         font-weight: bold;
         width: 100%;
         margin-right: 0;
      }

      button {
         width: 100px;
         height: 30px;
         background-color: #50b792;
         border: none;
         color: #fff;
         border-radius: 3px;
         display: flex;
         justify-content: center;
         align-items: center;
         text-decoration: none;
         align-self: flex-end;
      }
   }
`;

export default Unform;
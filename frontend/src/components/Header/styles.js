import styled from 'styled-components';

const Content = styled.header`
   float: left;
   margin: 25px 0 25px 7%;
   box-sizing: border-box;
   border: solid 1px #50b792;
   width: 85.5%;
   border-radius: 3px;
   padding: 1% 2%;

   > div {
     > div {
        float: left;
        width: 33.33%;
     } 
   }

   p {
      margin: 15px 0;
      font-weight: bold;
   }
`;

export default Content;
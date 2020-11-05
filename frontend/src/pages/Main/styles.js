import styled from 'styled-components';

export const PageMain = styled.div`

   width: 85%;
   min-height: 400px;
   border: solid 1px #50b792;
   border-radius: 5px;
   margin: 0 auto;
   padding: 3% 3%;
   box-sizing: border-box;
   display: flex;
   justify-content: flex-start;
   align-items: flex-start;
   flex-wrap: wrap;

   > div {
      width: 29%;
      height: 130px;
      box-shadow: 0px 0px 10px #50b792;
      box-sizing: border-box;
      padding: 3%;
      border-radius: 10px;
      margin: 2%;

      .main_box_title {
         font-weight: bold;
         font-size: 1.2em;
         text-align: center;
         height: 35px;
      }

      p.main_box_content {
         font-size: 2em;
         text-align: center;
         margin-top: 10px;
         font-weight: bold;
      }
   }

`;

export default PageMain;
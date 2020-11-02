import styled from 'styled-components';

const Content = styled.div.attrs(props => ({}))`
   display: ${props => props.displayNone ? 'none' : 'block'};
   position: fixed;
   width: 100%;
   height: 100%;
   top: 0;
   left: 0;
   background: rgba(255,255,255,1);

   > div {
      width: 80%;
      margin: 3% auto 0;
      height: 90%;
      overflow-y: auto;
      overflow-x: hidden;
      position: relative;

      > button > svg {
         position: absolute;
         right: 10%;
         top: 14%;
      }
   }
`;

export default Content;
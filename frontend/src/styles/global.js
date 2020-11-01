import { createGlobalStyle } from 'styled-components';

export default createGlobalStyle`
    *{
        margin: 0;
        padding: 0;
        outline: none;
        box-sizing: border-box;
    }

    html, body, #root{
        min-height: 100%;
    }

    body{
        -webkit-font-smoothing: antialiased !important;
    }

    body, input, button {
        color: #222;
        font-size: 14px;
        font-family: sans-serif;
    }

    button{
        cursor: pointer;
    }
`;

import axios from 'axios';

const apiVista = axios.create({
    baseURL: 'http://sandbox-rest.vistahost.com.br/',
});

export default apiVista;

import axios from 'axios';

const api = axios.create({
    baseURL: 'http://localhost/sgli/backend/src/public/',
});

export default api;

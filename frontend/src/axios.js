import axios from 'axios'

const api = axios.create({
    baseURL: 'http://localhost/api', // Laravel API base URL
})

export default api
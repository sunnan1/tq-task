import axios from 'axios';

const axiosInstance = axios.create({
  baseURL: 'http://127.0.0.1:8000/api/', // Set your base URL here
  headers: {
    'Content-Type': 'application/json',
  },
});

axiosInstance.interceptors.response.use(
    (response) => {
      return response;
    },
    (error) => {
      if (error.response.data.message.includes('Unauthenticated')) {
        localStorage.removeItem('authToken');
          window.location.href = '/login';
      }
      return Promise.reject(error);
    }
);
export default axiosInstance;

import { ref } from 'vue';
import api from '@/services/api';
import router from '@/router';

const token = ref(localStorage.getItem('token') || '');

export const useAuthStore = () => {
    const login = async (email, password) => {
        const { data } = await api.post('/login', { email, password });
        token.value = data.token;
        localStorage.setItem('token', data.token);
        router.push('/tasks'); 
    };

    const logout = () => {
        localStorage.removeItem('token');
        token.value = '';
        router.push('/login'); 
    };

    return { token, login, logout };
};

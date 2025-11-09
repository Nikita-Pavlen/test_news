import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const getCookie = (name) => {
    const value = document.cookie
        ?.split('; ')
        ?.find((row) => row.startsWith(`${name}=`));

    return value ? decodeURIComponent(value.split('=')[1]) : null;
};

window.axios = axios;
window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

import _ from 'lodash';
window._ = _;

import 'bootstrap';

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = Echo;

window.ws = {};
window.ws.key = import.meta.env.VITE_PUSHER_APP_KEY;
window.ws.host = import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`;
window.ws.wsPort = import.meta.env.VITE_PUSHER_PORT ?? 80;
window.ws.wssPort = import.meta.env.VITE_PUSHER_PORT ?? 443;
window.ws.forceTLS = (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https';

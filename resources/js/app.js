import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

import { Ziggy as ZiggyConfig } from './ziggy';
import '../../vendor/tightenco/ziggy/dist/route.umd'; // Solo importa para que se ejecute y defina 'window.route'

window.Ziggy = ZiggyConfig; // La función 'route' necesita la configuración de Ziggy en 'window.Ziggy'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.config.globalProperties.$route = window.route; // Asigna la función 'route' global a $route en Vue
        return app.use(plugin).mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
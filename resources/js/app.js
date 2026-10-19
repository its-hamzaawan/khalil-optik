import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import SiteLayout from './Layouts/SiteLayout.vue';
import AdminLayout from './Layouts/AdminLayout.vue';
import reveal from './Composables/reveal';

const appName = 'Khalil Optik';

createInertiaApp({
    title: (title) => (title ? `${title} · ${appName}` : `${appName} — Trusted for Your Vision Since 1960`),

    resolve: async (name) => {
        const page = await resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'));

        // Persistent layouts: the header/footer stay mounted between pages,
        // which lets us animate only the page content.
        if (page.default.layout === undefined) {
            if (name.startsWith('Admin/') && name !== 'Admin/Login') page.default.layout = AdminLayout;
            else if (name !== 'Admin/Login' && name !== 'Error') page.default.layout = SiteLayout;
        }

        return page;
    },

    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .directive('reveal', reveal)
            .mount(el);
    },

    progress: { color: '#9DB8DE', showSpinner: false },
});

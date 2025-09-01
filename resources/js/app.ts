import '../css/app.css';
import './bootstrap';

import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, DefineComponent, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

import './plugins/fontawesome';

import { createPinia } from 'pinia';

import 'tippy.js/dist/tippy.css';
import AppLayout from "@/Layouts/AppLayout.vue";
import { ToastProps, ToastSeverity, useToastsStore } from "@/stores/toastsStore";
import { useUserStore } from './stores/userStore';

const pinia = createPinia();

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const page = resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./Pages/**/*.vue'),
        );

        page.then((module) => {
            module.default.layout = module.default.layout || AppLayout;
        });

        return page;
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({
            render: () => h(App, props),
            mounted(): any {
            }
        })
            .use(plugin)
            .use(ZiggyVue)
            .use(pinia);

        const userStore = useUserStore();
        if (props.initialPage.props.auth && props.initialPage.props.auth.user) {
            userStore.setUser(props.initialPage.props.auth.user);
        }

        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
}).then(() => {
    router.on('error', (event) => {
        const store = useToastsStore();
        Object.values(event.detail.errors).forEach((errorMsg) => {
            const toast: ToastProps = {
                message: errorMsg,
                severity: ToastSeverity.ERROR,
            }
            store.displayToast(toast);
        });
    });
});


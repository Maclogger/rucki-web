import '../css/app.css';
import './bootstrap';

import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {createApp, DefineComponent, h} from 'vue';
import {ZiggyVue} from '../../vendor/tightenco/ziggy';

import './plugins/fontawesome';


import PrimeVue from 'primevue/config';
import {myPreset} from './plugins/primevue';

import {createPinia} from 'pinia';

const pinia = createPinia();

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

await createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./Pages/**/*.vue'),
        ),
    setup({el, App, props, plugin}) {
        const app = createApp({render: () => h(App, props)})
            .use(plugin)
            .use(ZiggyVue)
            .use(pinia)
            .use(PrimeVue, {
                theme: {
                    preset: myPreset,
                    options: {
                        prefix: 'p',
                        darkModeSelector: 'system',
/*
                        darkModeSelector: 'none',
*/
                    },
                },
            })
            /*
                        .use(PrimeVue, {
                            theme: {
                                preset: Lara,
                                options: {
                                    prefix: 'p',
                                    darkModeSelector: 'none',
                                    cssLayer: true
                                }
                            }
                        })
            */
            .mount(el)
        ;
    },
    progress: {
        color: '#4B5563',
    },
});


import './bootstrap';
import '../css/app.css';
import 'react-toastify/dist/ReactToastify.css';

import {createRoot} from 'react-dom/client';
import {createInertiaApp} from '@inertiajs/react';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ToastContainer} from "react-toastify";
import { library } from '@fortawesome/fontawesome-svg-core';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { fab } from '@fortawesome/free-brands-svg-icons';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

library.add(far);
library.add(fas);
library.add(fab);

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.jsx`, import.meta.glob('./Pages/**/*.jsx')),
    setup({el, App, props}) {
        const root = createRoot(el);

        root.render(
            <>
                <App {...props} />
                <ToastContainer
                    theme={"colored"}
                />
            </>);
    },
    progress: {
        color: '#4B5563',
    },
});

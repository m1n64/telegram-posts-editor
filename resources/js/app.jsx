import './bootstrap';
import '../css/app.css';
import 'react-toastify/dist/ReactToastify.css';

import {createRoot} from 'react-dom/client';
import {createInertiaApp} from '@inertiajs/react';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {toast, ToastContainer} from "react-toastify";
import {library} from '@fortawesome/fontawesome-svg-core';
import {fas} from '@fortawesome/free-solid-svg-icons';
import {far} from '@fortawesome/free-regular-svg-icons';
import {fab} from '@fortawesome/free-brands-svg-icons';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

library.add(far);
library.add(fas);
library.add(fab);

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.jsx`, import.meta.glob('./Pages/**/*.jsx')),
    setup({el, App, props}) {
        const root = createRoot(el);

        const user = props.initialPage.props.auth.user;
        if (user) {
            Echo.private('notification.' + user.id)
                .listen('.NotificationEvent', (e) => {
                    switch (e.type) {
                        case 'success':
                            toast.success(e.message);
                            break;

                        case 'error':
                            toast.error(e.message);
                            break;

                        case 'info':
                            toast.info(e.message);
                            break;
                    }
                })
        }

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

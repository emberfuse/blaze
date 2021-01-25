import { InertiaProgress } from '@inertiajs/progress';

/**
 * Initialize page load indicator progressbar.
 *
 * @return  {void}
 */
export default function initProgressIndicator() {
    InertiaProgress.init({
        delay: 250,
        color: '#3B82F6',
        includeCSS: true,
        showSpinner: false,
    });
}

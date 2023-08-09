import './bootstrap';

import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';

const rGlobal = () => {
    if ( document.querySelector('[data-pops]') ) {
        import('./partials/_tooltip').then(({default: rTooltip}) => { rTooltip(); });
    }
};

if ( document.readyState !== 'loading' ) {
    rGlobal();
} else {
    document.addEventListener( 'DOMContentLoaded', rGlobal );
}

window.Alpine = Alpine;

Alpine.plugin(focus);

Alpine.start();

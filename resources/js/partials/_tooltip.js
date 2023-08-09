/**
 * @file _tooltip.js
 */

import {
    computePosition,
    autoUpdate,
    flip,
    shift,
    offset,
    arrow,
} from '@floating-ui/dom';

/**
 * Tooltip
 */
const rTooltip = () => {
    const triggers = document.querySelectorAll('[data-pops]');

    if (!triggers || !triggers.length) {
        return;
    }

    const cleanupFns = {};

    /**
     * Hide tooltip
     *
     * @param {Event} e - Trigger event
     */
    const hide = ({target}) => {
        const t = target.closest('[data-pops]');
        const tip = document.getElementById(t.dataset.pops);

        if (!t || !tip) {
            return;
        }

        tip.classList.add('hidden');

        if (cleanupFns[t.dataset.pops]) {
            cleanupFns[t.dataset.pops]();
        }
    };

    /**
     * Show tooltip
     *
     * @param {Event} e - Trigger event
     */
    const show = ({target}) => {
        const t = target.closest('[data-pops]');
        const tip = document.getElementById(t.dataset.pops);

        if (!t || !tip) {
            return;
        }

        // const tip = document.getElementById(t.dataset.pops);
        const arrowElement = tip.querySelector('[data-arrow]');

        /**
         * Update tooltip position.
         *
         * @param {HTMLElement} t - Trigger element
         */
        const updatePosition = () => {
            computePosition(t, tip, {
                placement: t.dataset.placement || 'top',
                middleware: [
                    offset(8),
                    flip(),
                    shift({padding: 10}),
                    arrow({element: arrowElement})
                ]
            }).then(({x, y, placement, middlewareData}) => {
                Object.assign(tip.style, {
                    left: `${x}px`,
                    top: `${y}px`,
                });

                // Accessing the data
                const {x: arrowX, y: arrowY} = middlewareData.arrow;

                const staticSide = {
                    top: 'bottom',
                    right: 'left',
                    bottom: 'top',
                    left: 'right',
                }[placement.split('-')[0]];

                Object.assign(arrowElement.style, {
                    left: arrowX != null ? `${arrowX}px` : '',
                    top: arrowY != null ? `${arrowY}px` : '',
                    right: '',
                    bottom: '',
                    [staticSide]: '-4px',
                });
            });
        };

        tip.classList.remove('hidden');
        // update(t);
        cleanupFns[t.dataset.pops] = autoUpdate(t, tip, updatePosition);
    };

    [...triggers].forEach((t) => {
        t.addEventListener('focus', show);
        t.addEventListener('blur', hide);
        t.addEventListener('click', (e) => {
            e.preventDefault();

            const { target } = e;

            if (!target.closest('[data-pops]')) {
                return;
            }

            const currentExpanded = target.getAttribute('aria-expanded') === 'true';

            if (currentExpanded) {
                hide(e);
            } else {
                show(e);
            }

            target.setAttribute('aria-expanded', !currentExpanded);
        });
    });
};

export default rTooltip;

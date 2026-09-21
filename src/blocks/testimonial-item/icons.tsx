import type { Element as WPElement } from '@wordpress/element';

// Same stroke/fill-based SVG approach as alert-box's icons.tsx: hand-
// written shapes (not Dashicons) so the JS preview (here) and the
// front-end render (render.php, same path hand-written in PHP) don't
// depend on Dashicons, which aren't guaranteed to be enqueued on the
// front end by every theme.
const STAR_PATH = 'M12 2.5l2.9 6.6 7.1.6-5.4 4.7 1.6 7-6.2-3.8-6.2 3.8 1.6-7-5.4-4.7 7.1-.6z';

export const StarFilled: WPElement = (
    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
        <path d={STAR_PATH} fill="currentColor" />
    </svg>
);

export const StarEmpty: WPElement = (
    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
        <path
            d={STAR_PATH}
            fill="none"
            stroke="currentColor"
            strokeWidth="1.5"
            strokeLinejoin="round"
        />
    </svg>
);

import type { Element as WPElement } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import type { AlertVariant } from './types';

// Simple stroke-based SVGs (no external icon library) so the JS preview
// (here) and the front-end render (render.php, same shapes hand-written
// in PHP) don't depend on Dashicons — Dashicons are always available in
// the editor, but not guaranteed on the front end unless the active
// theme happens to enqueue them.
export const VARIANT_ICONS: Record<AlertVariant, WPElement> = {
    info: (
        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <circle
                cx="12"
                cy="12"
                r="10"
                fill="none"
                stroke="currentColor"
                strokeWidth="2"
            />
            <line
                x1="12"
                y1="11"
                x2="12"
                y2="16"
                stroke="currentColor"
                strokeWidth="2"
                strokeLinecap="round"
            />
            <circle cx="12" cy="7.5" r="1.25" fill="currentColor" />
        </svg>
    ),
    success: (
        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <circle
                cx="12"
                cy="12"
                r="10"
                fill="none"
                stroke="currentColor"
                strokeWidth="2"
            />
            <polyline
                points="7.5,12.5 10.5,15.5 16.5,9"
                fill="none"
                stroke="currentColor"
                strokeWidth="2"
                strokeLinecap="round"
                strokeLinejoin="round"
            />
        </svg>
    ),
    warning: (
        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path
                d="M12 3.5 2.5 20h19z"
                fill="none"
                stroke="currentColor"
                strokeWidth="2"
                strokeLinejoin="round"
            />
            <line
                x1="12"
                y1="10"
                x2="12"
                y2="14.5"
                stroke="currentColor"
                strokeWidth="2"
                strokeLinecap="round"
            />
            <circle cx="12" cy="17" r="1.1" fill="currentColor" />
        </svg>
    ),
    error: (
        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <circle
                cx="12"
                cy="12"
                r="10"
                fill="none"
                stroke="currentColor"
                strokeWidth="2"
            />
            <line
                x1="8.5"
                y1="8.5"
                x2="15.5"
                y2="15.5"
                stroke="currentColor"
                strokeWidth="2"
                strokeLinecap="round"
            />
            <line
                x1="15.5"
                y1="8.5"
                x2="8.5"
                y2="15.5"
                stroke="currentColor"
                strokeWidth="2"
                strokeLinecap="round"
            />
        </svg>
    ),
};

export const VARIANT_LABELS: Record<AlertVariant, string> = {
    info: __('Info', 'pv-blocks-suite'),
    success: __('Success', 'pv-blocks-suite'),
    warning: __('Warning', 'pv-blocks-suite'),
    error: __('Error', 'pv-blocks-suite'),
};

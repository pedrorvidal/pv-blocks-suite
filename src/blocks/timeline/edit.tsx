import type { BlockEditProps } from '@wordpress/blocks';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import type { TimelineAttributes } from './types';

const ALLOWED_BLOCKS = ['pv-blocks-suite/timeline-item'];

const TEMPLATE = [
    [
        'pv-blocks-suite/timeline-item',
        {
            date: '2022',
            heading: __('Company founded', 'pv-blocks-suite'),
        },
    ],
    [
        'pv-blocks-suite/timeline-item',
        {
            date: '2023',
            heading: __('First 1,000 customers', 'pv-blocks-suite'),
        },
    ],
    [
        'pv-blocks-suite/timeline-item',
        {
            date: '2024',
            heading: __('Series A funding', 'pv-blocks-suite'),
        },
    ],
];

export default function Edit(_props: BlockEditProps<TimelineAttributes>) {
    const blockProps = useBlockProps();

    // A timeline is inherently ordered (events in sequence), so the
    // wrapper is a real <ol>, not a generic <div> — matches render.php.
    return (
        <ol {...blockProps}>
            <InnerBlocks
                allowedBlocks={ALLOWED_BLOCKS}
                template={TEMPLATE}
                templateLock={false}
            />
        </ol>
    );
}

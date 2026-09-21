import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

const ALLOWED_BLOCKS = ['pv-blocks-suite/tab-item'];

const TEMPLATE = [
    [
        'pv-blocks-suite/tab-item',
        { label: __('Tab 1', 'pv-blocks-suite') },
        [
            [
                'core/paragraph',
                { placeholder: __('Add content…', 'pv-blocks-suite') },
            ],
        ],
    ],
    [
        'pv-blocks-suite/tab-item',
        { label: __('Tab 2', 'pv-blocks-suite') },
        [
            [
                'core/paragraph',
                { placeholder: __('Add content…', 'pv-blocks-suite') },
            ],
        ],
    ],
];

// No attributes of its own (mirrors accordion's parent), so no props are
// needed here — a function with fewer parameters than BlockEditProps<T>
// declares is still assignable where an `edit` component is expected.
export default function Edit() {
    const blockProps = useBlockProps();

    // The editor deliberately shows every tab-item stacked and
    // simultaneously visible via plain InnerBlocks, not a working
    // interactive tab-switcher — the Interactivity API only hydrates on
    // the real front end, and a working switcher here would fight the
    // editor's own block-selection handling and hide content from
    // editing, the same problem accordion-item's editor already solves
    // by always showing its panel open.
    return (
        <div {...blockProps}>
            <InnerBlocks
                allowedBlocks={ALLOWED_BLOCKS}
                template={TEMPLATE}
                templateLock={false}
            />
        </div>
    );
}

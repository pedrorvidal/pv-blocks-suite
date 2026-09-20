import type { BlockEditProps } from '@wordpress/blocks';
import { useBlockProps, InspectorControls, InnerBlocks } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useEffect } from '@wordpress/element';

import type { AccordionAttributes } from './types';

const ALLOWED_BLOCKS = ['pv-blocks-suite/accordion-item'];

const TEMPLATE = [
    [
        'pv-blocks-suite/accordion-item',
        { summary: __('Question 1', 'pv-blocks-suite') },
        [
            [
                'core/paragraph',
                { placeholder: __('Add the answer…', 'pv-blocks-suite') },
            ],
        ],
    ],
    [
        'pv-blocks-suite/accordion-item',
        { summary: __('Question 2', 'pv-blocks-suite') },
        [
            [
                'core/paragraph',
                { placeholder: __('Add the answer…', 'pv-blocks-suite') },
            ],
        ],
    ],
    [
        'pv-blocks-suite/accordion-item',
        { summary: __('Question 3', 'pv-blocks-suite') },
        [
            [
                'core/paragraph',
                { placeholder: __('Add the answer…', 'pv-blocks-suite') },
            ],
        ],
    ],
];

export default function Edit({
    attributes,
    setAttributes,
    clientId,
}: BlockEditProps<AccordionAttributes>) {
    const { groupId, allowMultipleOpen } = attributes;

    // A stable, unique group id is required so the native
    // `<details name="...">` mutual-exclusivity behavior (see
    // accordion-item's render.php) only groups items within THIS
    // accordion instance, not every accordion on the page. `clientId` is
    // already a unique id per block instance, so it's persisted once on
    // first insert instead of generating a separate one.
    useEffect(() => {
        if (!groupId) {
            setAttributes({ groupId: clientId });
        }
    }, [groupId, clientId, setAttributes]);

    const blockProps = useBlockProps();

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Behavior', 'pv-blocks-suite')}>
                    <ToggleControl
                        label={__(
                            'Allow multiple items open at once',
                            'pv-blocks-suite'
                        )}
                        help={__(
                            'When off, opening an item closes any other open item.',
                            'pv-blocks-suite'
                        )}
                        checked={allowMultipleOpen}
                        onChange={(value: boolean) =>
                            setAttributes({ allowMultipleOpen: value })
                        }
                    />
                </PanelBody>
            </InspectorControls>

            <div {...blockProps}>
                <InnerBlocks
                    allowedBlocks={ALLOWED_BLOCKS}
                    template={TEMPLATE}
                    templateLock={false}
                />
            </div>
        </>
    );
}

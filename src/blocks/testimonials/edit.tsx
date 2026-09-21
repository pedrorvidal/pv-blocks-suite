import type { BlockEditProps } from '@wordpress/blocks';
import { useBlockProps, InspectorControls, InnerBlocks } from '@wordpress/block-editor';
import { PanelBody, RangeControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import type { TestimonialsAttributes } from './types';

const ALLOWED_BLOCKS = ['pv-blocks-suite/testimonial-item'];

const TEMPLATE = [
    ['pv-blocks-suite/testimonial-item', {}],
    ['pv-blocks-suite/testimonial-item', {}],
    ['pv-blocks-suite/testimonial-item', {}],
];

export default function Edit({
    attributes,
    setAttributes,
}: BlockEditProps<TestimonialsAttributes>) {
    const { columns } = attributes;

    // useBlockProps() (from the untyped @wordpress/block-editor module,
    // see src/global.d.ts) accepts arbitrary style objects, so a CSS
    // custom property key here doesn't need a cast the way it would
    // against React's own stricter CSSProperties type.
    const blockProps = useBlockProps({
        style: {
            '--testimonials-columns': columns,
        },
    });

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Layout', 'pv-blocks-suite')}>
                    <RangeControl
                        label={__('Columns', 'pv-blocks-suite')}
                        value={columns}
                        onChange={(value?: number) =>
                            setAttributes({ columns: value ?? 3 })
                        }
                        min={2}
                        max={4}
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

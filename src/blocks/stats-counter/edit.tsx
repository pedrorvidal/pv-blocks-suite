import type { BlockEditProps } from '@wordpress/blocks';
import { useBlockProps, InspectorControls, InnerBlocks } from '@wordpress/block-editor';
import { PanelBody, RangeControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import type { StatsCounterAttributes } from './types';

const ALLOWED_BLOCKS = ['pv-blocks-suite/stat-item'];

const TEMPLATE = [
    ['pv-blocks-suite/stat-item', { value: 500, suffix: '+', label: __('Happy Clients', 'pv-blocks-suite') }],
    ['pv-blocks-suite/stat-item', { value: 20, suffix: '', label: __('Years in Business', 'pv-blocks-suite') }],
    ['pv-blocks-suite/stat-item', { value: 99, suffix: '%', label: __('Satisfaction Rate', 'pv-blocks-suite') }],
];

export default function Edit({
    attributes,
    setAttributes,
}: BlockEditProps<StatsCounterAttributes>) {
    const { columns, animationDuration } = attributes;

    // useBlockProps() (from the untyped @wordpress/block-editor module,
    // see src/global.d.ts) accepts arbitrary style objects, so a CSS
    // custom property key here doesn't need a cast the way it would
    // against React's own stricter CSSProperties type.
    const blockProps = useBlockProps({
        style: {
            '--stats-counter-columns': columns,
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
                <PanelBody title={__('Animation', 'pv-blocks-suite')}>
                    <RangeControl
                        label={__('Animation duration (ms)', 'pv-blocks-suite')}
                        help={__(
                            'How long each number takes to count up once it scrolls into view.',
                            'pv-blocks-suite'
                        )}
                        value={animationDuration}
                        onChange={(value?: number) =>
                            setAttributes({ animationDuration: value ?? 2000 })
                        }
                        min={500}
                        max={5000}
                        step={100}
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

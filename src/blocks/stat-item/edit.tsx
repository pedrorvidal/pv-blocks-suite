import type { BlockEditProps } from '@wordpress/blocks';
import { useBlockProps, InspectorControls, RichText } from '@wordpress/block-editor';
import { PanelBody, TextControl, RangeControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import type { StatItemAttributes } from './types';

type BorderRadius = NonNullable<
    NonNullable<StatItemAttributes['style']>['border']
>['radius'];

// Same "either a single linked value or a per-corner object once unlinked"
// check `cta`/`card`/`before-after`/`testimonial-item` already use for
// their own native border-radius support.
function hasVisibleBorderRadius(radius: BorderRadius): boolean {
    if (!radius) {
        return false;
    }

    if (typeof radius === 'string') {
        return radius.trim() !== '';
    }

    return Object.values(radius).some(
        (value) => typeof value === 'string' && value.trim() !== ''
    );
}

// Mirrors render.php's/view.ts's formatting exactly, but with no
// animation: the Interactivity API only hydrates on the real front end,
// not the block-editor canvas (same reasoning as before-after's static
// editor preview), so the editor just shows the final value directly.
function formatValue(value: number, decimals: number, prefix: string, suffix: string): string {
    const formatted = value.toLocaleString('en-US', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    });

    return `${prefix}${formatted}${suffix}`;
}

export default function Edit({
    attributes,
    setAttributes,
}: BlockEditProps<StatItemAttributes>) {
    const { value, decimals, prefix, suffix, label } = attributes;

    const hasBorderRadius = hasVisibleBorderRadius(
        attributes.style?.border?.radius
    );

    const blockProps = useBlockProps({
        style: {
            overflow: hasBorderRadius ? 'hidden' : undefined,
        },
    });

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Value', 'pv-blocks-suite')}>
                    <TextControl
                        label={__('Value', 'pv-blocks-suite')}
                        type="number"
                        value={String(value)}
                        onChange={(newValue: string) =>
                            setAttributes({ value: parseFloat(newValue) || 0 })
                        }
                    />
                    <RangeControl
                        label={__('Decimal places', 'pv-blocks-suite')}
                        value={decimals}
                        onChange={(newValue?: number) =>
                            setAttributes({ decimals: newValue ?? 0 })
                        }
                        min={0}
                        max={2}
                    />
                    <TextControl
                        label={__('Prefix', 'pv-blocks-suite')}
                        help={__('e.g. "$"', 'pv-blocks-suite')}
                        value={prefix}
                        onChange={(newValue: string) =>
                            setAttributes({ prefix: newValue })
                        }
                    />
                    <TextControl
                        label={__('Suffix', 'pv-blocks-suite')}
                        help={__('e.g. "+", "%", "k"', 'pv-blocks-suite')}
                        value={suffix}
                        onChange={(newValue: string) =>
                            setAttributes({ suffix: newValue })
                        }
                    />
                </PanelBody>
            </InspectorControls>

            <div {...blockProps}>
                <span className="wp-block-pv-blocks-suite-stat-item__value">
                    {formatValue(value, decimals, prefix, suffix)}
                </span>
                <RichText
                    tagName="p"
                    className="wp-block-pv-blocks-suite-stat-item__label"
                    value={label}
                    onChange={(newValue: string) =>
                        setAttributes({ label: newValue })
                    }
                    placeholder={__('Add a label…', 'pv-blocks-suite')}
                    allowedFormats={[]}
                />
            </div>
        </>
    );
}

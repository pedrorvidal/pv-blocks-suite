import type { BlockEditProps } from '@wordpress/blocks';
import { useBlockProps, InspectorControls, RichText } from '@wordpress/block-editor';
import { PanelBody, SelectControl, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import type { AlertBoxAttributes, AlertVariant } from './types';
import { VARIANT_ICONS, VARIANT_LABELS } from './icons';

export default function Edit({
    attributes,
    setAttributes,
}: BlockEditProps<AlertBoxAttributes>) {
    const { variant, heading, message, showIcon } = attributes;

    const blockProps = useBlockProps({
        className: `is-${variant}`,
    });

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Alert settings', 'pv-blocks-suite')}>
                    <SelectControl
                        label={__('Type', 'pv-blocks-suite')}
                        value={variant}
                        options={[
                            {
                                label: __('Info', 'pv-blocks-suite'),
                                value: 'info',
                            },
                            {
                                label: __('Success', 'pv-blocks-suite'),
                                value: 'success',
                            },
                            {
                                label: __('Warning', 'pv-blocks-suite'),
                                value: 'warning',
                            },
                            {
                                label: __('Error', 'pv-blocks-suite'),
                                value: 'error',
                            },
                        ]}
                        onChange={(value: string) =>
                            setAttributes({
                                variant: value as AlertVariant,
                            })
                        }
                    />
                    <ToggleControl
                        label={__('Show icon', 'pv-blocks-suite')}
                        checked={showIcon}
                        onChange={(value: boolean) =>
                            setAttributes({ showIcon: value })
                        }
                    />
                </PanelBody>
            </InspectorControls>

            <div {...blockProps}>
                {showIcon && (
                    <span className="wp-block-pv-blocks-suite-alert-box__icon">
                        {VARIANT_ICONS[variant]}
                    </span>
                )}

                {/* Visually hidden: sighted users get the alert's type
                    from its color/icon, screen-reader users get the same
                    information from this text instead — see the note in
                    render.php for the front-end equivalent. */}
                <span className="wp-block-pv-blocks-suite-alert-box__visually-hidden">
                    {VARIANT_LABELS[variant]}
                    {': '}
                </span>

                <div className="wp-block-pv-blocks-suite-alert-box__content">
                    <RichText
                        tagName="strong"
                        className="wp-block-pv-blocks-suite-alert-box__heading"
                        value={heading}
                        onChange={(value: string) =>
                            setAttributes({ heading: value })
                        }
                        placeholder={__(
                            'Heading (optional)…',
                            'pv-blocks-suite'
                        )}
                        allowedFormats={[]}
                    />
                    <RichText
                        tagName="p"
                        className="wp-block-pv-blocks-suite-alert-box__message"
                        value={message}
                        onChange={(value: string) =>
                            setAttributes({ message: value })
                        }
                        placeholder={__(
                            'Add your message…',
                            'pv-blocks-suite'
                        )}
                    />
                </div>
            </div>
        </>
    );
}

import type { BlockEditProps } from '@wordpress/blocks';
import { createElement } from '@wordpress/element';
import {
    useBlockProps,
    InspectorControls,
    BlockControls,
    HeadingLevelDropdown,
    InnerBlocks,
    RichText,
} from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import type { PricingPlanAttributes } from './types';

export default function Edit({
    attributes,
    setAttributes,
}: BlockEditProps<PricingPlanAttributes>) {
    const {
        planName,
        headingLevel,
        price,
        pricePeriod,
        description,
        buttonText,
        buttonUrl,
        buttonOpensInNewTab,
        isFeatured,
        featuredLabel,
    } = attributes;

    const blockProps = useBlockProps({
        className: isFeatured ? 'is-featured' : undefined,
    });

    return (
        <>
            <BlockControls>
                <HeadingLevelDropdown
                    value={headingLevel}
                    options={[2, 3, 4, 5, 6]}
                    onChange={(level: number) =>
                        setAttributes({ headingLevel: level })
                    }
                />
            </BlockControls>

            <InspectorControls>
                <PanelBody title={__('Highlight', 'pv-blocks-suite')}>
                    <ToggleControl
                        label={__('Featured plan', 'pv-blocks-suite')}
                        help={__(
                            'Visually highlights this plan among the others.',
                            'pv-blocks-suite'
                        )}
                        checked={isFeatured}
                        onChange={(value: boolean) =>
                            setAttributes({ isFeatured: value })
                        }
                    />
                    {isFeatured && (
                        <TextControl
                            label={__('Badge label', 'pv-blocks-suite')}
                            value={featuredLabel}
                            onChange={(value: string) =>
                                setAttributes({ featuredLabel: value })
                            }
                        />
                    )}
                </PanelBody>

                <PanelBody title={__('Button', 'pv-blocks-suite')}>
                    <TextControl
                        label={__('Button URL', 'pv-blocks-suite')}
                        type="url"
                        value={buttonUrl}
                        onChange={(value: string) =>
                            setAttributes({ buttonUrl: value })
                        }
                    />
                    <ToggleControl
                        label={__('Open in new tab', 'pv-blocks-suite')}
                        checked={buttonOpensInNewTab}
                        onChange={(value: boolean) =>
                            setAttributes({ buttonOpensInNewTab: value })
                        }
                    />
                </PanelBody>
            </InspectorControls>

            <div {...blockProps}>
                {isFeatured && (
                    <span className="wp-block-pv-blocks-suite-pricing-plan__featured-badge">
                        {featuredLabel}
                    </span>
                )}

                {createElement(
                    `h${headingLevel}`,
                    {
                        className:
                            'wp-block-pv-blocks-suite-pricing-plan__plan-name',
                    },
                    <RichText
                        tagName="span"
                        value={planName}
                        onChange={(value: string) =>
                            setAttributes({ planName: value })
                        }
                        placeholder={__('Plan name', 'pv-blocks-suite')}
                        allowedFormats={[]}
                    />
                )}

                <div className="wp-block-pv-blocks-suite-pricing-plan__price">
                    <RichText
                        tagName="span"
                        className="wp-block-pv-blocks-suite-pricing-plan__price-amount"
                        value={price}
                        onChange={(value: string) =>
                            setAttributes({ price: value })
                        }
                        placeholder={__('$0', 'pv-blocks-suite')}
                        allowedFormats={[]}
                    />
                    <RichText
                        tagName="span"
                        className="wp-block-pv-blocks-suite-pricing-plan__price-period"
                        value={pricePeriod}
                        onChange={(value: string) =>
                            setAttributes({ pricePeriod: value })
                        }
                        placeholder={__('/month', 'pv-blocks-suite')}
                        allowedFormats={[]}
                    />
                </div>

                <RichText
                    tagName="p"
                    className="wp-block-pv-blocks-suite-pricing-plan__description"
                    value={description}
                    onChange={(value: string) =>
                        setAttributes({ description: value })
                    }
                    placeholder={__(
                        'Add a short description…',
                        'pv-blocks-suite'
                    )}
                />

                <div className="wp-block-pv-blocks-suite-pricing-plan__features">
                    <InnerBlocks />
                </div>

                <RichText
                    tagName="span"
                    className="wp-block-pv-blocks-suite-pricing-plan__button"
                    value={buttonText}
                    onChange={(value: string) =>
                        setAttributes({ buttonText: value })
                    }
                    placeholder={__('Add button text…', 'pv-blocks-suite')}
                    allowedFormats={[]}
                />
            </div>
        </>
    );
}

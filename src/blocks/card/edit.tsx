import type { BlockEditProps } from '@wordpress/blocks';
import { createElement } from '@wordpress/element';
import {
    useBlockProps,
    InspectorControls,
    BlockControls,
    HeadingLevelDropdown,
    RichText,
    MediaUpload,
    MediaUploadCheck,
} from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import type { CardAttributes } from './types';

interface MediaUploadSelection {
    url: string;
    alt: string;
}

type BorderRadius = NonNullable<
    NonNullable<CardAttributes['style']>['border']
>['radius'];

// The native border-radius Inspector control (`supports.border.radius`)
// stores either a single linked value or a per-corner object once a user
// unlinks the corners — either way, any non-empty value means content
// (namely the image) should be clipped to the rounded corners. Same
// helper as cta/edit.tsx.
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

export default function Edit({
    attributes,
    setAttributes,
}: BlockEditProps<CardAttributes>) {
    const {
        imageUrl,
        imageAlt,
        heading,
        headingLevel,
        description,
        buttonText,
        buttonUrl,
        buttonOpensInNewTab,
    } = attributes;

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
                <PanelBody title={__('Image', 'pv-blocks-suite')}>
                    <MediaUploadCheck>
                        <MediaUpload
                            onSelect={(media: MediaUploadSelection) =>
                                setAttributes({
                                    imageUrl: media.url,
                                    imageAlt: media.alt || imageAlt,
                                })
                            }
                            allowedTypes={['image']}
                            value={imageUrl}
                            render={({ open }: { open: () => void }) => (
                                <Button variant="secondary" onClick={open}>
                                    {imageUrl
                                        ? __('Replace image', 'pv-blocks-suite')
                                        : __('Select image', 'pv-blocks-suite')}
                                </Button>
                            )}
                        />
                    </MediaUploadCheck>
                    {imageUrl && (
                        <>
                            <Button
                                variant="link"
                                isDestructive
                                onClick={() =>
                                    setAttributes({ imageUrl: '', imageAlt: '' })
                                }
                            >
                                {__('Remove image', 'pv-blocks-suite')}
                            </Button>
                            <TextControl
                                label={__('Alt text', 'pv-blocks-suite')}
                                help={__(
                                    'Describe the image for screen readers. Leave empty only if the image is purely decorative.',
                                    'pv-blocks-suite'
                                )}
                                value={imageAlt}
                                onChange={(value: string) =>
                                    setAttributes({ imageAlt: value })
                                }
                            />
                        </>
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
                {imageUrl && (
                    <img
                        className="wp-block-pv-blocks-suite-card__image"
                        src={imageUrl}
                        alt={imageAlt}
                    />
                )}

                {createElement(
                    `h${headingLevel}`,
                    {
                        className: 'wp-block-pv-blocks-suite-card__heading',
                    },
                    <RichText
                        tagName="span"
                        value={heading}
                        onChange={(value: string) =>
                            setAttributes({ heading: value })
                        }
                        placeholder={__('Card title', 'pv-blocks-suite')}
                        allowedFormats={[]}
                    />
                )}

                <RichText
                    tagName="p"
                    className="wp-block-pv-blocks-suite-card__description"
                    value={description}
                    onChange={(value: string) =>
                        setAttributes({ description: value })
                    }
                    placeholder={__(
                        'Add a short description…',
                        'pv-blocks-suite'
                    )}
                />

                <RichText
                    tagName="span"
                    className="wp-block-pv-blocks-suite-card__button"
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

import type { BlockEditProps } from '@wordpress/blocks';
import {
    useBlockProps,
    InspectorControls,
    BlockControls,
    AlignmentControl,
    HeadingLevelDropdown,
    RichText,
    PanelColorSettings,
    ContrastChecker,
    MediaUpload,
    MediaUploadCheck,
} from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import type { CtaAttributes } from './types';

interface MediaUploadSelection {
    url: string;
}

type BorderRadius = NonNullable<
    NonNullable<CtaAttributes['style']>['border']
>['radius'];

// The native border-radius Inspector control (`supports.border.radius`)
// stores either a single linked value or a per-corner object once a user
// unlinks the corners — either way, any non-empty value means content
// (namely the background image) should be clipped to the rounded corners.
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
}: BlockEditProps<CtaAttributes>) {
    const {
        heading,
        headingLevel,
        description,
        buttonText,
        buttonUrl,
        buttonOpensInNewTab,
        textAlign,
        backgroundColor,
        backgroundImage,
        textColor,
        buttonBackgroundColor,
        buttonTextColor,
    } = attributes;

    const hasBorderRadius = hasVisibleBorderRadius(
        attributes.style?.border?.radius
    );

    const blockProps = useBlockProps({
        style: {
            textAlign: textAlign || undefined,
            color: textColor || undefined,
            backgroundColor: backgroundColor || undefined,
            backgroundImage: backgroundImage
                ? `url(${backgroundImage})`
                : undefined,
            overflow: hasBorderRadius ? 'hidden' : undefined,
        },
    });

    return (
        <>
            <BlockControls>
                <AlignmentControl
                    value={textAlign}
                    onChange={(value?: string) =>
                        setAttributes({ textAlign: value ?? 'center' })
                    }
                />
                <HeadingLevelDropdown
                    value={headingLevel}
                    options={[2, 3, 4, 5, 6]}
                    onChange={(level: number) =>
                        setAttributes({ headingLevel: level })
                    }
                />
            </BlockControls>

            <InspectorControls>
                <PanelBody title={__('Background image', 'pv-blocks-suite')}>
                    <MediaUploadCheck>
                        <MediaUpload
                            onSelect={(media: MediaUploadSelection) =>
                                setAttributes({ backgroundImage: media.url })
                            }
                            allowedTypes={['image']}
                            value={backgroundImage}
                            render={({ open }: { open: () => void }) => (
                                <Button variant="secondary" onClick={open}>
                                    {backgroundImage
                                        ? __('Replace image', 'pv-blocks-suite')
                                        : __('Select image', 'pv-blocks-suite')}
                                </Button>
                            )}
                        />
                    </MediaUploadCheck>
                    {backgroundImage && (
                        <Button
                            variant="link"
                            isDestructive
                            onClick={() =>
                                setAttributes({ backgroundImage: '' })
                            }
                        >
                            {__('Remove image', 'pv-blocks-suite')}
                        </Button>
                    )}
                </PanelBody>

                <PanelColorSettings
                    title={__('Color settings', 'pv-blocks-suite')}
                    colorSettings={[
                        {
                            value: backgroundColor,
                            onChange: (value?: string) =>
                                setAttributes({
                                    backgroundColor: value ?? '',
                                }),
                            label: __('Background color', 'pv-blocks-suite'),
                        },
                        {
                            value: textColor,
                            onChange: (value?: string) =>
                                setAttributes({ textColor: value ?? '' }),
                            label: __('Text color', 'pv-blocks-suite'),
                        },
                        {
                            value: buttonBackgroundColor,
                            onChange: (value?: string) =>
                                setAttributes({
                                    buttonBackgroundColor: value ?? '',
                                }),
                            label: __(
                                'Button background color',
                                'pv-blocks-suite'
                            ),
                        },
                        {
                            value: buttonTextColor,
                            onChange: (value?: string) =>
                                setAttributes({
                                    buttonTextColor: value ?? '',
                                }),
                            label: __('Button text color', 'pv-blocks-suite'),
                        },
                    ]}
                >
                    <ContrastChecker
                        backgroundColor={backgroundColor}
                        textColor={textColor}
                    />
                    <ContrastChecker
                        backgroundColor={buttonBackgroundColor}
                        textColor={buttonTextColor}
                    />
                </PanelColorSettings>

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
                <RichText
                    tagName={`h${headingLevel}`}
                    className="wp-block-pv-blocks-suite-cta__heading"
                    value={heading}
                    onChange={(value: string) =>
                        setAttributes({ heading: value })
                    }
                    placeholder={__('Add heading…', 'pv-blocks-suite')}
                />
                <RichText
                    tagName="p"
                    className="wp-block-pv-blocks-suite-cta__description"
                    value={description}
                    onChange={(value: string) =>
                        setAttributes({ description: value })
                    }
                    placeholder={__('Add description…', 'pv-blocks-suite')}
                />
                <RichText
                    tagName="span"
                    className="wp-block-pv-blocks-suite-cta__button"
                    style={{
                        backgroundColor: buttonBackgroundColor || undefined,
                        color: buttonTextColor || undefined,
                    }}
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

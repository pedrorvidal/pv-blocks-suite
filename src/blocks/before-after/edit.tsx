import type { BlockEditProps } from '@wordpress/blocks';
import {
    useBlockProps,
    InspectorControls,
    RichText,
    MediaUpload,
    MediaUploadCheck,
} from '@wordpress/block-editor';
import {
    PanelBody,
    TextControl,
    ToggleControl,
    RangeControl,
    SelectControl,
    Button,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import type { BeforeAfterAttributes } from './types';

interface MediaUploadSelection {
    url: string;
    alt: string;
}

type BorderRadius = NonNullable<
    NonNullable<BeforeAfterAttributes['style']>['border']
>['radius'];

// Same "either a single linked value or a per-corner object once unlinked"
// check `cta`/`card` already use for their own native border-radius support.
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

// Mirrors the lookup table in render.php/view.ts — no cross-runtime
// sharing mechanism exists yet in this project (same precedent as
// `alert-box`'s SVG icons being duplicated between icons.tsx and render.php).
const ASPECT_RATIO_OPTIONS: { label: string; value: string }[] = [
    { label: __('Square (1:1)', 'pv-blocks-suite'), value: '1:1' },
    { label: __('Standard (4:3)', 'pv-blocks-suite'), value: '4:3' },
    { label: __('Widescreen (16:9)', 'pv-blocks-suite'), value: '16:9' },
    { label: __('Ultrawide (21:9)', 'pv-blocks-suite'), value: '21:9' },
    { label: __('Photo (3:2)', 'pv-blocks-suite'), value: '3:2' },
];

const ASPECT_RATIO_CSS: Record<string, string> = {
    '1:1': '1 / 1',
    '4:3': '4 / 3',
    '16:9': '16 / 9',
    '21:9': '21 / 9',
    '3:2': '3 / 2',
};

export default function Edit({
    attributes,
    setAttributes,
}: BlockEditProps<BeforeAfterAttributes>) {
    const {
        beforeImageUrl,
        beforeImageAlt,
        afterImageUrl,
        afterImageAlt,
        beforeLabel,
        afterLabel,
        showLabels,
        initialPosition,
        aspectRatio,
    } = attributes;

    const hasBorderRadius = hasVisibleBorderRadius(
        attributes.style?.border?.radius
    );

    const blockProps = useBlockProps({
        style: {
            overflow: hasBorderRadius ? 'hidden' : undefined,
        },
    });

    const aspectRatioCss =
        ASPECT_RATIO_CSS[aspectRatio] ?? ASPECT_RATIO_CSS['16:9'];

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Before image', 'pv-blocks-suite')}>
                    <MediaUploadCheck>
                        <MediaUpload
                            onSelect={(media: MediaUploadSelection) =>
                                setAttributes({
                                    beforeImageUrl: media.url,
                                    beforeImageAlt: media.alt || beforeImageAlt,
                                })
                            }
                            allowedTypes={['image']}
                            value={beforeImageUrl}
                            render={({ open }: { open: () => void }) => (
                                <Button variant="secondary" onClick={open}>
                                    {beforeImageUrl
                                        ? __('Replace image', 'pv-blocks-suite')
                                        : __('Select image', 'pv-blocks-suite')}
                                </Button>
                            )}
                        />
                    </MediaUploadCheck>
                    {beforeImageUrl && (
                        <>
                            <Button
                                variant="link"
                                isDestructive
                                onClick={() =>
                                    setAttributes({
                                        beforeImageUrl: '',
                                        beforeImageAlt: '',
                                    })
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
                                value={beforeImageAlt}
                                onChange={(value: string) =>
                                    setAttributes({ beforeImageAlt: value })
                                }
                            />
                        </>
                    )}
                </PanelBody>

                <PanelBody title={__('After image', 'pv-blocks-suite')}>
                    <MediaUploadCheck>
                        <MediaUpload
                            onSelect={(media: MediaUploadSelection) =>
                                setAttributes({
                                    afterImageUrl: media.url,
                                    afterImageAlt: media.alt || afterImageAlt,
                                })
                            }
                            allowedTypes={['image']}
                            value={afterImageUrl}
                            render={({ open }: { open: () => void }) => (
                                <Button variant="secondary" onClick={open}>
                                    {afterImageUrl
                                        ? __('Replace image', 'pv-blocks-suite')
                                        : __('Select image', 'pv-blocks-suite')}
                                </Button>
                            )}
                        />
                    </MediaUploadCheck>
                    {afterImageUrl && (
                        <>
                            <Button
                                variant="link"
                                isDestructive
                                onClick={() =>
                                    setAttributes({
                                        afterImageUrl: '',
                                        afterImageAlt: '',
                                    })
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
                                value={afterImageAlt}
                                onChange={(value: string) =>
                                    setAttributes({ afterImageAlt: value })
                                }
                            />
                        </>
                    )}
                </PanelBody>

                <PanelBody title={__('Settings', 'pv-blocks-suite')}>
                    <RangeControl
                        label={__('Initial handle position', 'pv-blocks-suite')}
                        min={0}
                        max={100}
                        value={initialPosition}
                        onChange={(value?: number) =>
                            setAttributes({ initialPosition: value ?? 50 })
                        }
                    />
                    <SelectControl
                        label={__('Aspect ratio', 'pv-blocks-suite')}
                        value={aspectRatio}
                        options={ASPECT_RATIO_OPTIONS}
                        onChange={(value: string) =>
                            setAttributes({ aspectRatio: value })
                        }
                    />
                    <ToggleControl
                        label={__('Show labels', 'pv-blocks-suite')}
                        checked={showLabels}
                        onChange={(value: boolean) =>
                            setAttributes({ showLabels: value })
                        }
                    />
                </PanelBody>
            </InspectorControls>

            <div {...blockProps}>
                <div
                    className="wp-block-pv-blocks-suite-before-after__stage"
                    style={{ aspectRatio: aspectRatioCss }}
                >
                    {beforeImageUrl && (
                        <img
                            className="wp-block-pv-blocks-suite-before-after__image wp-block-pv-blocks-suite-before-after__image--before"
                            src={beforeImageUrl}
                            alt={beforeImageAlt}
                        />
                    )}
                    <div
                        className="wp-block-pv-blocks-suite-before-after__after-wrap"
                        style={{
                            clipPath: `inset(0 ${100 - initialPosition}% 0 0)`,
                        }}
                    >
                        {afterImageUrl && (
                            <img
                                className="wp-block-pv-blocks-suite-before-after__image wp-block-pv-blocks-suite-before-after__image--after"
                                src={afterImageUrl}
                                alt={afterImageAlt}
                            />
                        )}
                    </div>

                    {showLabels && (
                        <>
                            <RichText
                                tagName="span"
                                className="wp-block-pv-blocks-suite-before-after__label wp-block-pv-blocks-suite-before-after__label--before"
                                value={beforeLabel}
                                onChange={(value: string) =>
                                    setAttributes({ beforeLabel: value })
                                }
                                allowedFormats={[]}
                            />
                            <RichText
                                tagName="span"
                                className="wp-block-pv-blocks-suite-before-after__label wp-block-pv-blocks-suite-before-after__label--after"
                                value={afterLabel}
                                onChange={(value: string) =>
                                    setAttributes({ afterLabel: value })
                                }
                                allowedFormats={[]}
                            />
                        </>
                    )}

                    <div
                        className="wp-block-pv-blocks-suite-before-after__handle"
                        style={{ left: `${initialPosition}%` }}
                    >
                        <span
                            className="wp-block-pv-blocks-suite-before-after__handle-grip"
                            aria-hidden="true"
                        />
                    </div>
                </div>
            </div>
        </>
    );
}

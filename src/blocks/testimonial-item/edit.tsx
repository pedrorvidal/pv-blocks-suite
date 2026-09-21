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
    RangeControl,
    ToggleControl,
    Button,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import type { TestimonialItemAttributes } from './types';
import { StarFilled, StarEmpty } from './icons';

interface MediaUploadSelection {
    url: string;
    alt: string;
}

type BorderRadius = NonNullable<
    NonNullable<TestimonialItemAttributes['style']>['border']
>['radius'];

// The native border-radius Inspector control (`supports.border.radius`)
// stores either a single linked value or a per-corner object once a user
// unlinks the corners — either way, any non-empty value means content
// should be clipped to the rounded corners. Same helper as cta/card's
// edit.tsx.
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
}: BlockEditProps<TestimonialItemAttributes>) {
    const {
        avatarUrl,
        avatarAlt,
        quote,
        name,
        role,
        rating,
        showRating,
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
            <InspectorControls>
                <PanelBody title={__('Photo', 'pv-blocks-suite')}>
                    <MediaUploadCheck>
                        <MediaUpload
                            onSelect={(media: MediaUploadSelection) =>
                                setAttributes({
                                    avatarUrl: media.url,
                                    avatarAlt: media.alt || avatarAlt,
                                })
                            }
                            allowedTypes={['image']}
                            value={avatarUrl}
                            render={({ open }: { open: () => void }) => (
                                <Button variant="secondary" onClick={open}>
                                    {avatarUrl
                                        ? __('Replace image', 'pv-blocks-suite')
                                        : __('Select image', 'pv-blocks-suite')}
                                </Button>
                            )}
                        />
                    </MediaUploadCheck>
                    {avatarUrl && (
                        <>
                            <Button
                                variant="link"
                                isDestructive
                                onClick={() =>
                                    setAttributes({ avatarUrl: '', avatarAlt: '' })
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
                                value={avatarAlt}
                                onChange={(value: string) =>
                                    setAttributes({ avatarAlt: value })
                                }
                            />
                        </>
                    )}
                </PanelBody>

                <PanelBody title={__('Author', 'pv-blocks-suite')}>
                    <TextControl
                        label={__('Role', 'pv-blocks-suite')}
                        help={__(
                            'Job title or company, e.g. "CEO, Acme Inc."',
                            'pv-blocks-suite'
                        )}
                        value={role}
                        onChange={(value: string) =>
                            setAttributes({ role: value })
                        }
                    />
                </PanelBody>

                <PanelBody title={__('Rating', 'pv-blocks-suite')}>
                    <ToggleControl
                        label={__('Show rating', 'pv-blocks-suite')}
                        checked={showRating}
                        onChange={(value: boolean) =>
                            setAttributes({ showRating: value })
                        }
                    />
                    {showRating && (
                        <RangeControl
                            label={__('Stars', 'pv-blocks-suite')}
                            value={rating}
                            onChange={(value?: number) =>
                                setAttributes({ rating: value ?? 5 })
                            }
                            min={1}
                            max={5}
                        />
                    )}
                </PanelBody>
            </InspectorControls>

            <div {...blockProps}>
                {avatarUrl && (
                    <img
                        className="wp-block-pv-blocks-suite-testimonial-item__avatar"
                        src={avatarUrl}
                        alt={avatarAlt}
                    />
                )}

                <RichText
                    tagName="blockquote"
                    className="wp-block-pv-blocks-suite-testimonial-item__quote"
                    value={quote}
                    onChange={(value: string) =>
                        setAttributes({ quote: value })
                    }
                    placeholder={__('Add a testimonial…', 'pv-blocks-suite')}
                />

                <RichText
                    tagName="cite"
                    className="wp-block-pv-blocks-suite-testimonial-item__name"
                    value={name}
                    onChange={(value: string) =>
                        setAttributes({ name: value })
                    }
                    placeholder={__('Author name', 'pv-blocks-suite')}
                    allowedFormats={[]}
                />

                {role && (
                    <span className="wp-block-pv-blocks-suite-testimonial-item__role">
                        {role}
                    </span>
                )}

                {showRating && (
                    <div
                        className="wp-block-pv-blocks-suite-testimonial-item__rating"
                        aria-hidden="true"
                    >
                        {[1, 2, 3, 4, 5].map((position) => (
                            <span
                                key={position}
                                className="wp-block-pv-blocks-suite-testimonial-item__star"
                            >
                                {position <= rating ? StarFilled : StarEmpty}
                            </span>
                        ))}
                    </div>
                )}
            </div>
        </>
    );
}

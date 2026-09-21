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
import { PanelBody, TextControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import type { TeamMemberAttributes } from './types';

interface MediaUploadSelection {
    url: string;
    alt: string;
}

type BorderRadius = NonNullable<
    NonNullable<TeamMemberAttributes['style']>['border']
>['radius'];

// The native border-radius Inspector control (`supports.border.radius`)
// stores either a single linked value or a per-corner object once a user
// unlinks the corners — either way, any non-empty value means content
// (namely the avatar) should be clipped to the rounded corners. Same
// helper as cta/card/testimonial-item's edit.tsx.
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
}: BlockEditProps<TeamMemberAttributes>) {
    const {
        avatarUrl,
        avatarAlt,
        name,
        headingLevel,
        role,
        bio,
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

                <PanelBody title={__('Role', 'pv-blocks-suite')}>
                    <TextControl
                        label={__('Role', 'pv-blocks-suite')}
                        help={__(
                            'Job title or department, e.g. "CEO & Founder"',
                            'pv-blocks-suite'
                        )}
                        value={role}
                        onChange={(value: string) =>
                            setAttributes({ role: value })
                        }
                    />
                </PanelBody>
            </InspectorControls>

            <div {...blockProps}>
                {avatarUrl && (
                    <img
                        className="wp-block-pv-blocks-suite-team-member__avatar"
                        src={avatarUrl}
                        alt={avatarAlt}
                    />
                )}

                {createElement(
                    `h${headingLevel}`,
                    {
                        className: 'wp-block-pv-blocks-suite-team-member__name',
                    },
                    <RichText
                        tagName="span"
                        value={name}
                        onChange={(value: string) =>
                            setAttributes({ name: value })
                        }
                        placeholder={__('Name', 'pv-blocks-suite')}
                        allowedFormats={[]}
                    />
                )}

                {role && (
                    <p className="wp-block-pv-blocks-suite-team-member__role">
                        {role}
                    </p>
                )}

                <RichText
                    tagName="p"
                    className="wp-block-pv-blocks-suite-team-member__bio"
                    value={bio}
                    onChange={(value: string) =>
                        setAttributes({ bio: value })
                    }
                    placeholder={__('Add a short bio…', 'pv-blocks-suite')}
                />
            </div>
        </>
    );
}

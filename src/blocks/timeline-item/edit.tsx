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

import type { TimelineItemAttributes } from './types';

interface MediaUploadSelection {
    url: string;
    alt: string;
}

export default function Edit({
    attributes,
    setAttributes,
}: BlockEditProps<TimelineItemAttributes>) {
    const { imageUrl, imageAlt, date, heading, headingLevel, description } =
        attributes;

    const blockProps = useBlockProps();

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
                                    setAttributes({
                                        imageUrl: '',
                                        imageAlt: '',
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
                                value={imageAlt}
                                onChange={(value: string) =>
                                    setAttributes({ imageAlt: value })
                                }
                            />
                        </>
                    )}
                </PanelBody>
            </InspectorControls>

            {/* A real <li>, not a <div> — matches render.php and keeps
                the parent <ol>'s list semantics intact. */}
            <li {...blockProps}>
                <span className="wp-block-pv-blocks-suite-timeline-item__dot" />
                <div className="wp-block-pv-blocks-suite-timeline-item__content">
                    {imageUrl && (
                        <img
                            className="wp-block-pv-blocks-suite-timeline-item__image"
                            src={imageUrl}
                            alt={imageAlt}
                        />
                    )}

                    <RichText
                        tagName="span"
                        className="wp-block-pv-blocks-suite-timeline-item__date"
                        value={date}
                        onChange={(value: string) =>
                            setAttributes({ date: value })
                        }
                        placeholder={__(
                            'Date or label…',
                            'pv-blocks-suite'
                        )}
                        allowedFormats={[]}
                    />

                    {createElement(
                        `h${headingLevel}`,
                        {
                            className:
                                'wp-block-pv-blocks-suite-timeline-item__heading',
                        },
                        <RichText
                            tagName="span"
                            value={heading}
                            onChange={(value: string) =>
                                setAttributes({ heading: value })
                            }
                            placeholder={__('Title', 'pv-blocks-suite')}
                            allowedFormats={[]}
                        />
                    )}

                    <RichText
                        tagName="p"
                        className="wp-block-pv-blocks-suite-timeline-item__description"
                        value={description}
                        onChange={(value: string) =>
                            setAttributes({ description: value })
                        }
                        placeholder={__(
                            'Add a short description…',
                            'pv-blocks-suite'
                        )}
                    />
                </div>
            </li>
        </>
    );
}

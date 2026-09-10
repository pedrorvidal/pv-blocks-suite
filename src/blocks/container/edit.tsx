import type { BlockEditProps } from '@wordpress/blocks';
import {
    useBlockProps,
    InspectorControls,
    InnerBlocks,
    PanelColorSettings,
    MediaUpload,
    MediaUploadCheck,
} from '@wordpress/block-editor';
import {
    PanelBody,
    // eslint-disable-next-line @wordpress/no-unsafe-wp-apis -- no stable
    // non-experimental unit input exists yet for arbitrary CSS length values.
    __experimentalUnitControl as UnitControl,
    TextControl,
    Button,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

interface ContainerAttributes {
    paddingTop: string;
    paddingBottom: string;
    paddingLeft: string;
    paddingRight: string;
    backgroundColor: string;
    backgroundImage: string;
    maxWidth: string;
}

interface MediaUploadSelection {
    url: string;
}

export default function Edit({
    attributes,
    setAttributes,
}: BlockEditProps<ContainerAttributes>) {
    const {
        paddingTop,
        paddingBottom,
        paddingLeft,
        paddingRight,
        backgroundColor,
        backgroundImage,
        maxWidth,
    } = attributes;

    const blockProps = useBlockProps({
        style: {
            paddingTop: paddingTop || undefined,
            paddingBottom: paddingBottom || undefined,
            paddingLeft: paddingLeft || undefined,
            paddingRight: paddingRight || undefined,
            backgroundColor: backgroundColor || undefined,
            backgroundImage: backgroundImage
                ? `url(${backgroundImage})`
                : undefined,
            maxWidth: maxWidth || undefined,
            marginLeft: maxWidth ? 'auto' : undefined,
            marginRight: maxWidth ? 'auto' : undefined,
        },
    });

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Spacing', 'pv-blocks-suite')}>
                    <UnitControl
                        label={__('Padding top', 'pv-blocks-suite')}
                        value={paddingTop}
                        onChange={(value?: string) =>
                            setAttributes({ paddingTop: value ?? '' })
                        }
                    />
                    <UnitControl
                        label={__('Padding bottom', 'pv-blocks-suite')}
                        value={paddingBottom}
                        onChange={(value?: string) =>
                            setAttributes({ paddingBottom: value ?? '' })
                        }
                    />
                    <UnitControl
                        label={__('Padding left', 'pv-blocks-suite')}
                        value={paddingLeft}
                        onChange={(value?: string) =>
                            setAttributes({ paddingLeft: value ?? '' })
                        }
                    />
                    <UnitControl
                        label={__('Padding right', 'pv-blocks-suite')}
                        value={paddingRight}
                        onChange={(value?: string) =>
                            setAttributes({ paddingRight: value ?? '' })
                        }
                    />
                </PanelBody>

                <PanelColorSettings
                    title={__('Background color', 'pv-blocks-suite')}
                    colorSettings={[
                        {
                            value: backgroundColor,
                            onChange: (value?: string) =>
                                setAttributes({
                                    backgroundColor: value ?? '',
                                }),
                            label: __('Background color', 'pv-blocks-suite'),
                        },
                    ]}
                />

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

                <PanelBody title={__('Width', 'pv-blocks-suite')}>
                    <TextControl
                        label={__('Max width', 'pv-blocks-suite')}
                        help={__('e.g. 1200px, 80rem, none', 'pv-blocks-suite')}
                        value={maxWidth}
                        onChange={(value: string) =>
                            setAttributes({ maxWidth: value })
                        }
                    />
                </PanelBody>
            </InspectorControls>

            <div {...blockProps}>
                <InnerBlocks />
            </div>
        </>
    );
}

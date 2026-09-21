import type { BlockEditProps } from '@wordpress/blocks';
import { createElement } from '@wordpress/element';
import {
    useBlockProps,
    BlockControls,
    HeadingLevelDropdown,
    RichText,
} from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import type { TimelineItemAttributes } from './types';

export default function Edit({
    attributes,
    setAttributes,
}: BlockEditProps<TimelineItemAttributes>) {
    const { date, heading, headingLevel, description } = attributes;

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

            {/* No Inspector panel needed: every field (date, heading,
                description) is edited inline via RichText, matching the
                other content-only fields already, e.g. pricing-plan's
                price/description. */}

            {/* A real <li>, not a <div> — matches render.php and keeps
                the parent <ol>'s list semantics intact. */}
            <li {...blockProps}>
                <span className="wp-block-pv-blocks-suite-timeline-item__dot" />
                <div className="wp-block-pv-blocks-suite-timeline-item__content">
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

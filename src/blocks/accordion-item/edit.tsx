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
import { PanelBody, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import type { AccordionItemAttributes } from './types';

export default function Edit({
    attributes,
    setAttributes,
}: BlockEditProps<AccordionItemAttributes>) {
    const { summary, headingLevel, openByDefault } = attributes;

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
                <PanelBody title={__('Item settings', 'pv-blocks-suite')}>
                    <ToggleControl
                        label={__('Open by default', 'pv-blocks-suite')}
                        help={__(
                            'Whether this item starts expanded when the page loads.',
                            'pv-blocks-suite'
                        )}
                        checked={openByDefault}
                        onChange={(value: boolean) =>
                            setAttributes({ openByDefault: value })
                        }
                    />
                </PanelBody>
            </InspectorControls>

            {/* Not a real <details>/<summary> here: the native toggle
                click would fight the editor's own block-selection click
                handling, and hidden panel content can't be edited. The
                front end (render.php) is what renders the real,
                JS-free disclosure element; the editor always shows the
                panel content open so it stays editable. The question text
                is wrapped in a real heading (both here and in render.php)
                so screen-reader users can navigate the FAQ list by
                heading, not just by reading paragraph text. */}
            <div {...blockProps}>
                <div className="wp-block-pv-blocks-suite-accordion-item__summary">
                    {createElement(
                        `h${headingLevel}`,
                        {
                            className:
                                'wp-block-pv-blocks-suite-accordion-item__summary-heading',
                        },
                        <RichText
                            tagName="span"
                            value={summary}
                            onChange={(value: string) =>
                                setAttributes({ summary: value })
                            }
                            placeholder={__(
                                'Add a question…',
                                'pv-blocks-suite'
                            )}
                            allowedFormats={[]}
                        />
                    )}
                </div>
                <div className="wp-block-pv-blocks-suite-accordion-item__content">
                    <div className="wp-block-pv-blocks-suite-accordion-item__content-inner">
                        <InnerBlocks />
                    </div>
                </div>
            </div>
        </>
    );
}

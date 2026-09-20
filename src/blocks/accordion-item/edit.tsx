import type { BlockEditProps } from '@wordpress/blocks';
import {
    useBlockProps,
    InspectorControls,
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
    const { summary, openByDefault } = attributes;

    const blockProps = useBlockProps();

    return (
        <>
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
                panel content open so it stays editable. */}
            <div {...blockProps}>
                <RichText
                    tagName="div"
                    className="wp-block-pv-blocks-suite-accordion-item__summary"
                    value={summary}
                    onChange={(value: string) =>
                        setAttributes({ summary: value })
                    }
                    placeholder={__('Add a question…', 'pv-blocks-suite')}
                    allowedFormats={[]}
                />
                <div className="wp-block-pv-blocks-suite-accordion-item__content">
                    <div className="wp-block-pv-blocks-suite-accordion-item__content-inner">
                        <InnerBlocks />
                    </div>
                </div>
            </div>
        </>
    );
}

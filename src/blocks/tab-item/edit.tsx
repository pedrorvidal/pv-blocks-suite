import type { BlockEditProps } from '@wordpress/blocks';
import { useBlockProps, InnerBlocks, RichText } from '@wordpress/block-editor';
import { useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import type { TabItemAttributes } from './types';

export default function Edit({
    attributes,
    setAttributes,
    clientId,
}: BlockEditProps<TabItemAttributes>) {
    const { label, tabId } = attributes;

    // A stable, unique id is required so the parent `tabs` block can wire
    // this tab's button to its panel via aria-controls/aria-labelledby on
    // the front end (see tabs/render.php and tab-item/render.php). Same
    // clientId-persistence trick as accordion's groupId, just applied to
    // the CHILD block here instead of the parent — each tab needs its own
    // id, not one shared id for the whole set.
    useEffect(() => {
        if (!tabId) {
            setAttributes({ tabId: clientId });
        }
    }, [tabId, clientId, setAttributes]);

    const blockProps = useBlockProps();

    return (
        <div {...blockProps}>
            {/* Shown here for editing convenience, even though the front
                end renders this label somewhere else entirely — hoisted
                by the parent into a shared tablist, not left in this
                block's own DOM position (see tabs/render.php). This is a
                deliberate, common editorial simplification for tab-style
                blocks: editors expect to see/edit a tab's name right next
                to its content while building it, even though it doesn't
                reflect the final rendered structure. */}
            <RichText
                tagName="div"
                className="wp-block-pv-blocks-suite-tab-item__label"
                value={label}
                onChange={(value: string) => setAttributes({ label: value })}
                placeholder={__('Tab label…', 'pv-blocks-suite')}
                allowedFormats={[]}
            />
            <div className="wp-block-pv-blocks-suite-tab-item__panel-preview">
                <InnerBlocks />
            </div>
        </div>
    );
}

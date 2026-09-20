import type { BlockEditProps } from '@wordpress/blocks';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import type { PricingTableAttributes } from './types';

const ALLOWED_BLOCKS = ['pv-blocks-suite/pricing-plan'];

const TEMPLATE = [
    [
        'pv-blocks-suite/pricing-plan',
        {
            planName: __('Basic', 'pv-blocks-suite'),
            price: '$9',
            pricePeriod: __('/month', 'pv-blocks-suite'),
            description: __(
                'For individuals getting started.',
                'pv-blocks-suite'
            ),
            buttonText: __('Choose Basic', 'pv-blocks-suite'),
        },
        [['core/list', {}, [['core/list-item', {}]]]],
    ],
    [
        'pv-blocks-suite/pricing-plan',
        {
            planName: __('Pro', 'pv-blocks-suite'),
            price: '$29',
            pricePeriod: __('/month', 'pv-blocks-suite'),
            description: __('For growing teams.', 'pv-blocks-suite'),
            buttonText: __('Choose Pro', 'pv-blocks-suite'),
            isFeatured: true,
        },
        [['core/list', {}, [['core/list-item', {}]]]],
    ],
    [
        'pv-blocks-suite/pricing-plan',
        {
            planName: __('Enterprise', 'pv-blocks-suite'),
            price: '$99',
            pricePeriod: __('/month', 'pv-blocks-suite'),
            description: __(
                'For large organizations.',
                'pv-blocks-suite'
            ),
            buttonText: __('Contact Us', 'pv-blocks-suite'),
        },
        [['core/list', {}, [['core/list-item', {}]]]],
    ],
];

export default function Edit(_props: BlockEditProps<PricingTableAttributes>) {
    const blockProps = useBlockProps();

    return (
        <div {...blockProps}>
            <InnerBlocks
                allowedBlocks={ALLOWED_BLOCKS}
                template={TEMPLATE}
                templateLock={false}
                orientation="horizontal"
            />
        </div>
    );
}

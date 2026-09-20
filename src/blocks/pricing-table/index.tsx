import { registerBlockType, type BlockConfiguration } from '@wordpress/blocks';

import Edit from './edit';
import save from './save';
import metadata from './block.json';
import type { PricingTableAttributes } from './types';

import './style.scss';

// TypeScript's `resolveJsonModule` infers plain `string`/`number` for
// block.json's fields (e.g. `category: string`, not the literal union
// BlockConfiguration expects), so the import never structurally matches
// on its own. The JSON is correct at runtime; this just bridges that gap.
// The extra `as unknown` step is needed because this block's `example`
// nests a `pricing-plan` example with a boolean attribute
// (`isFeatured`) — see pricing-plan/index.tsx for the full explanation
// of why that specifically defeats a direct cast here.
registerBlockType(
    metadata as unknown as BlockConfiguration<PricingTableAttributes>,
    {
        edit: Edit,
        save,
    }
);

import { registerBlockType, type BlockConfiguration } from '@wordpress/blocks';

import Edit from './edit';
import save from './save';
import metadata from './block.json';
import type { PricingPlanAttributes } from './types';

import './style.scss';

// TypeScript's `resolveJsonModule` infers plain `string`/`number` for
// block.json's fields (e.g. `category: string`, not the literal union
// BlockConfiguration expects), so the import never structurally matches
// on its own. The JSON is correct at runtime; this just bridges that gap.
// The extra `as unknown` step is needed here specifically because
// `@wordpress/blocks`' own type for `example.attributes` is itself
// imprecise (it types values as `BlockAttribute`, the attribute *schema*
// shape, instead of `Partial<Attributes>`, the actual example *values*) —
// this block is the first one with a boolean example attribute
// (`isFeatured`), which is what actually triggers the mismatch; a direct
// cast fails with "insufficient overlap".
registerBlockType(
    metadata as unknown as BlockConfiguration<PricingPlanAttributes>,
    {
        edit: Edit,
        save,
    }
);

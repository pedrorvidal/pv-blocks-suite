import { registerBlockType, type BlockConfiguration } from '@wordpress/blocks';

import Edit from './edit';
import save from './save';
import metadata from './block.json';
import type { TabsAttributes } from './types';

import './style.scss';

// TypeScript's `resolveJsonModule` infers plain `string`/`number` for
// block.json's fields (e.g. `category: string`, not the literal union
// BlockConfiguration expects), so the import never structurally matches
// on its own. The JSON is correct at runtime; this just bridges that gap.
// This block also has no `attributes` key at all (it has none of its
// own), which BlockConfiguration<T> otherwise requires — going through
// `unknown` first is the same fix this project's pricing-table note
// documents for a different attributes-shape mismatch.
registerBlockType(
    metadata as unknown as BlockConfiguration<TabsAttributes>,
    {
        edit: Edit,
        save,
    }
);

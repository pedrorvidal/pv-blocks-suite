import { registerBlockType, type BlockConfiguration } from '@wordpress/blocks';

import Edit from './edit';
import save from './save';
import metadata from './block.json';
import type { CardAttributes } from './types';

import './style.scss';

// TypeScript's `resolveJsonModule` infers plain `string`/`number` for
// block.json's fields (e.g. `category: string`, not the literal union
// BlockConfiguration expects), so the import never structurally matches
// on its own. The JSON is correct at runtime; this just bridges that gap.
registerBlockType(metadata as BlockConfiguration<CardAttributes>, {
    edit: Edit,
    save,
});

<?php
/**
 * Enforces that every block this plugin registers ships a block.json
 * "example" (the source for the inserter's hover preview) — a project
 * convention agreed with Pedro on 2026-09-20, checked automatically here
 * rather than relied on as a manual checklist item for future blocks.
 *
 * @package PV\BlocksSuite
 */

declare( strict_types = 1 );

final class Block_Examples_Test extends WP_UnitTestCase {

	public function test_every_registered_block_has_an_inserter_preview_example(): void {
		$registry = WP_Block_Type_Registry::get_instance();

		foreach ( $registry->get_all_registered() as $block_type ) {
			if ( ! str_starts_with( $block_type->name, 'pv-blocks-suite/' ) ) {
				continue;
			}

			$this->assertNotEmpty(
				$block_type->example,
				sprintf( 'Block "%s" is missing a block.json "example" for its inserter hover preview.', $block_type->name )
			);
		}
	}
}

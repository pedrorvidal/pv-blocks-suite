<?php
/**
 * Tests for the `PV\BlocksSuite\Block_Categories` filter.
 *
 * @package PV\BlocksSuite
 */

declare( strict_types = 1 );

final class Block_Categories_Test extends WP_UnitTestCase {

	public function test_pv_blocks_suite_category_is_registered(): void {
		$categories = apply_filters( 'block_categories_all', get_default_block_categories() );
		$slugs      = wp_list_pluck( $categories, 'slug' );

		$this->assertContains( 'pv-blocks-suite', $slugs );
	}

	public function test_pv_blocks_suite_category_is_positioned_right_after_the_first_category(): void {
		$default_categories = get_default_block_categories();
		$categories         = apply_filters( 'block_categories_all', $default_categories );

		$this->assertSame( $default_categories[0]['slug'], $categories[0]['slug'] );
		$this->assertSame( 'pv-blocks-suite', $categories[1]['slug'] );
	}

	public function test_every_plugin_block_uses_the_pv_blocks_suite_category(): void {
		$registry = WP_Block_Type_Registry::get_instance();

		foreach ( $registry->get_all_registered() as $block_type ) {
			if ( ! str_starts_with( $block_type->name, 'pv-blocks-suite/' ) ) {
				continue;
			}

			$this->assertSame(
				'pv-blocks-suite',
				$block_type->category,
				sprintf( 'Block "%s" should use the "pv-blocks-suite" category.', $block_type->name )
			);
		}
	}
}

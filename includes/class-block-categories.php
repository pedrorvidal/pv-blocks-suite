<?php
/**
 * Registers a dedicated "PV Blocks Suite" block category so every block
 * this plugin ships is grouped together in the inserter, instead of
 * mixing into one of WordPress' built-in categories (e.g. "Design").
 *
 * @package PV\BlocksSuite
 */

declare( strict_types=1 );

namespace PV\BlocksSuite;

/**
 * Adds the `pv-blocks-suite` category via the `block_categories_all`
 * filter, positioned right after whichever category WordPress core lists
 * first (rather than appended at the end, which is where a new category
 * lands by default).
 */
final class Block_Categories {

	/**
	 * Hooks category registration into the `block_categories_all` filter.
	 */
	public static function init(): void {
		add_filter( 'block_categories_all', [ self::class, 'register_category' ] );
	}

	/**
	 * Inserts the plugin's category right after the first entry in the
	 * existing list.
	 *
	 * @param array<int, array{slug: string, title: string, icon?: string|null}> $categories Existing categories.
	 * @return array<int, array{slug: string, title: string, icon?: string|null}> Categories with ours inserted.
	 */
	public static function register_category( array $categories ): array {
		$pv_category = [
			'slug'  => 'pv-blocks-suite',
			'title' => __( 'PV Blocks Suite', 'pv-blocks-suite' ),
			'icon'  => 'layout',
		];

		array_splice( $categories, 1, 0, [ $pv_category ] );

		return $categories;
	}
}

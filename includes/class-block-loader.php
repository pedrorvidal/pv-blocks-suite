<?php
/**
 * Discovers block definitions under src/blocks/ and registers each one
 * from its compiled output in build/blocks/.
 *
 * @package PV\BlocksSuite
 */

declare( strict_types=1 );

namespace PV\BlocksSuite;

/**
 * Scans src/blocks/*\/block.json to discover blocks, then registers each
 * one using the compiled assets in the matching build/blocks/{slug}/
 * directory. Adding a new block never requires touching this class -
 * dropping a folder with a block.json under src/blocks/ is enough.
 */
final class Block_Loader {

	/**
	 * Hooks block registration into WordPress' `init` action.
	 */
	public static function init(): void {
		add_action( 'init', [ self::class, 'register_blocks' ] );
	}

	/**
	 * Discovers every block folder under src/blocks/ and registers the
	 * ones that have a matching compiled build/blocks/{slug}/block.json.
	 */
	public static function register_blocks(): void {
		$src_blocks_dir = PV_BLOCKS_SUITE_DIR . 'src/blocks';

		if ( ! is_dir( $src_blocks_dir ) ) {
			return;
		}

		$block_dirs = glob( $src_blocks_dir . '/*', GLOB_ONLYDIR );

		if ( false === $block_dirs ) {
			return;
		}

		foreach ( $block_dirs as $src_block_dir ) {
			self::maybe_register_block( $src_block_dir );
		}
	}

	/**
	 * Registers a single block if its src/ definition has a compiled
	 * counterpart under build/blocks/{slug}/, and warns in debug mode
	 * when it does not.
	 *
	 * @param string $src_block_dir Absolute path to src/blocks/{slug}.
	 */
	private static function maybe_register_block( string $src_block_dir ): void {
		if ( ! file_exists( $src_block_dir . '/block.json' ) ) {
			return;
		}

		$slug             = basename( $src_block_dir );
		$build_block_dir  = PV_BLOCKS_SUITE_DIR . 'build/blocks/' . $slug;
		$build_block_json = $build_block_dir . '/block.json';

		if ( ! file_exists( $build_block_json ) ) {
			self::warn_missing_build( $slug );
			return;
		}

		register_block_type( $build_block_dir );
	}

	/**
	 * Logs (debug mode only) that a block exists in src/ but hasn't been
	 * built yet, so a "missing" block in the editor is easy to diagnose.
	 *
	 * @param string $slug Block folder slug, e.g. "container".
	 */
	private static function warn_missing_build( string $slug ): void {
		if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
			return;
		}

		// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		error_log(
			sprintf(
				'[pv-blocks-suite] Block "%s" has a src/ definition but no compiled build/ output. Run `npm run build`.',
				$slug
			)
		);
	}
}
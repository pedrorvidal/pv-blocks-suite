<?php
/**
 * PHPStan-only bootstrap. Declares constants that are defined at runtime
 * with non-literal values (so PHPStan can't discover them via define()
 * scanning) purely so static analysis knows they exist. Never loaded by
 * WordPress itself.
 *
 * @package PV\BlocksSuite
 */

declare( strict_types=1 );

if ( ! defined( 'PV_BLOCKS_SUITE_DIR' ) ) {
	define( 'PV_BLOCKS_SUITE_DIR', __DIR__ . '/' );
}

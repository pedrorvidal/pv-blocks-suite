<?php
/**
 * Plugin Name: PV Blocks Suite
 * Description: A set of Gutenberg blocks to streamline content creation.
 * Version: 0.1.0
 * Author: Pedro Vidal
 * License: GPL v2 or later
 * Text Domain: pv-blocks-suite
 * Requires PHP: 8.2
 */

declare( strict_types = 1 );

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PV_BLOCKS_SUITE_DIR', plugin_dir_path( __FILE__ ) );

require __DIR__ . '/vendor/autoload.php';

\PV\BlocksSuite\Block_Loader::init();
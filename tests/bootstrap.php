<?php
/**
 * PHPUnit bootstrap for the PV Blocks Suite test suite.
 *
 * Wires the Composer-installed copy of WordPress core's own test suite
 * (`wp-phpunit/wp-phpunit`) to this plugin: loads the PHPUnit Polyfills it
 * requires, points it at our DB/environment config
 * (`wp-tests-config.php`), hooks the plugin to load the same way a real
 * must-use plugin would (before WordPress finishes booting), then hands
 * control to the suite's own bootstrap to install/reset the test database
 * and load WordPress.
 *
 * @package PV\BlocksSuite
 */

declare( strict_types=1 );

$plugin_root    = dirname( __DIR__ );
$wp_phpunit_dir = $plugin_root . '/vendor/wp-phpunit/wp-phpunit';

require_once $plugin_root . '/vendor/yoast/phpunit-polyfills/phpunitpolyfills-autoload.php';

// Tells wp-phpunit's own (placeholder) wp-tests-config.php where to find
// our real one, instead of editing a file inside vendor/. This runs once,
// before WordPress (and any request-scoped config) exists, so there's
// nothing else here for a runtime env change to conflict with.
// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_putenv -- required by wp-phpunit's own documented setup; see comment above.
putenv( 'WP_PHPUNIT__TESTS_CONFIG=' . __DIR__ . '/wp-tests-config.php' );

require $wp_phpunit_dir . '/includes/functions.php';

/**
 * Loads the plugin once WordPress is ready for plugins, but before the
 * rest of the test suite's own fixtures run — mirroring how a real
 * WordPress install loads it.
 */
tests_add_filter(
	'muplugins_loaded',
	static function () use ( $plugin_root ): void {
		require $plugin_root . '/pv-blocks-suite.php';
	}
);

require $wp_phpunit_dir . '/includes/bootstrap.php';

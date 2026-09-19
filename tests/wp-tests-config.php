<?php
/**
 * Database and environment configuration for the PHPUnit test suite.
 *
 * These values match the Docker Compose service containers wp-env spins up
 * from `.wp-env.tests.json` (confirmed via
 * `wp-env run cli --config .wp-env.tests.json -- wp config list`) — they're
 * wp-env's own fixed defaults for its Docker network, not secrets, and
 * only reachable from inside that network.
 *
 * @package PV\BlocksSuite
 */

// WordPress core lives here inside the wp-env containers this suite runs in.
define( 'ABSPATH', '/var/www/html/' );

define( 'DB_NAME', 'wordpress' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', 'password' );
define( 'DB_HOST', 'mysql' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

/*
 * A distinct prefix keeps the tables PHPUnit creates/resets separate from
 * the live site's own `wp_` tables in that same database. wp-phpunit's own
 * bootstrap (includes/bootstrap.php) requires this as a plain global, not
 * a constant — that's not our choice to make here.
 */
// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- required by wp-phpunit's bootstrap; see comment above.
$table_prefix = 'wptests_';

define( 'WP_TESTS_DOMAIN', 'example.org' );
define( 'WP_TESTS_EMAIL', 'admin@example.org' );
define( 'WP_TESTS_TITLE', 'PV Blocks Suite Test Site' );

define( 'WP_PHP_BINARY', 'php' );

define( 'WP_DEBUG', true );

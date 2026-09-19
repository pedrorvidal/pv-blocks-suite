const path = require( 'path' );

const defaultConfig = require( '@wordpress/scripts/config/jest-unit.config.js' );

module.exports = {
	...defaultConfig,
	// Jest merges this with the preset's own setupFilesAfterEnv (which
	// registers @wordpress/jest-console), rather than replacing it, so
	// both run.
	setupFilesAfterEnv: [ '@testing-library/jest-dom' ],
	// @wordpress/components and @wordpress/blocks pull in a growing chain
	// of dependencies (uuid, @wordpress/ui, its own nested
	// @wordpress/theme, marked, ...) that ship pure ESM. Jest ignores all
	// of node_modules for transforms by default; rather than maintain a
	// list of offenders that grows every time a transitive dependency
	// changes, transform everything and let Babel no-op on plain CJS.
	transformIgnorePatterns: [],
	// The wp-scripts default transform only matches .js/.jsx/.ts/.tsx, but
	// some of those @wordpress/* dependencies ship as .mjs, which Jest
	// would otherwise try to require as-is (still hitting the "Cannot use
	// import statement outside a module" error even once un-ignored above).
	transform: {
		...defaultConfig.transform,
		'\\.mjs$': path.join(
			path.dirname( require.resolve( '@wordpress/scripts/package.json' ) ),
			'config',
			'babel-transform'
		),
	},
};

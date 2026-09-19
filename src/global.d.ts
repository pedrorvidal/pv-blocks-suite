// @wordpress/block-editor's published types (build-types/) cover only part
// of its public API in this version — InspectorControls, InnerBlocks,
// PanelColorSettings, MediaUpload etc. aren't included. Rather than fight
// partial coverage, treat the whole module as untyped here.
declare module '@wordpress/block-editor';

// Webpack (via wp-scripts) handles .scss imports at build time; they're
// side-effect only and have no JS/TS shape to type.
declare module '*.scss';

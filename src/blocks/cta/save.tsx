// Fully dynamic block: render.php builds all markup from attributes, so
// nothing needs to be stored as post content (unlike `container`, which
// saves `<InnerBlocks.Content />` because InnerBlocks needs *something*
// persisted). Returning null keeps only the block comment delimiter +
// attributes JSON in the post content.
export default function save() {
    return null;
}

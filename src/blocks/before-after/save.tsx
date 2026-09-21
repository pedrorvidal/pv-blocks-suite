// Fully dynamic block: render.php builds all markup from attributes, so
// nothing needs to be stored as post content. Returning null keeps only
// the block comment delimiter + attributes JSON in the post content.
export default function save() {
    return null;
}

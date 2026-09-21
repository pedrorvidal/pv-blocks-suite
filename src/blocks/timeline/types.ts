export interface TimelineAttributes {
    // BlockEditProps<Attrs> / BlockConfiguration<Attrs> require Attrs to
    // satisfy Record<string, unknown>. This block has no attributes of
    // its own — it's purely an InnerBlocks wrapper around timeline-item.
    [key: string]: unknown;
}

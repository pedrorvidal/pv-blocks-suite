export interface TabsAttributes {
    // No attributes of its own — mirrors accordion's parent shape exactly.
    // BlockEditProps<Attrs> / BlockConfiguration<Attrs> require Attrs to
    // satisfy Record<string, unknown>.
    [key: string]: unknown;
}

export interface TeamAttributes {
    columns: number;
    // BlockEditProps<Attrs> / BlockConfiguration<Attrs> require Attrs to
    // satisfy Record<string, unknown>.
    [key: string]: unknown;
}

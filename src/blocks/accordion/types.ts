export interface AccordionAttributes {
    groupId: string;
    allowMultipleOpen: boolean;
    // BlockEditProps<Attrs> / BlockConfiguration<Attrs> require Attrs to
    // satisfy Record<string, unknown>.
    [key: string]: unknown;
}

export interface AccordionItemAttributes {
    summary: string;
    headingLevel: number;
    openByDefault: boolean;
    // BlockEditProps<Attrs> / BlockConfiguration<Attrs> require Attrs to
    // satisfy Record<string, unknown>.
    [key: string]: unknown;
}

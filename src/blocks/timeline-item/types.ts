export interface TimelineItemAttributes {
    imageUrl: string;
    imageAlt: string;
    date: string;
    heading: string;
    headingLevel: number;
    description: string;
    // BlockEditProps<Attrs> / BlockConfiguration<Attrs> require Attrs to
    // satisfy Record<string, unknown>.
    [key: string]: unknown;
}

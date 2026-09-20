export interface CtaAttributes {
    heading: string;
    description: string;
    buttonText: string;
    buttonUrl: string;
    buttonOpensInNewTab: boolean;
    textAlign: string;
    backgroundColor: string;
    backgroundImage: string;
    textColor: string;
    buttonBackgroundColor: string;
    buttonTextColor: string;
    // BlockEditProps<Attrs> / BlockConfiguration<Attrs> require Attrs to
    // satisfy Record<string, unknown>.
    [key: string]: unknown;
}

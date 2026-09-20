// Shape of the `style` attribute WordPress injects automatically for any
// block that declares `supports.spacing`/`supports.border` in block.json —
// not something we register ourselves. `radius`/`padding` are a single
// string when every side is linked, or a per-side object when a user
// unlinks them via the Inspector's "unlink" toggle.
export interface CtaBlockSupportsStyle {
    border?: {
        radius?:
            | string
            | Partial<
                  Record<
                      'topLeft' | 'topRight' | 'bottomLeft' | 'bottomRight',
                      string
                  >
              >;
    };
    spacing?: {
        padding?: Partial<
            Record<'top' | 'right' | 'bottom' | 'left', string>
        >;
    };
}

export interface CtaAttributes {
    heading: string;
    headingLevel: number;
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
    style?: CtaBlockSupportsStyle;
    // BlockEditProps<Attrs> / BlockConfiguration<Attrs> require Attrs to
    // satisfy Record<string, unknown>.
    [key: string]: unknown;
}

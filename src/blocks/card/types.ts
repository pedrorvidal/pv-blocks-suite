// Shape of the `style` attribute WordPress injects automatically for any
// block that declares `supports.spacing`/`supports.border` in block.json —
// not something we register ourselves. See cta/types.ts for the full
// explanation of this shape (same native-support pattern reused here).
export interface CardBlockSupportsStyle {
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

export interface CardAttributes {
    imageUrl: string;
    imageAlt: string;
    heading: string;
    headingLevel: number;
    description: string;
    buttonText: string;
    buttonUrl: string;
    buttonOpensInNewTab: boolean;
    style?: CardBlockSupportsStyle;
    // BlockEditProps<Attrs> / BlockConfiguration<Attrs> require Attrs to
    // satisfy Record<string, unknown>.
    [key: string]: unknown;
}

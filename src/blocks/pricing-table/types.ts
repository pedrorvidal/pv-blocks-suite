export interface PricingTableAttributes {
    // BlockEditProps<Attrs> / BlockConfiguration<Attrs> require Attrs to
    // satisfy Record<string, unknown>. This block has no attributes of
    // its own — it's purely an InnerBlocks wrapper around pricing-plan.
    [key: string]: unknown;
}

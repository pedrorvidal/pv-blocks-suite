<?php
/**
 * Tests for the `pv-blocks-suite/pricing-plan` block's server-side render.
 *
 * @package PV\BlocksSuite
 */

declare( strict_types=1 );

final class Pricing_Plan_Render_Test extends WP_UnitTestCase {

	/**
	 * Renders the pricing-plan block.
	 *
	 * @param array<string, mixed> $attrs      Block attributes.
	 * @param string               $inner_html Inner blocks markup.
	 */
	private function render_plan( array $attrs = [], string $inner_html = '' ): string {
		return (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/pricing-plan',
				'attrs'        => $attrs,
				'innerHTML'    => $inner_html,
				'innerContent' => [ $inner_html ],
			]
		);
	}

	public function test_block_is_registered(): void {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'pv-blocks-suite/pricing-plan' ) );
	}

	public function test_plan_name_price_and_description_are_rendered(): void {
		$output = $this->render_plan(
			[
				'planName'    => 'Pro',
				'price'       => '$29',
				'pricePeriod' => '/month',
				'description' => 'For growing teams.',
			]
		);

		$this->assertStringContainsString( '<h3 class="wp-block-pv-blocks-suite-pricing-plan__plan-name">Pro</h3>', $output );
		$this->assertStringContainsString( '<span class="wp-block-pv-blocks-suite-pricing-plan__price-amount">$29</span>', $output );
		$this->assertStringContainsString( '<span class="wp-block-pv-blocks-suite-pricing-plan__price-period">/month</span>', $output );
		$this->assertStringContainsString( '<p class="wp-block-pv-blocks-suite-pricing-plan__description">For growing teams.</p>', $output );
	}

	public function test_heading_level_is_configurable(): void {
		$output = $this->render_plan(
			[
				'planName'     => 'Pro',
				'headingLevel' => 4,
			]
		);

		$this->assertStringContainsString( '<h4 class="wp-block-pv-blocks-suite-pricing-plan__plan-name">Pro</h4>', $output );
	}

	public function test_heading_level_is_clamped_to_a_valid_range(): void {
		$output = $this->render_plan(
			[
				'planName'     => 'Pro',
				'headingLevel' => 1,
			]
		);

		$this->assertStringContainsString( '<h2 class="wp-block-pv-blocks-suite-pricing-plan__plan-name">Pro</h2>', $output );
	}

	public function test_empty_fields_are_omitted(): void {
		$output = $this->render_plan();

		$this->assertStringNotContainsString( '__plan-name', $output );
		$this->assertStringNotContainsString( '__price', $output );
		$this->assertStringNotContainsString( '__description', $output );
	}

	public function test_features_inner_blocks_are_preserved(): void {
		$output = $this->render_plan( [], '<ul><li>Unlimited projects</li></ul>' );

		$this->assertStringContainsString( '<ul><li>Unlimited projects</li></ul>', $output );
	}

	public function test_button_is_rendered_when_text_and_url_are_set(): void {
		$output = $this->render_plan(
			[
				'buttonText' => 'Choose Pro',
				'buttonUrl'  => 'https://example.org/checkout',
			]
		);

		$this->assertStringContainsString( 'href="https://example.org/checkout"', $output );
		$this->assertMatchesRegularExpression( '/>\s*Choose Pro\s*<\/a>/', $output );
	}

	public function test_button_is_omitted_when_text_or_url_is_missing(): void {
		$output_no_url  = $this->render_plan( [ 'buttonText' => 'Choose Pro' ] );
		$output_no_text = $this->render_plan( [ 'buttonUrl' => 'https://example.org/checkout' ] );

		$this->assertStringNotContainsString( '<a ', $output_no_url );
		$this->assertStringNotContainsString( '<a ', $output_no_text );
	}

	public function test_button_hints_it_opens_in_a_new_tab_for_screen_readers(): void {
		$output = $this->render_plan(
			[
				'buttonText'          => 'Choose Pro',
				'buttonUrl'           => 'https://example.org/checkout',
				'buttonOpensInNewTab' => true,
			]
		);

		$this->assertStringContainsString( 'target="_blank"', $output );
		$this->assertStringContainsString( 'rel="noopener noreferrer"', $output );
		$this->assertStringContainsString( '(opens in a new tab)', $output );
	}

	public function test_featured_badge_and_class_are_rendered_when_featured(): void {
		$output = $this->render_plan(
			[
				'isFeatured'    => true,
				'featuredLabel' => 'Most Popular',
			]
		);

		$this->assertStringContainsString( 'is-featured', $output );
		$this->assertStringContainsString( '<span class="wp-block-pv-blocks-suite-pricing-plan__featured-badge">Most Popular</span>', $output );
	}

	public function test_featured_badge_and_class_are_absent_by_default(): void {
		$output = $this->render_plan();

		$this->assertStringNotContainsString( 'is-featured', $output );
		$this->assertStringNotContainsString( '__featured-badge', $output );
	}

	public function test_featured_colors_become_inline_styles_when_featured(): void {
		$output = $this->render_plan(
			[
				'isFeatured'              => true,
				'featuredBackgroundColor' => '#1e1e1e',
				'featuredTextColor'       => '#ffffff',
			]
		);

		$this->assertStringContainsString( 'background-color:#1e1e1e', $output );
		$this->assertStringContainsString( 'color:#ffffff', $output );
	}

	public function test_featured_colors_are_ignored_when_not_featured(): void {
		$output = $this->render_plan(
			[
				'isFeatured'              => false,
				'featuredBackgroundColor' => '#1e1e1e',
				'featuredTextColor'       => '#ffffff',
			]
		);

		$this->assertStringNotContainsString( '#1e1e1e', $output );
		$this->assertStringNotContainsString( '#ffffff', $output );
	}

	public function test_plan_name_and_description_allow_safe_html_but_strip_scripts(): void {
		$output = $this->render_plan(
			[
				'planName'    => 'Hello <strong>world</strong><script>alert(1)</script>',
				'description' => 'Safe <em>text</em><script>alert(2)</script>',
			]
		);

		$this->assertStringContainsString( '<strong>world</strong>', $output );
		$this->assertStringContainsString( '<em>text</em>', $output );
		$this->assertStringNotContainsString( '<script>', $output );
	}

	public function test_featured_label_is_escaped_not_html_permissive(): void {
		$output = $this->render_plan(
			[
				'isFeatured'    => true,
				'featuredLabel' => 'Hot <script>alert(1)</script>',
			]
		);

		$this->assertStringNotContainsString( '<script>', $output );
	}
}

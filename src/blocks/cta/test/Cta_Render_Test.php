<?php
/**
 * Tests for the `pv-blocks-suite/cta` block's server-side render.
 *
 * Renders through the real `render_block()` / `WP_Block` machinery (not by
 * including render.php directly), so attribute defaults from block.json are
 * applied the same way they are for a real request, and the block must
 * actually be registered — the same guarantee Block_Loader gives in
 * production.
 *
 * @package PV\BlocksSuite
 */

declare( strict_types = 1 );

final class Cta_Render_Test extends WP_UnitTestCase {

	/**
	 * Renders the cta block with the given attributes.
	 *
	 * @param array<string, mixed> $attrs Block attributes.
	 */
	private function render_cta( array $attrs = [] ): string {
		return (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/cta',
				'attrs'        => $attrs,
				'innerHTML'    => '',
				'innerContent' => [],
			]
		);
	}

	public function test_block_is_registered(): void {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'pv-blocks-suite/cta' ) );
	}

	public function test_heading_and_description_are_rendered(): void {
		$output = $this->render_cta(
			[
				'heading'     => 'Sign up today',
				'description' => 'Join thousands of happy customers.',
			]
		);

		$this->assertStringContainsString( '<h2 class="wp-block-pv-blocks-suite-cta__heading">Sign up today</h2>', $output );
		$this->assertStringContainsString( '<p class="wp-block-pv-blocks-suite-cta__description">Join thousands of happy customers.</p>', $output );
	}

	public function test_empty_heading_and_description_are_omitted(): void {
		$output = $this->render_cta();

		$this->assertStringNotContainsString( '<h2', $output );
		$this->assertStringNotContainsString( '<p class="wp-block-pv-blocks-suite-cta__description"', $output );
	}

	public function test_button_is_rendered_when_text_and_url_are_set(): void {
		$output = $this->render_cta(
			[
				'buttonText' => 'Get started',
				'buttonUrl'  => 'https://example.org/signup',
			]
		);

		$this->assertStringContainsString( 'href="https://example.org/signup"', $output );
		// Not an exact `>Get started</a>` match: the template has a
		// trailing conditional (the "opens in a new tab" hint) after the
		// button text, which leaves whitespace between the text and the
		// closing tag even when that hint isn't rendered.
		$this->assertMatchesRegularExpression( '/>\s*Get started\s*<\/a>/', $output );
	}

	public function test_button_is_omitted_when_text_or_url_is_missing(): void {
		$output_no_url  = $this->render_cta( [ 'buttonText' => 'Get started' ] );
		$output_no_text = $this->render_cta( [ 'buttonUrl' => 'https://example.org/signup' ] );

		$this->assertStringNotContainsString( '<a ', $output_no_url );
		$this->assertStringNotContainsString( '<a ', $output_no_text );
	}

	public function test_button_opens_in_new_tab_when_enabled(): void {
		$output = $this->render_cta(
			[
				'buttonText'          => 'Get started',
				'buttonUrl'           => 'https://example.org/signup',
				'buttonOpensInNewTab' => true,
			]
		);

		$this->assertStringContainsString( 'target="_blank"', $output );
		$this->assertStringContainsString( 'rel="noopener noreferrer"', $output );
	}

	public function test_button_does_not_open_in_new_tab_by_default(): void {
		$output = $this->render_cta(
			[
				'buttonText' => 'Get started',
				'buttonUrl'  => 'https://example.org/signup',
			]
		);

		$this->assertStringNotContainsString( 'target="_blank"', $output );
	}

	public function test_text_align_and_colors_become_inline_styles(): void {
		$output = $this->render_cta(
			[
				'textAlign'       => 'right',
				'backgroundColor' => '#000000',
				'textColor'       => '#ffffff',
			]
		);

		$this->assertStringContainsString( 'text-align:right', $output );
		$this->assertStringContainsString( 'background-color:#000000', $output );
		$this->assertStringContainsString( 'color:#ffffff', $output );
	}

	public function test_background_image_url_is_escaped(): void {
		$output = $this->render_cta( [ 'backgroundImage' => 'https://example.org/photo.jpg?a=1&b=2' ] );

		$this->assertStringContainsString( 'background-image:url(https://example.org/photo.jpg?a=1', $output );
	}

	public function test_button_color_attributes_become_inline_styles_on_the_button(): void {
		$output = $this->render_cta(
			[
				'buttonText'            => 'Get started',
				'buttonUrl'             => 'https://example.org/signup',
				'buttonBackgroundColor' => '#ff0000',
				'buttonTextColor'       => '#00ff00',
			]
		);

		$this->assertMatchesRegularExpression(
			'/<a[^>]*style="[^"]*background-color:\s*#ff0000/',
			$output
		);
		$this->assertMatchesRegularExpression(
			'/<a[^>]*style="[^"]*color:\s*#00ff00/',
			$output
		);
	}

	public function test_native_padding_support_is_applied(): void {
		$output = $this->render_cta(
			[
				'style' => [
					'spacing' => [
						'padding' => [
							'top'    => '2rem',
							'right'  => '1rem',
							'bottom' => '2rem',
							'left'   => '1rem',
						],
					],
				],
			]
		);

		$this->assertStringContainsString( 'padding-top:2rem', $output );
		$this->assertStringContainsString( 'padding-right:1rem', $output );
		$this->assertStringContainsString( 'padding-bottom:2rem', $output );
		$this->assertStringContainsString( 'padding-left:1rem', $output );
	}

	public function test_native_border_radius_support_is_applied(): void {
		$output = $this->render_cta(
			[
				'style' => [
					'border' => [
						'radius' => '12px',
					],
				],
			]
		);

		$this->assertStringContainsString( 'border-radius:12px', $output );
	}

	public function test_overflow_hidden_when_linked_border_radius_is_set(): void {
		$output = $this->render_cta(
			[
				'style' => [
					'border' => [
						'radius' => '12px',
					],
				],
			]
		);

		$this->assertStringContainsString( 'overflow:hidden', $output );
	}

	public function test_overflow_hidden_when_only_one_corner_radius_is_unlinked_and_set(): void {
		$output = $this->render_cta(
			[
				'style' => [
					'border' => [
						'radius' => [ 'topLeft' => '12px' ],
					],
				],
			]
		);

		$this->assertStringContainsString( 'overflow:hidden', $output );
	}

	public function test_overflow_is_omitted_when_no_border_radius_is_set(): void {
		$output = $this->render_cta();

		$this->assertStringNotContainsString( 'overflow', $output );
	}

	public function test_heading_and_description_allow_safe_html_but_strip_scripts(): void {
		$output = $this->render_cta(
			[
				'heading' => 'Hello <strong>world</strong><script>alert(1)</script>',
			]
		);

		$this->assertStringContainsString( '<strong>world</strong>', $output );
		$this->assertStringNotContainsString( '<script>', $output );
	}

	public function test_heading_level_is_configurable(): void {
		$output = $this->render_cta(
			[
				'heading'      => 'Sign up today',
				'headingLevel' => 4,
			]
		);

		$this->assertStringContainsString( '<h4 class="wp-block-pv-blocks-suite-cta__heading">Sign up today</h4>', $output );
	}

	public function test_heading_level_is_clamped_to_a_valid_range(): void {
		$output = $this->render_cta(
			[
				'heading'      => 'Sign up today',
				'headingLevel' => 1,
			]
		);

		$this->assertStringContainsString( '<h2 class="wp-block-pv-blocks-suite-cta__heading">Sign up today</h2>', $output );
	}

	public function test_button_hints_it_opens_in_a_new_tab_for_screen_readers(): void {
		$output = $this->render_cta(
			[
				'buttonText'          => 'Get started',
				'buttonUrl'           => 'https://example.org/signup',
				'buttonOpensInNewTab' => true,
			]
		);

		$this->assertStringContainsString( '(opens in a new tab)', $output );
	}

	public function test_new_tab_hint_is_absent_by_default(): void {
		$output = $this->render_cta(
			[
				'buttonText' => 'Get started',
				'buttonUrl'  => 'https://example.org/signup',
			]
		);

		$this->assertStringNotContainsString( 'opens in a new tab', $output );
	}
}

<?php
/**
 * Tests for the `pv-blocks-suite/alert-box` block's server-side render.
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

final class Alert_Box_Render_Test extends WP_UnitTestCase {

	/**
	 * Renders the alert-box block with the given attributes.
	 *
	 * @param array<string, mixed> $attrs Block attributes.
	 */
	private function render_alert( array $attrs = [] ): string {
		return (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/alert-box',
				'attrs'        => $attrs,
				'innerHTML'    => '',
				'innerContent' => [],
			]
		);
	}

	public function test_block_is_registered(): void {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'pv-blocks-suite/alert-box' ) );
	}

	public function test_defaults_to_the_info_variant(): void {
		$output = $this->render_alert();

		$this->assertStringContainsString( 'is-info', $output );
	}

	public function test_each_variant_applies_its_own_class_and_label(): void {
		$expectations = [
			'success' => 'Success',
			'warning' => 'Warning',
			'error'   => 'Error',
		];

		foreach ( $expectations as $variant => $label ) {
			$output = $this->render_alert( [ 'variant' => $variant ] );

			$this->assertStringContainsString( "is-{$variant}", $output );
			$this->assertStringContainsString( $label . ':', $output );
		}
	}

	public function test_unknown_variant_falls_back_to_info(): void {
		$output = $this->render_alert( [ 'variant' => 'not-a-real-variant' ] );

		$this->assertStringContainsString( 'is-info', $output );
		$this->assertStringNotContainsString( 'is-not-a-real-variant', $output );
	}

	public function test_heading_and_message_are_rendered(): void {
		$output = $this->render_alert(
			[
				'heading' => 'Heads up',
				'message' => 'This action cannot be undone.',
			]
		);

		$this->assertStringContainsString( '<strong class="wp-block-pv-blocks-suite-alert-box__heading">Heads up</strong>', $output );
		$this->assertStringContainsString( '<p class="wp-block-pv-blocks-suite-alert-box__message">This action cannot be undone.</p>', $output );
	}

	public function test_empty_heading_and_message_are_omitted(): void {
		$output = $this->render_alert();

		$this->assertStringNotContainsString( '__heading', $output );
		$this->assertStringNotContainsString( '__message', $output );
	}

	public function test_icon_is_rendered_by_default(): void {
		$output = $this->render_alert();

		$this->assertStringContainsString( 'wp-block-pv-blocks-suite-alert-box__icon', $output );
		$this->assertStringContainsString( '<svg', $output );
	}

	public function test_icon_is_omitted_when_show_icon_is_false(): void {
		$output = $this->render_alert( [ 'showIcon' => false ] );

		$this->assertStringNotContainsString( 'wp-block-pv-blocks-suite-alert-box__icon', $output );
		$this->assertStringNotContainsString( '<svg', $output );
	}

	public function test_screen_reader_label_is_present_even_when_icon_is_hidden(): void {
		$output = $this->render_alert(
			[
				'variant'  => 'error',
				'showIcon' => false,
			]
		);

		$this->assertStringContainsString( 'Error:', $output );
	}

	public function test_heading_and_message_allow_safe_html_but_strip_scripts(): void {
		$output = $this->render_alert(
			[
				'heading' => 'Hello <strong>world</strong><script>alert(1)</script>',
				'message' => 'Safe <em>text</em><script>alert(2)</script>',
			]
		);

		$this->assertStringContainsString( '<strong>world</strong>', $output );
		$this->assertStringContainsString( '<em>text</em>', $output );
		$this->assertStringNotContainsString( '<script>', $output );
	}
}

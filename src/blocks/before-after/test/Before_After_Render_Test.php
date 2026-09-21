<?php
/**
 * Tests for the `pv-blocks-suite/before-after` block's server-side render.
 *
 * Renders through the real `render_block()` / `WP_Block` machinery (not by
 * including render.php directly), so attribute defaults from block.json are
 * applied the same way they are for a real request, and the block must
 * actually be registered — the same guarantee Block_Loader gives in
 * production.
 *
 * @package PV\BlocksSuite
 */

declare( strict_types=1 );

final class Before_After_Render_Test extends WP_UnitTestCase {

	/**
	 * Renders the before-after block with the given attributes.
	 *
	 * @param array<string, mixed> $attrs Block attributes.
	 */
	private function render_before_after( array $attrs = [] ): string {
		return (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/before-after',
				'attrs'        => $attrs,
				'innerHTML'    => '',
				'innerContent' => [],
			]
		);
	}

	public function test_block_is_registered(): void {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'pv-blocks-suite/before-after' ) );
	}

	public function test_default_render_includes_the_interactive_stage(): void {
		$output = $this->render_before_after();

		$this->assertStringContainsString( 'wp-block-pv-blocks-suite-before-after__stage', $output );
		$this->assertStringContainsString( 'data-wp-interactive="pv-blocks-suite/before-after"', $output );
		$this->assertStringContainsString( 'data-wp-context=', $output );
		$this->assertStringContainsString( 'data-wp-on--pointerdown="actions.onPointerDown"', $output );
		$this->assertStringContainsString( 'data-wp-on-window--pointermove="actions.onPointerMove"', $output );
		$this->assertStringContainsString( 'data-wp-on-window--pointerup="actions.onPointerUp"', $output );
	}

	public function test_default_render_includes_both_images(): void {
		$output = $this->render_before_after(
			[
				'beforeImageUrl' => 'https://example.com/before.jpg',
				'beforeImageAlt' => 'Before renovation',
				'afterImageUrl'  => 'https://example.com/after.jpg',
				'afterImageAlt'  => 'After renovation',
			]
		);

		$this->assertStringContainsString( 'wp-block-pv-blocks-suite-before-after__image--before', $output );
		$this->assertStringContainsString( 'src="https://example.com/before.jpg"', $output );
		$this->assertStringContainsString( 'alt="Before renovation"', $output );
		$this->assertStringContainsString( 'wp-block-pv-blocks-suite-before-after__image--after', $output );
		$this->assertStringContainsString( 'src="https://example.com/after.jpg"', $output );
		$this->assertStringContainsString( 'alt="After renovation"', $output );
	}

	public function test_labels_are_rendered_when_show_labels_is_true(): void {
		$output = $this->render_before_after(
			[
				'showLabels'  => true,
				'beforeLabel' => 'Old kitchen',
				'afterLabel'  => 'New kitchen',
			]
		);

		$this->assertStringContainsString( 'wp-block-pv-blocks-suite-before-after__label--before', $output );
		$this->assertStringContainsString( 'Old kitchen', $output );
		$this->assertStringContainsString( 'wp-block-pv-blocks-suite-before-after__label--after', $output );
		$this->assertStringContainsString( 'New kitchen', $output );
	}

	public function test_labels_are_omitted_when_show_labels_is_false(): void {
		$output = $this->render_before_after(
			[
				'showLabels'  => false,
				'beforeLabel' => 'Old kitchen',
				'afterLabel'  => 'New kitchen',
			]
		);

		$this->assertStringNotContainsString( '__label--before', $output );
		$this->assertStringNotContainsString( '__label--after', $output );
	}

	public function test_a_blank_label_is_individually_omitted_even_with_show_labels_true(): void {
		$output = $this->render_before_after(
			[
				'showLabels'  => true,
				'beforeLabel' => '',
				'afterLabel'  => 'New kitchen',
			]
		);

		$this->assertStringNotContainsString( '__label--before', $output );
		$this->assertStringContainsString( '__label--after', $output );
	}

	public function test_initial_position_is_reflected_in_the_clip_path_and_handle(): void {
		$output = $this->render_before_after( [ 'initialPosition' => 30 ] );

		$this->assertStringContainsString( 'clip-path:inset(0 70% 0 0)', $output );
		$this->assertStringContainsString( 'left:30%', $output );
		$this->assertStringContainsString( 'aria-valuenow="30"', $output );
	}

	public function test_initial_position_below_zero_is_clamped(): void {
		$output = $this->render_before_after( [ 'initialPosition' => -20 ] );

		$this->assertStringContainsString( 'aria-valuenow="0"', $output );
		$this->assertStringContainsString( 'left:0%', $output );
	}

	public function test_initial_position_above_100_is_clamped(): void {
		$output = $this->render_before_after( [ 'initialPosition' => 150 ] );

		$this->assertStringContainsString( 'aria-valuenow="100"', $output );
		$this->assertStringContainsString( 'left:100%', $output );
	}

	public function test_aspect_ratio_is_mapped_to_the_css_value(): void {
		$output = $this->render_before_after( [ 'aspectRatio' => '1:1' ] );
		$this->assertStringContainsString( 'aspect-ratio:1 / 1', $output );

		$output = $this->render_before_after( [ 'aspectRatio' => '21:9' ] );
		$this->assertStringContainsString( 'aspect-ratio:21 / 9', $output );
	}

	public function test_unrecognized_aspect_ratio_falls_back_to_16_9(): void {
		$output = $this->render_before_after( [ 'aspectRatio' => 'not-a-real-ratio' ] );

		$this->assertStringContainsString( 'aspect-ratio:16 / 9', $output );
	}

	public function test_wrapper_has_no_overflow_hidden_without_a_border_radius(): void {
		$output = $this->render_before_after();

		$this->assertStringNotContainsString( 'overflow:hidden', $output );
	}

	public function test_wrapper_has_overflow_hidden_when_a_border_radius_is_set(): void {
		$output = $this->render_before_after(
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

	public function test_wrapper_has_overflow_hidden_when_a_per_corner_border_radius_is_set(): void {
		$output = $this->render_before_after(
			[
				'style' => [
					'border' => [
						'radius' => [
							'topLeft'     => '12px',
							'topRight'    => '',
							'bottomLeft'  => '',
							'bottomRight' => '',
						],
					],
				],
			]
		);

		$this->assertStringContainsString( 'overflow:hidden', $output );
	}

	public function test_handle_has_slider_role_and_aria_attributes(): void {
		$output = $this->render_before_after();

		$this->assertStringContainsString( 'role="slider"', $output );
		$this->assertStringContainsString( 'aria-valuemin="0"', $output );
		$this->assertStringContainsString( 'aria-valuemax="100"', $output );
		$this->assertStringContainsString( 'aria-orientation="horizontal"', $output );
		$this->assertStringContainsString( 'data-wp-bind--aria-valuenow="state.roundedPosition"', $output );
	}

	public function test_labels_allow_safe_html_but_strip_scripts(): void {
		$output = $this->render_before_after(
			[
				'beforeLabel' => 'Old <strong>kitchen</strong><script>alert(1)</script>',
			]
		);

		$this->assertStringContainsString( '<strong>kitchen</strong>', $output );
		$this->assertStringNotContainsString( '<script>', $output );
	}
}

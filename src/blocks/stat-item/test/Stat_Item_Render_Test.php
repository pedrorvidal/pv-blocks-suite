<?php
/**
 * Tests for the `pv-blocks-suite/stat-item` block's server-side render, in
 * isolation (no parent stats-counter / block context supplied).
 *
 * Cross-block context propagation (animationDuration) is covered by
 * Stats_Counter_Render_Test, since context only exists when the item is
 * actually rendered as a child of a real stats-counter block.
 *
 * @package PV\BlocksSuite
 */

declare( strict_types = 1 );

final class Stat_Item_Render_Test extends WP_UnitTestCase {

	/**
	 * Renders the stat-item block standalone (no parent context).
	 *
	 * @param array<string, mixed> $attrs Block attributes.
	 */
	private function render_item( array $attrs = [] ): string {
		return (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/stat-item',
				'attrs'        => $attrs,
				'innerHTML'    => '',
				'innerContent' => [],
			]
		);
	}

	public function test_block_is_registered(): void {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'pv-blocks-suite/stat-item' ) );
	}

	public function test_default_value_is_formatted_with_suffix(): void {
		$output = $this->render_item(
			[
				'value'  => 100,
				'suffix' => '+',
			]
		);

		$this->assertStringContainsString( '>100+<', $output );
	}

	public function test_prefix_and_suffix_are_both_applied(): void {
		$output = $this->render_item(
			[
				'value'  => 99,
				'prefix' => '$',
				'suffix' => 'k',
			]
		);

		$this->assertStringContainsString( '>$99k<', $output );
	}

	public function test_decimals_are_formatted(): void {
		$output = $this->render_item(
			[
				'value'    => 4.9,
				'decimals' => 1,
				'suffix'   => '',
			]
		);

		$this->assertStringContainsString( '>4.9<', $output );
	}

	public function test_decimals_are_clamped_to_a_valid_range(): void {
		$output = $this->render_item(
			[
				'value'    => 4.9,
				'decimals' => 10,
				'suffix'   => '',
			]
		);

		// Clamped to 2 decimal places, not the raw (invalid) 10.
		$this->assertStringContainsString( '>4.90<', $output );
	}

	public function test_large_values_get_thousands_separators(): void {
		$output = $this->render_item(
			[
				'value'  => 12000,
				'suffix' => '',
			]
		);

		$this->assertStringContainsString( '>12,000<', $output );
	}

	public function test_label_is_rendered_when_set(): void {
		$output = $this->render_item(
			[
				'value' => 500,
				'label' => 'Happy Clients',
			]
		);

		$this->assertStringContainsString( '<p class="wp-block-pv-blocks-suite-stat-item__label">Happy Clients</p>', $output );
	}

	public function test_label_is_absent_when_empty(): void {
		$output = $this->render_item( [ 'value' => 500 ] );

		$this->assertStringNotContainsString( '__label', $output );
	}

	public function test_label_allows_safe_html_but_strips_scripts(): void {
		$output = $this->render_item(
			[
				'value' => 500,
				'label' => 'Happy <strong>Clients</strong><script>alert(1)</script>',
			]
		);

		$this->assertStringContainsString( '<strong>Clients</strong>', $output );
		$this->assertStringNotContainsString( '<script>', $output );
	}

	public function test_animation_duration_defaults_to_2000_without_a_parent(): void {
		$output = $this->render_item( [ 'value' => 500 ] );

		// Always guard with a default when reading block context, per the
		// established rule from accordion-item: context is only populated
		// when a real matching ancestor exists.
		$this->assertStringContainsString( '"duration":2000', $output );
	}

	public function test_interactivity_directives_are_present(): void {
		$output = $this->render_item( [ 'value' => 500 ] );

		$this->assertStringContainsString( 'data-wp-interactive="pv-blocks-suite/stat-item"', $output );
		$this->assertStringContainsString( 'data-wp-init="callbacks.startObserving"', $output );
		$this->assertStringContainsString( 'data-wp-text="state.formattedValue"', $output );
	}

	public function test_context_carries_the_target_value(): void {
		$output = $this->render_item( [ 'value' => 500 ] );

		$this->assertStringContainsString( '"targetValue":500', $output );
		$this->assertStringContainsString( '"displayValue":500', $output );
	}

	public function test_overflow_hidden_only_applied_when_radius_is_set(): void {
		$output_without_radius = $this->render_item( [ 'value' => 500 ] );
		$output_with_radius    = $this->render_item(
			[
				'value' => 500,
				'style' => [ 'border' => [ 'radius' => '12px' ] ],
			]
		);

		$this->assertStringNotContainsString( 'overflow:hidden', $output_without_radius );
		$this->assertStringContainsString( 'overflow:hidden', $output_with_radius );
	}
}

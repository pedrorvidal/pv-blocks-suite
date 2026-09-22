<?php
/**
 * Tests for the `pv-blocks-suite/accordion-item` block's server-side
 * render, in isolation (no parent accordion / block context supplied).
 *
 * Cross-block context propagation (groupId, allowMultipleOpen) is covered
 * by Accordion_Render_Test, since context only exists when the item is
 * actually rendered as a child of a real accordion block.
 *
 * @package PV\BlocksSuite
 */

declare( strict_types = 1 );

final class Accordion_Item_Render_Test extends WP_UnitTestCase {

	/**
	 * Renders the accordion-item block standalone (no parent context).
	 *
	 * @param array<string, mixed> $attrs      Block attributes.
	 * @param string               $inner_html Inner blocks markup.
	 */
	private function render_item( array $attrs = [], string $inner_html = '' ): string {
		return (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/accordion-item',
				'attrs'        => $attrs,
				'innerHTML'    => $inner_html,
				'innerContent' => [ $inner_html ],
			]
		);
	}

	public function test_block_is_registered(): void {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'pv-blocks-suite/accordion-item' ) );
	}

	public function test_renders_as_a_details_and_summary_element(): void {
		$output = $this->render_item( [ 'summary' => 'Question' ] );

		$this->assertStringContainsString( '<details', $output );
		$this->assertStringContainsString( '<summary class="wp-block-pv-blocks-suite-accordion-item__summary">', $output );
		$this->assertStringContainsString( '</details>', $output );
	}

	public function test_question_is_wrapped_in_a_heading_for_screen_readers_and_seo(): void {
		$output = $this->render_item( [ 'summary' => 'Question' ] );

		// Defaults to h3: a real heading (not just styled text) lets
		// screen-reader users navigate the FAQ list by heading, and
		// matches how search engines expect FAQ-style content to be
		// structured.
		$this->assertMatchesRegularExpression(
			'/<summary class="wp-block-pv-blocks-suite-accordion-item__summary">\s*<h3 class="wp-block-pv-blocks-suite-accordion-item__summary-heading">Question<\/h3>\s*<\/summary>/',
			$output
		);
	}

	public function test_heading_level_is_configurable(): void {
		$output = $this->render_item(
			[
				'summary'      => 'Question',
				'headingLevel' => 5,
			]
		);

		$this->assertStringContainsString( '<h5 class="wp-block-pv-blocks-suite-accordion-item__summary-heading">Question</h5>', $output );
	}

	public function test_heading_level_is_clamped_to_a_valid_range(): void {
		$output = $this->render_item(
			[
				'summary'      => 'Question',
				'headingLevel' => 99,
			]
		);

		$this->assertStringContainsString( '<h6 class="wp-block-pv-blocks-suite-accordion-item__summary-heading">Question</h6>', $output );
	}

	public function test_panel_content_is_preserved(): void {
		$output = $this->render_item( [], '<p>The answer.</p>' );

		$this->assertStringContainsString( '<p>The answer.</p>', $output );
	}

	public function test_panel_content_is_wrapped_for_the_css_open_close_animation(): void {
		$output = $this->render_item( [], '<p>The answer.</p>' );

		// The extra `__content-inner` wrapper (and its parent
		// `__content`) is what the CSS grid-row animation in
		// style.scss animates between 0fr and 1fr — without it, the
		// content couldn't be collapsed to a single animatable row.
		$this->assertMatchesRegularExpression(
			'/<div class="wp-block-pv-blocks-suite-accordion-item__content">\s*<div class="wp-block-pv-blocks-suite-accordion-item__content-inner">\s*<p>The answer\.<\/p>/',
			$output
		);
	}

	public function test_open_attribute_is_present_when_open_by_default(): void {
		$output = $this->render_item( [ 'openByDefault' => true ] );

		$this->assertMatchesRegularExpression( '/<details[^>]*\bopen\b/', $output );
	}

	public function test_open_attribute_is_absent_by_default(): void {
		$output = $this->render_item();

		$this->assertDoesNotMatchRegularExpression( '/<details[^>]*\bopen\b/', $output );
	}

	public function test_name_attribute_is_absent_without_a_parent_group(): void {
		$output = $this->render_item( [ 'summary' => 'Question' ] );

		$this->assertStringNotContainsString( 'name=', $output );
	}

	public function test_summary_allows_safe_html_but_strips_scripts(): void {
		$output = $this->render_item(
			[ 'summary' => 'Is it <strong>free</strong>?<script>alert(1)</script>' ]
		);

		$this->assertStringContainsString( '<strong>free</strong>', $output );
		$this->assertStringNotContainsString( '<script>', $output );
	}
}

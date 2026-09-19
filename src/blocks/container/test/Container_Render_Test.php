<?php
/**
 * Tests for the `pv-blocks-suite/container` block's server-side render.
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

final class Container_Render_Test extends WP_UnitTestCase {

	/**
	 * Renders the container block with the given attributes and inner content.
	 *
	 * @param array<string, mixed> $attrs        Block attributes.
	 * @param string               $inner_html   Inner blocks markup.
	 */
	private function render_container( array $attrs = [], string $inner_html = '' ): string {
		return (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/container',
				'attrs'        => $attrs,
				'innerHTML'    => $inner_html,
				'innerContent' => [ $inner_html ],
			]
		);
	}

	public function test_block_is_registered(): void {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'pv-blocks-suite/container' ) );
	}

	public function test_padding_and_max_width_become_inline_styles(): void {
		$output = $this->render_container(
			[
				'paddingTop'    => '3rem',
				'paddingBottom' => '3rem',
				'paddingLeft'   => '2rem',
				'paddingRight'  => '2rem',
				'maxWidth'      => '800px',
			]
		);

		$this->assertStringContainsString( 'padding-top:3rem', $output );
		$this->assertStringContainsString( 'padding-bottom:3rem', $output );
		$this->assertStringContainsString( 'padding-left:2rem', $output );
		$this->assertStringContainsString( 'padding-right:2rem', $output );
		$this->assertStringContainsString( 'max-width:800px', $output );
		$this->assertStringContainsString( 'margin-left:auto', $output );
		$this->assertStringContainsString( 'margin-right:auto', $output );
	}

	public function test_inner_content_is_preserved(): void {
		$output = $this->render_container( [], '<p>Hello from inside</p>' );

		$this->assertStringContainsString( '<p>Hello from inside</p>', $output );
	}

	public function test_background_color_is_applied_when_set(): void {
		$output = $this->render_container( [ 'backgroundColor' => '#ff0000' ] );

		$this->assertStringContainsString( 'background-color:#ff0000', $output );
	}

	public function test_background_styles_are_omitted_when_unset(): void {
		$output = $this->render_container();

		$this->assertStringNotContainsString( 'background-color', $output );
		$this->assertStringNotContainsString( 'background-image', $output );
	}

	public function test_background_image_url_is_escaped(): void {
		$output = $this->render_container( [ 'backgroundImage' => 'https://example.org/photo.jpg?a=1&b=2' ] );

		$this->assertStringContainsString( 'background-image:url(https://example.org/photo.jpg?a=1', $output );
	}
}

<?php
/**
 * Tests for the `pv-blocks-suite/team-member` block's server-side render.
 *
 * @package PV\BlocksSuite
 */

declare( strict_types=1 );

final class Team_Member_Render_Test extends WP_UnitTestCase {

	/**
	 * Renders the team-member block.
	 *
	 * @param array<string, mixed> $attrs Block attributes.
	 */
	private function render_team_member( array $attrs = [] ): string {
		return (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/team-member',
				'attrs'        => $attrs,
				'innerHTML'    => '',
				'innerContent' => [],
			]
		);
	}

	public function test_block_is_registered(): void {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'pv-blocks-suite/team-member' ) );
	}

	public function test_name_role_and_bio_are_rendered(): void {
		$output = $this->render_team_member(
			[
				'name' => 'Alex Morgan',
				'role' => 'CEO & Founder',
				'bio'  => 'Leads the company.',
			]
		);

		$this->assertStringContainsString( '<h3 class="wp-block-pv-blocks-suite-team-member__name">Alex Morgan</h3>', $output );
		$this->assertStringContainsString( '<p class="wp-block-pv-blocks-suite-team-member__role">CEO &amp; Founder</p>', $output );
		$this->assertStringContainsString( '<p class="wp-block-pv-blocks-suite-team-member__bio">Leads the company.</p>', $output );
	}

	public function test_heading_level_is_configurable(): void {
		$output = $this->render_team_member(
			[
				'name'         => 'Alex Morgan',
				'headingLevel' => 4,
			]
		);

		$this->assertStringContainsString( '<h4 class="wp-block-pv-blocks-suite-team-member__name">Alex Morgan</h4>', $output );
	}

	public function test_heading_level_is_clamped_to_a_valid_range(): void {
		$output = $this->render_team_member(
			[
				'name'         => 'Alex Morgan',
				'headingLevel' => 99,
			]
		);

		$this->assertStringContainsString( '<h6 class="wp-block-pv-blocks-suite-team-member__name">Alex Morgan</h6>', $output );
	}

	public function test_empty_fields_are_omitted(): void {
		$output = $this->render_team_member();

		$this->assertStringNotContainsString( '<img', $output );
		$this->assertStringNotContainsString( '__name', $output );
		$this->assertStringNotContainsString( '__role', $output );
		$this->assertStringNotContainsString( '__bio', $output );
	}

	public function test_avatar_is_rendered_with_alt_text(): void {
		$output = $this->render_team_member(
			[
				'avatarUrl' => 'https://example.org/photo.jpg',
				'avatarAlt' => 'A photo of Alex Morgan',
			]
		);

		$this->assertStringContainsString( 'src="https://example.org/photo.jpg"', $output );
		$this->assertStringContainsString( 'alt="A photo of Alex Morgan"', $output );
	}

	public function test_avatar_alt_attribute_is_always_present_even_when_empty(): void {
		$output = $this->render_team_member( [ 'avatarUrl' => 'https://example.org/photo.jpg' ] );

		// A missing `alt` attribute (as opposed to an empty one) makes
		// screen readers fall back to announcing the raw filename/URL —
		// alt="" explicitly marks the image as decorative instead.
		$this->assertStringContainsString( 'alt=""', $output );
	}

	public function test_overflow_hidden_when_border_radius_is_set(): void {
		$output = $this->render_team_member(
			[
				'style' => [
					'border' => [ 'radius' => '12px' ],
				],
			]
		);

		$this->assertStringContainsString( 'overflow:hidden', $output );
	}

	public function test_overflow_is_omitted_when_no_border_radius_is_set(): void {
		$output = $this->render_team_member();

		$this->assertStringNotContainsString( 'overflow', $output );
	}

	public function test_name_and_bio_allow_safe_html_but_strip_scripts(): void {
		$output = $this->render_team_member(
			[
				'name' => 'Hello <strong>world</strong><script>alert(1)</script>',
				'bio'  => 'Safe <em>text</em><script>alert(2)</script>',
			]
		);

		$this->assertStringContainsString( '<strong>world</strong>', $output );
		$this->assertStringContainsString( '<em>text</em>', $output );
		$this->assertStringNotContainsString( '<script>', $output );
	}

	public function test_role_is_plain_text_not_rich_html(): void {
		$output = $this->render_team_member(
			[ 'role' => 'CEO <strong>& Founder</strong>' ]
		);

		$this->assertStringNotContainsString( '<strong>', $output );
		$this->assertStringContainsString( 'CEO &lt;strong&gt;&amp; Founder&lt;/strong&gt;', $output );
	}

	public function test_avatar_url_is_escaped(): void {
		$output = $this->render_team_member(
			[ 'avatarUrl' => 'https://example.org/photo.jpg?a=1&b=2' ]
		);

		$this->assertStringContainsString( 'src="https://example.org/photo.jpg?a=1&#038;b=2"', $output );
	}
}

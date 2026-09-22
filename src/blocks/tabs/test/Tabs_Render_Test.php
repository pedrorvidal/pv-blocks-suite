<?php
/**
 * Tests for the `pv-blocks-suite/tabs` block's server-side render.
 *
 * Renders through the real `render_block()` / `WP_Block` machinery (not by
 * including render.php directly), so the parent can genuinely read its
 * children's resolved attributes off `$block->inner_blocks` the same way
 * it does in production.
 *
 * @package PV\BlocksSuite
 */

declare( strict_types = 1 );

final class Tabs_Render_Test extends WP_UnitTestCase {

	public function test_block_is_registered(): void {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'pv-blocks-suite/tabs' ) );
	}

	public function test_tab_item_is_registered(): void {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'pv-blocks-suite/tab-item' ) );
	}

	/**
	 * Renders a `tabs` block with N `tab-item` children.
	 *
	 * @param array<int, array{label: string, tabId: string, content?: string}> $tabs Tab definitions.
	 */
	private function render_tabs( array $tabs ): string {
		$inner_blocks = [];

		foreach ( $tabs as $tab ) {
			$inner_html = $tab['content'] ?? '<p>Panel content</p>';

			$inner_blocks[] = [
				'blockName'    => 'pv-blocks-suite/tab-item',
				'attrs'        => [
					'label' => $tab['label'],
					'tabId' => $tab['tabId'],
				],
				'innerBlocks'  => [],
				'innerHTML'    => $inner_html,
				'innerContent' => [ $inner_html ],
			];
		}

		return (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/tabs',
				'attrs'        => [],
				'innerBlocks'  => $inner_blocks,
				'innerHTML'    => '',
				'innerContent' => array_fill( 0, count( $inner_blocks ), null ),
			]
		);
	}

	public function test_wrapper_has_the_block_class_and_interactive_directive(): void {
		$output = $this->render_tabs(
			[
				[
					'label' => 'Tab 1',
					'tabId' => 'tab-a',
				],
			]
		);

		$this->assertStringContainsString( 'wp-block-pv-blocks-suite-tabs', $output );
		$this->assertStringContainsString( 'data-wp-interactive="pv-blocks-suite/tabs"', $output );
	}

	public function test_tablist_contains_one_button_per_tab_with_correct_labels(): void {
		$output = $this->render_tabs(
			[
				[
					'label' => 'Overview',
					'tabId' => 'tab-a',
				],
				[
					'label' => 'Details',
					'tabId' => 'tab-b',
				],
			]
		);

		$this->assertStringContainsString( 'role="tablist"', $output );
		$this->assertSame( 2, substr_count( $output, 'role="tab"' ) );
		$this->assertStringContainsString( '>Overview</button>', $output );
		$this->assertStringContainsString( '>Details</button>', $output );
	}

	public function test_first_tab_is_selected_and_focusable_by_default(): void {
		$output = $this->render_tabs(
			[
				[
					'label' => 'Overview',
					'tabId' => 'tab-a',
				],
				[
					'label' => 'Details',
					'tabId' => 'tab-b',
				],
			]
		);

		// Attributes are each on their own line in render.php's markup, so
		// this checks presence per attribute rather than a single
		// contiguous, exact-whitespace substring.
		$this->assertMatchesRegularExpression(
			'/id="pv-tab-button-tab-a"[\s\S]*?aria-controls="pv-tab-panel-tab-a"[\s\S]*?aria-selected="true"[\s\S]*?tabindex="0"/',
			$output
		);
		$this->assertMatchesRegularExpression(
			'/id="pv-tab-button-tab-b"[\s\S]*?aria-controls="pv-tab-panel-tab-b"[\s\S]*?aria-selected="false"[\s\S]*?tabindex="-1"/',
			$output
		);
	}

	public function test_button_and_panel_ids_are_correctly_linked(): void {
		$output = $this->render_tabs(
			[
				[
					'label' => 'Overview',
					'tabId' => 'tab-a',
				],
			]
		);

		$this->assertStringContainsString( 'aria-controls="pv-tab-panel-tab-a"', $output );
		$this->assertStringContainsString( 'id="pv-tab-panel-tab-a"', $output );
		$this->assertStringContainsString( 'aria-labelledby="pv-tab-button-tab-a"', $output );
	}

	public function test_active_tab_context_matches_the_first_tab(): void {
		$output = $this->render_tabs(
			[
				[
					'label' => 'Overview',
					'tabId' => 'tab-a',
				],
				[
					'label' => 'Details',
					'tabId' => 'tab-b',
				],
			]
		);

		$this->assertStringContainsString( '"activeTabId":"tab-a"', $output );
	}

	public function test_panels_are_rendered_and_not_statically_hidden(): void {
		$output = $this->render_tabs(
			[
				[
					'label'   => 'Overview',
					'tabId'   => 'tab-a',
					'content' => '<p>Overview content</p>',
				],
				[
					'label'   => 'Details',
					'tabId'   => 'tab-b',
					'content' => '<p>Details content</p>',
				],
			]
		);

		$this->assertStringContainsString( '<p>Overview content</p>', $output );
		$this->assertStringContainsString( '<p>Details content</p>', $output );

		// Deliberate no-JS fallback: neither panel gets a static `hidden`
		// attribute server-side. See tab-item/render.php's docblock.
		$this->assertStringNotContainsString( ' hidden', $output );
	}

	public function test_tab_item_with_empty_tab_id_is_skipped_from_the_tablist(): void {
		$output = $this->render_tabs(
			[
				[
					'label' => 'Overview',
					'tabId' => '',
				],
				[
					'label' => 'Details',
					'tabId' => 'tab-b',
				],
			]
		);

		$this->assertSame( 1, substr_count( $output, 'role="tab"' ) );
		$this->assertStringNotContainsString( '>Overview</button>', $output );
		$this->assertStringContainsString( '>Details</button>', $output );
	}
}

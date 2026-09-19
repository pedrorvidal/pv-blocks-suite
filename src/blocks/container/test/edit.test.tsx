import { render, screen } from '@testing-library/react';

import Edit from '../edit';
import type { ContainerAttributes } from '../types';

// InnerBlocks needs a full BlockEditorProvider (Redux store, registered
// block types, etc.) to render for real. Our Edit component only cares
// that it's rendered as the block's content area, so stub it out rather
// than wiring up the entire block-editor runtime for this test.
//
// InspectorControls is left un-mocked but its Fill content isn't asserted
// on here: it only renders into a matching <InspectorControls.Slot />,
// which in the real editor lives inside <BlockInspector> together with
// grouping/tabs machinery that isn't worth reproducing just for this test.
jest.mock( '@wordpress/block-editor', () => {
	const actual = jest.requireActual( '@wordpress/block-editor' );
	return {
		...actual,
		InnerBlocks: () => <div data-testid="inner-blocks" />,
	};
} );

const baseAttributes: ContainerAttributes = {
	paddingTop: '2rem',
	paddingBottom: '2rem',
	paddingLeft: '1rem',
	paddingRight: '1rem',
	backgroundColor: '',
	backgroundImage: '',
	maxWidth: '1200px',
};

function renderEdit( attributes: ContainerAttributes ) {
	return render(
		<Edit
			attributes={ attributes }
			setAttributes={ jest.fn() }
			clientId="test-client-id"
			isSelected={ true }
			context={ {} }
			className=""
		/>
	);
}

describe( 'container block edit', () => {
	it( 'renders the InnerBlocks content area', () => {
		renderEdit( baseAttributes );

		expect( screen.getByTestId( 'inner-blocks' ) ).toBeInTheDocument();
	} );

	it( 'turns padding and max-width attributes into inline styles on the wrapper', () => {
		renderEdit( baseAttributes );

		const wrapper = screen.getByTestId( 'inner-blocks' ).parentElement;

		expect( wrapper ).toHaveStyle( {
			paddingTop: '2rem',
			paddingBottom: '2rem',
			paddingLeft: '1rem',
			paddingRight: '1rem',
			maxWidth: '1200px',
			marginLeft: 'auto',
			marginRight: 'auto',
		} );
	} );

	it( 'omits background styles entirely when no color or image is set', () => {
		renderEdit( baseAttributes );

		const wrapper = screen.getByTestId( 'inner-blocks' ).parentElement;

		expect( wrapper?.style.backgroundColor ).toBe( '' );
		expect( wrapper?.style.backgroundImage ).toBe( '' );
	} );

	it( 'applies a background color when one is set', () => {
		renderEdit( { ...baseAttributes, backgroundColor: '#ff0000' } );

		const wrapper = screen.getByTestId( 'inner-blocks' ).parentElement;

		expect( wrapper ).toHaveStyle( { backgroundColor: '#ff0000' } );
	} );
} );

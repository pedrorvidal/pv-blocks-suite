import { render, screen } from '@testing-library/react';

import Edit from '../edit';
import type { CardGridAttributes } from '../types';

jest.mock( '@wordpress/block-editor', () => {
	const actual = jest.requireActual( '@wordpress/block-editor' );
	return {
		...actual,
		InnerBlocks: () => <div data-testid="inner-blocks" />,
	};
} );

const baseAttributes: CardGridAttributes = {
	columns: 3,
};

function renderEdit( attributes: CardGridAttributes ) {
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

describe( 'card-grid block edit', () => {
	it( 'renders the InnerBlocks content area', () => {
		renderEdit( baseAttributes );

		expect( screen.getByTestId( 'inner-blocks' ) ).toBeInTheDocument();
	} );

	it( 'exposes the columns count as a CSS custom property', () => {
		renderEdit( { ...baseAttributes, columns: 4 } );

		const wrapper = screen.getByTestId( 'inner-blocks' ).parentElement;

		expect(
			wrapper?.style.getPropertyValue( '--card-grid-columns' )
		).toBe( '4' );
	} );
} );

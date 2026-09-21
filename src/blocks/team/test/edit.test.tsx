import { render, screen } from '@testing-library/react';

import Edit from '../edit';
import type { TeamAttributes } from '../types';

jest.mock( '@wordpress/block-editor', () => {
	const actual = jest.requireActual( '@wordpress/block-editor' );
	return {
		...actual,
		InnerBlocks: () => <div data-testid="inner-blocks" />,
	};
} );

const baseAttributes: TeamAttributes = {
	columns: 3,
};

function renderEdit( attributes: TeamAttributes ) {
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

describe( 'team block edit', () => {
	it( 'renders the InnerBlocks content area', () => {
		renderEdit( baseAttributes );

		expect( screen.getByTestId( 'inner-blocks' ) ).toBeInTheDocument();
	} );

	it( 'exposes the columns count as a CSS custom property', () => {
		renderEdit( { ...baseAttributes, columns: 4 } );

		const wrapper = screen.getByTestId( 'inner-blocks' ).parentElement;

		expect(
			wrapper?.style.getPropertyValue( '--team-columns' )
		).toBe( '4' );
	} );
} );

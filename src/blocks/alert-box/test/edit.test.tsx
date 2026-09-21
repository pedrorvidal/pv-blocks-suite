import { render, screen } from '@testing-library/react';

import Edit from '../edit';
import type { AlertBoxAttributes } from '../types';

const baseAttributes: AlertBoxAttributes = {
	variant: 'info',
	heading: '',
	message: '',
	showIcon: true,
};

function renderEdit( attributes: AlertBoxAttributes ) {
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

describe( 'alert-box block edit', () => {
	it( 'renders the heading and message fields', () => {
		renderEdit( {
			...baseAttributes,
			heading: 'Heads up',
			message: 'This action cannot be undone.',
		} );

		expect( screen.getByText( 'Heads up' ) ).toBeInTheDocument();
		expect(
			screen.getByText( 'This action cannot be undone.' )
		).toBeInTheDocument();
	} );

	it( 'applies the is-{variant} class to the wrapper', () => {
		renderEdit( { ...baseAttributes, variant: 'warning' } );

		// The visually-hidden label is a direct child of the block
		// wrapper (a sibling of the icon and content areas).
		const wrapper = screen.getByText( 'Warning:', {
			exact: false,
		} ).parentElement;

		expect( wrapper ).toHaveClass( 'is-warning' );
	} );

	it( 'shows the icon by default', () => {
		const { container } = renderEdit( baseAttributes );

		expect( container.querySelector( 'svg' ) ).toBeInTheDocument();
	} );

	it( 'hides the icon when showIcon is off', () => {
		const { container } = renderEdit( {
			...baseAttributes,
			showIcon: false,
		} );

		expect( container.querySelector( 'svg' ) ).not.toBeInTheDocument();
	} );

	it( 'always includes the screen-reader label, even with the icon hidden', () => {
		renderEdit( { ...baseAttributes, variant: 'error', showIcon: false } );

		expect(
			screen.getByText( 'Error:', { exact: false } )
		).toBeInTheDocument();
	} );
} );

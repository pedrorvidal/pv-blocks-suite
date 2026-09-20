import { render, screen } from '@testing-library/react';

import Edit from '../edit';
import type { CtaAttributes } from '../types';

const baseAttributes: CtaAttributes = {
	heading: '',
	description: '',
	buttonText: '',
	buttonUrl: '',
	buttonOpensInNewTab: false,
	textAlign: 'center',
	backgroundColor: '',
	backgroundImage: '',
	textColor: '',
	buttonBackgroundColor: '',
	buttonTextColor: '',
};

function renderEdit( attributes: CtaAttributes ) {
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

describe( 'cta block edit', () => {
	it( 'renders the heading, description, and button fields', () => {
		renderEdit( {
			...baseAttributes,
			heading: 'Sign up today',
			description: 'Join thousands of happy customers.',
			buttonText: 'Get started',
		} );

		expect( screen.getByText( 'Sign up today' ) ).toBeInTheDocument();
		expect(
			screen.getByText( 'Join thousands of happy customers.' )
		).toBeInTheDocument();
		expect( screen.getByText( 'Get started' ) ).toBeInTheDocument();
	} );

	it( 'turns textAlign and color attributes into inline styles on the wrapper', () => {
		renderEdit( {
			...baseAttributes,
			textAlign: 'right',
			textColor: '#ffffff',
			backgroundColor: '#000000',
		} );

		const wrapper = screen.getByLabelText( 'Add heading…' ).parentElement;

		expect( wrapper ).toHaveStyle( {
			textAlign: 'right',
			color: '#ffffff',
			backgroundColor: '#000000',
		} );
	} );

	it( 'omits background styles entirely when no color or image is set', () => {
		renderEdit( baseAttributes );

		const wrapper = screen.getByLabelText( 'Add heading…' ).parentElement;

		expect( wrapper?.style.backgroundColor ).toBe( '' );
		expect( wrapper?.style.backgroundImage ).toBe( '' );
	} );

	it( 'applies button background and text color as inline styles on the button', () => {
		renderEdit( {
			...baseAttributes,
			buttonBackgroundColor: '#ff0000',
			buttonTextColor: '#00ff00',
		} );

		const button = screen.getByLabelText( 'Add button text…' );

		expect( button ).toHaveStyle( {
			backgroundColor: '#ff0000',
			color: '#00ff00',
		} );
	} );

	it( 'applies overflow: hidden when a linked border radius is set', () => {
		renderEdit( {
			...baseAttributes,
			style: { border: { radius: '12px' } },
		} );

		const wrapper = screen.getByLabelText( 'Add heading…' ).parentElement;

		expect( wrapper ).toHaveStyle( { overflow: 'hidden' } );
	} );

	it( 'applies overflow: hidden when only one corner radius is unlinked and set', () => {
		renderEdit( {
			...baseAttributes,
			style: { border: { radius: { topLeft: '12px' } } },
		} );

		const wrapper = screen.getByLabelText( 'Add heading…' ).parentElement;

		expect( wrapper ).toHaveStyle( { overflow: 'hidden' } );
	} );

	it( 'omits overflow when no border radius is set', () => {
		renderEdit( baseAttributes );

		const wrapper = screen.getByLabelText( 'Add heading…' ).parentElement;

		expect( wrapper?.style.overflow ).toBe( '' );
	} );
} );

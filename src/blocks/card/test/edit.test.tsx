import { render, screen } from '@testing-library/react';

import Edit from '../edit';
import type { CardAttributes } from '../types';

const baseAttributes: CardAttributes = {
	imageUrl: '',
	imageAlt: '',
	heading: '',
	headingLevel: 3,
	description: '',
	buttonText: '',
	buttonUrl: '',
	buttonOpensInNewTab: false,
};

function renderEdit( attributes: CardAttributes ) {
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

describe( 'card block edit', () => {
	it( 'renders the heading, description, and button fields', () => {
		renderEdit( {
			...baseAttributes,
			heading: 'Fast',
			description: 'Built for speed.',
			buttonText: 'Learn more',
		} );

		expect( screen.getByText( 'Fast' ) ).toBeInTheDocument();
		expect( screen.getByText( 'Built for speed.' ) ).toBeInTheDocument();
		expect( screen.getByText( 'Learn more' ) ).toBeInTheDocument();
	} );

	it( 'wraps the heading in a tag matching headingLevel', () => {
		renderEdit( { ...baseAttributes, heading: 'Fast', headingLevel: 4 } );

		expect( screen.getByText( 'Fast' ).closest( 'h4' ) ).not.toBeNull();
	} );

	it( 'does not render an image when imageUrl is empty', () => {
		renderEdit( baseAttributes );

		expect( screen.queryByRole( 'img' ) ).not.toBeInTheDocument();
	} );

	it( 'renders the image with its alt text', () => {
		renderEdit( {
			...baseAttributes,
			imageUrl: 'https://example.org/photo.jpg',
			imageAlt: 'A mountain',
		} );

		const image = screen.getByRole( 'img' );

		expect( image ).toHaveAttribute(
			'src',
			'https://example.org/photo.jpg'
		);
		expect( image ).toHaveAttribute( 'alt', 'A mountain' );
	} );

	it( 'applies overflow: hidden when a border radius is set', () => {
		renderEdit( {
			...baseAttributes,
			style: { border: { radius: '12px' } },
		} );

		// "Card title" (the RichText span) is wrapped by the heading tag,
		// which is itself a direct child of the block wrapper — two
		// levels up, matching the structure written in edit.tsx.
		const wrapper =
			screen.getByLabelText( 'Card title' ).parentElement
				?.parentElement;

		expect( wrapper ).toHaveStyle( { overflow: 'hidden' } );
	} );

	it( 'omits overflow when no border radius is set', () => {
		renderEdit( baseAttributes );

		const wrapper =
			screen.getByLabelText( 'Card title' ).parentElement
				?.parentElement;

		expect( wrapper?.style.overflow ).toBe( '' );
	} );
} );

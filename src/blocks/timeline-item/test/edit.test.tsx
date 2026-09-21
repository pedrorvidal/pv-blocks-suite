import { render, screen } from '@testing-library/react';

import Edit from '../edit';
import type { TimelineItemAttributes } from '../types';

const baseAttributes: TimelineItemAttributes = {
	imageUrl: '',
	imageAlt: '',
	date: '',
	heading: '',
	headingLevel: 3,
	description: '',
};

function renderEdit( attributes: TimelineItemAttributes ) {
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

describe( 'timeline-item block edit', () => {
	it( 'renders as a real list item', () => {
		const { container } = renderEdit( baseAttributes );

		expect( container.querySelector( 'li' ) ).not.toBeNull();
	} );

	it( 'renders the date, heading, and description fields', () => {
		renderEdit( {
			...baseAttributes,
			date: '2024',
			heading: 'Series A funding',
			description: 'Raised funding to accelerate growth.',
		} );

		expect( screen.getByText( '2024' ) ).toBeInTheDocument();
		expect( screen.getByText( 'Series A funding' ) ).toBeInTheDocument();
		expect(
			screen.getByText( 'Raised funding to accelerate growth.' )
		).toBeInTheDocument();
	} );

	it( 'wraps the heading in a tag matching headingLevel', () => {
		renderEdit( {
			...baseAttributes,
			heading: 'Series A funding',
			headingLevel: 4,
		} );

		expect(
			screen.getByText( 'Series A funding' ).closest( 'h4' )
		).not.toBeNull();
	} );

	it( 'does not render an image when imageUrl is empty', () => {
		renderEdit( baseAttributes );

		expect( screen.queryByRole( 'img' ) ).not.toBeInTheDocument();
	} );

	it( 'renders the image with its alt text', () => {
		renderEdit( {
			...baseAttributes,
			imageUrl: 'https://example.org/photo.jpg',
			imageAlt: 'A launch event',
		} );

		const image = screen.getByRole( 'img' );

		expect( image ).toHaveAttribute(
			'src',
			'https://example.org/photo.jpg'
		);
		expect( image ).toHaveAttribute( 'alt', 'A launch event' );
	} );
} );

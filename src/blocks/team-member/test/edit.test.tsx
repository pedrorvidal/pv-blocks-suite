import { render, screen } from '@testing-library/react';

import Edit from '../edit';
import type { TeamMemberAttributes } from '../types';

const baseAttributes: TeamMemberAttributes = {
	avatarUrl: '',
	avatarAlt: '',
	name: '',
	headingLevel: 3,
	role: '',
	bio: '',
};

function renderEdit( attributes: TeamMemberAttributes ) {
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

describe( 'team-member block edit', () => {
	it( 'renders the name, role, and bio fields', () => {
		renderEdit( {
			...baseAttributes,
			name: 'Alex Morgan',
			role: 'CEO & Founder',
			bio: 'Leads the company.',
		} );

		expect( screen.getByText( 'Alex Morgan' ) ).toBeInTheDocument();
		expect( screen.getByText( 'CEO & Founder' ) ).toBeInTheDocument();
		expect( screen.getByText( 'Leads the company.' ) ).toBeInTheDocument();
	} );

	it( 'wraps the name in a tag matching headingLevel', () => {
		renderEdit( { ...baseAttributes, name: 'Alex Morgan', headingLevel: 4 } );

		expect( screen.getByText( 'Alex Morgan' ).closest( 'h4' ) ).not.toBeNull();
	} );

	it( 'does not render a role paragraph when role is empty', () => {
		renderEdit( baseAttributes );

		expect( screen.queryByText( 'CEO & Founder' ) ).not.toBeInTheDocument();
	} );

	it( 'does not render an image when avatarUrl is empty', () => {
		renderEdit( baseAttributes );

		expect( screen.queryByRole( 'img' ) ).not.toBeInTheDocument();
	} );

	it( 'renders the avatar with its alt text', () => {
		renderEdit( {
			...baseAttributes,
			avatarUrl: 'https://example.org/photo.jpg',
			avatarAlt: 'Alex Morgan',
		} );

		const image = screen.getByRole( 'img' );

		expect( image ).toHaveAttribute(
			'src',
			'https://example.org/photo.jpg'
		);
		expect( image ).toHaveAttribute( 'alt', 'Alex Morgan' );
	} );

	it( 'applies overflow: hidden when a border radius is set', () => {
		renderEdit( {
			...baseAttributes,
			style: { border: { radius: '12px' } },
		} );

		// "Name" (the RichText placeholder) is wrapped by the heading tag,
		// which is itself a direct child of the block wrapper — two
		// levels up, matching the structure written in edit.tsx.
		const wrapper =
			screen.getByLabelText( 'Name' ).parentElement?.parentElement;

		expect( wrapper ).toHaveStyle( { overflow: 'hidden' } );
	} );

	it( 'omits overflow when no border radius is set', () => {
		renderEdit( baseAttributes );

		const wrapper =
			screen.getByLabelText( 'Name' ).parentElement?.parentElement;

		expect( wrapper?.style.overflow ).toBe( '' );
	} );
} );

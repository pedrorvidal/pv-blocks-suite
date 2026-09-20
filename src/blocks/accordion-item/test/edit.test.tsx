import { render, screen } from '@testing-library/react';

import Edit from '../edit';
import type { AccordionItemAttributes } from '../types';

jest.mock( '@wordpress/block-editor', () => {
	const actual = jest.requireActual( '@wordpress/block-editor' );
	return {
		...actual,
		InnerBlocks: () => <div data-testid="inner-blocks" />,
	};
} );

const baseAttributes: AccordionItemAttributes = {
	summary: '',
	headingLevel: 3,
	openByDefault: false,
};

function renderEdit( attributes: AccordionItemAttributes ) {
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

describe( 'accordion-item block edit', () => {
	it( 'renders the summary text and the content area', () => {
		renderEdit( { ...baseAttributes, summary: 'Question text' } );

		expect( screen.getByText( 'Question text' ) ).toBeInTheDocument();
		expect( screen.getByTestId( 'inner-blocks' ) ).toBeInTheDocument();
	} );

	it( 'shows a placeholder when the summary is empty', () => {
		renderEdit( baseAttributes );

		expect(
			screen.getByLabelText( 'Add a question…' )
		).toBeInTheDocument();
	} );

	it( 'wraps the question in a heading matching headingLevel', () => {
		renderEdit( { ...baseAttributes, summary: 'Question text', headingLevel: 4 } );

		const heading = screen.getByRole( 'heading', { level: 4 } );

		expect( heading ).toHaveTextContent( 'Question text' );
	} );
} );

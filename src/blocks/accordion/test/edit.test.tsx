import { render, screen } from '@testing-library/react';

import Edit from '../edit';
import type { AccordionAttributes } from '../types';

// InnerBlocks needs a full BlockEditorProvider to render for real; the
// Edit component only cares that it's rendered as the block's content
// area, so stub it out (same approach as container's edit.test.tsx).
jest.mock( '@wordpress/block-editor', () => {
	const actual = jest.requireActual( '@wordpress/block-editor' );
	return {
		...actual,
		InnerBlocks: () => <div data-testid="inner-blocks" />,
	};
} );

const baseAttributes: AccordionAttributes = {
	groupId: '',
	allowMultipleOpen: false,
};

function renderEdit(
	attributes: AccordionAttributes,
	clientId = 'test-client-id'
) {
	const setAttributes = jest.fn();
	const utils = render(
		<Edit
			attributes={ attributes }
			setAttributes={ setAttributes }
			clientId={ clientId }
			isSelected={ true }
			context={ {} }
			className=""
		/>
	);
	return { ...utils, setAttributes };
}

describe( 'accordion block edit', () => {
	it( 'renders the InnerBlocks content area', () => {
		renderEdit( baseAttributes );

		expect( screen.getByTestId( 'inner-blocks' ) ).toBeInTheDocument();
	} );

	it( 'persists the client id as groupId when none is set yet', () => {
		const { setAttributes } = renderEdit( baseAttributes, 'abc-123' );

		expect( setAttributes ).toHaveBeenCalledWith( { groupId: 'abc-123' } );
	} );

	it( 'does not overwrite an existing groupId', () => {
		const { setAttributes } = renderEdit(
			{ ...baseAttributes, groupId: 'already-set' },
			'abc-123'
		);

		expect( setAttributes ).not.toHaveBeenCalled();
	} );
} );

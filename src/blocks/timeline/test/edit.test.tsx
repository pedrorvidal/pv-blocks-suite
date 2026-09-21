import { render, screen } from '@testing-library/react';

import Edit from '../edit';
import type { TimelineAttributes } from '../types';

jest.mock( '@wordpress/block-editor', () => {
	const actual = jest.requireActual( '@wordpress/block-editor' );
	return {
		...actual,
		InnerBlocks: () => <div data-testid="inner-blocks" />,
	};
} );

function renderEdit( attributes: TimelineAttributes = {} ) {
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

describe( 'timeline block edit', () => {
	it( 'renders the InnerBlocks content area inside an ordered list', () => {
		renderEdit();

		const innerBlocks = screen.getByTestId( 'inner-blocks' );

		expect( innerBlocks ).toBeInTheDocument();
		expect( innerBlocks.closest( 'ol' ) ).not.toBeNull();
	} );
} );

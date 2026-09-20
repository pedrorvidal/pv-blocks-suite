import { render, screen } from '@testing-library/react';

import Edit from '../edit';
import type { PricingTableAttributes } from '../types';

jest.mock( '@wordpress/block-editor', () => {
	const actual = jest.requireActual( '@wordpress/block-editor' );
	return {
		...actual,
		InnerBlocks: () => <div data-testid="inner-blocks" />,
	};
} );

function renderEdit( attributes: PricingTableAttributes = {} ) {
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

describe( 'pricing-table block edit', () => {
	it( 'renders the InnerBlocks content area', () => {
		renderEdit();

		expect( screen.getByTestId( 'inner-blocks' ) ).toBeInTheDocument();
	} );
} );

import { render, screen } from '@testing-library/react';

import Edit from '../edit';
import type { PricingPlanAttributes } from '../types';

jest.mock( '@wordpress/block-editor', () => {
	const actual = jest.requireActual( '@wordpress/block-editor' );
	return {
		...actual,
		InnerBlocks: () => <div data-testid="inner-blocks" />,
	};
} );

const baseAttributes: PricingPlanAttributes = {
	planName: '',
	headingLevel: 3,
	price: '',
	pricePeriod: '',
	description: '',
	buttonText: '',
	buttonUrl: '',
	buttonOpensInNewTab: false,
	isFeatured: false,
	featuredLabel: 'Most Popular',
	featuredBackgroundColor: '',
	featuredTextColor: '',
};

function renderEdit( attributes: PricingPlanAttributes ) {
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

describe( 'pricing-plan block edit', () => {
	it( 'renders the plan name, price, description, and features area', () => {
		renderEdit( {
			...baseAttributes,
			planName: 'Pro',
			price: '$29',
			pricePeriod: '/month',
			description: 'For growing teams.',
		} );

		expect( screen.getByText( 'Pro' ) ).toBeInTheDocument();
		expect( screen.getByText( '$29' ) ).toBeInTheDocument();
		expect( screen.getByText( '/month' ) ).toBeInTheDocument();
		expect( screen.getByText( 'For growing teams.' ) ).toBeInTheDocument();
		expect( screen.getByTestId( 'inner-blocks' ) ).toBeInTheDocument();
	} );

	it( 'wraps the plan name in a heading matching headingLevel', () => {
		renderEdit( { ...baseAttributes, planName: 'Pro', headingLevel: 4 } );

		const heading = screen.getByRole( 'heading', { level: 4 } );

		expect( heading ).toHaveTextContent( 'Pro' );
	} );

	it( 'shows the featured badge when isFeatured is true', () => {
		renderEdit( {
			...baseAttributes,
			isFeatured: true,
			featuredLabel: 'Best Value',
		} );

		expect( screen.getByText( 'Best Value' ) ).toBeInTheDocument();
	} );

	it( 'omits the featured badge by default', () => {
		renderEdit( baseAttributes );

		expect( screen.queryByText( 'Most Popular' ) ).not.toBeInTheDocument();
	} );

	it( 'applies featured colors to the wrapper only when featured', () => {
		renderEdit( {
			...baseAttributes,
			isFeatured: true,
			featuredBackgroundColor: '#1e1e1e',
			featuredTextColor: '#ffffff',
		} );

		const wrapper = screen.getByText( 'Most Popular' ).parentElement;

		expect( wrapper ).toHaveStyle( {
			backgroundColor: '#1e1e1e',
			color: '#ffffff',
		} );
	} );

	it( 'ignores featured colors when isFeatured is off', () => {
		renderEdit( {
			...baseAttributes,
			isFeatured: false,
			featuredBackgroundColor: '#1e1e1e',
			featuredTextColor: '#ffffff',
		} );

		// "Plan name" (the RichText span) is wrapped by the heading tag,
		// which is itself a direct child of the block wrapper — two
		// levels up, matching the structure written in edit.tsx.
		const wrapper =
			screen.getByLabelText( 'Plan name' ).parentElement?.parentElement;

		expect( wrapper?.style.backgroundColor ).toBe( '' );
		expect( wrapper?.style.color ).toBe( '' );
	} );
} );

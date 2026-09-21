import { render, screen } from '@testing-library/react';

import Edit from '../edit';
import type { BeforeAfterAttributes } from '../types';

const baseAttributes: BeforeAfterAttributes = {
    beforeImageUrl: '',
    beforeImageAlt: '',
    afterImageUrl: '',
    afterImageAlt: '',
    beforeLabel: 'Before',
    afterLabel: 'After',
    showLabels: true,
    initialPosition: 50,
    aspectRatio: '16:9',
};

function renderEdit( attributes: BeforeAfterAttributes ) {
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

describe( 'before-after block edit', () => {
    it( 'renders both images when URLs are set', () => {
        const { container } = renderEdit( {
            ...baseAttributes,
            beforeImageUrl: 'https://example.com/before.jpg',
            afterImageUrl: 'https://example.com/after.jpg',
        } );

        expect(
            container.querySelector(
                '.wp-block-pv-blocks-suite-before-after__image--before'
            )
        ).toHaveAttribute( 'src', 'https://example.com/before.jpg' );
        expect(
            container.querySelector(
                '.wp-block-pv-blocks-suite-before-after__image--after'
            )
        ).toHaveAttribute( 'src', 'https://example.com/after.jpg' );
    } );

    it( 'omits an image element when its URL is empty', () => {
        const { container } = renderEdit( baseAttributes );

        expect(
            container.querySelector(
                '.wp-block-pv-blocks-suite-before-after__image--before'
            )
        ).not.toBeInTheDocument();
    } );

    it( 'shows the labels by default', () => {
        renderEdit( baseAttributes );

        expect( screen.getByText( 'Before' ) ).toBeInTheDocument();
        expect( screen.getByText( 'After' ) ).toBeInTheDocument();
    } );

    it( 'hides the labels when showLabels is off', () => {
        renderEdit( { ...baseAttributes, showLabels: false } );

        expect( screen.queryByText( 'Before' ) ).not.toBeInTheDocument();
        expect( screen.queryByText( 'After' ) ).not.toBeInTheDocument();
    } );

    it( "reflects initialPosition in the handle's and after-wrap's inline style", () => {
        const { container } = renderEdit( {
            ...baseAttributes,
            initialPosition: 30,
        } );

        const handle = container.querySelector(
            '.wp-block-pv-blocks-suite-before-after__handle'
        );
        const afterWrap = container.querySelector(
            '.wp-block-pv-blocks-suite-before-after__after-wrap'
        );

        expect( handle ).toHaveStyle( { left: '30%' } );
        expect( afterWrap ).toHaveStyle( {
            clipPath: 'inset(0 70% 0 0)',
        } );
    } );

    // jsdom's bundled cssstyle package (2.3.0) doesn't know the CSS
    // `aspect-ratio` property, so `element.style.aspectRatio = '...'` is a
    // silent no-op here — it never reaches the rendered `style` attribute
    // in this test environment at all, unlike a real browser. There's
    // nothing observable to assert on for the actual mapped value from
    // this environment (same class of gap as this project's documented
    // InspectorControls/SlotFillProvider Jest limitation) — these two
    // cases (a recognized ratio, and an unrecognized one falling back to
    // 16:9) are instead covered by Before_After_Render_Test.php, where
    // the equivalent PHP-side mapping has no such limitation. This test
    // only guards against the component throwing for either input.
    it( 'renders without error for a custom and an unrecognized aspect ratio', () => {
        expect( () =>
            renderEdit( { ...baseAttributes, aspectRatio: '1:1' } )
        ).not.toThrow();
        expect( () =>
            renderEdit( { ...baseAttributes, aspectRatio: 'not-a-real-ratio' } )
        ).not.toThrow();
    } );
} );

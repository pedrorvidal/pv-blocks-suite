import { render, screen } from '@testing-library/react';

import Edit from '../edit';
import type { StatsCounterAttributes } from '../types';

jest.mock('@wordpress/block-editor', () => {
    const actual = jest.requireActual('@wordpress/block-editor');
    return {
        ...actual,
        InnerBlocks: () => <div data-testid="inner-blocks" />,
    };
});

const baseAttributes: StatsCounterAttributes = {
    columns: 3,
    animationDuration: 2000,
};

function renderEdit(attributes: StatsCounterAttributes) {
    return render(
        <Edit
            attributes={attributes}
            setAttributes={jest.fn()}
            clientId="test-client-id"
            isSelected={true}
            context={{}}
            className=""
        />
    );
}

describe('stats-counter block edit', () => {
    it('renders the InnerBlocks content area', () => {
        renderEdit(baseAttributes);

        expect(screen.getByTestId('inner-blocks')).toBeInTheDocument();
    });

    it('exposes the columns count as a CSS custom property', () => {
        renderEdit({ ...baseAttributes, columns: 4 });

        const wrapper = screen.getByTestId('inner-blocks').parentElement;

        expect(
            wrapper?.style.getPropertyValue('--stats-counter-columns')
        ).toBe('4');
    });
});

import { render, screen } from '@testing-library/react';

import Edit from '../edit';
import type { StatItemAttributes } from '../types';

const baseAttributes: StatItemAttributes = {
    value: 100,
    decimals: 0,
    prefix: '',
    suffix: '+',
    label: '',
};

function renderEdit(attributes: StatItemAttributes) {
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

describe('stat-item block edit', () => {
    it('renders the formatted value', () => {
        renderEdit({ ...baseAttributes, value: 500, suffix: '+' });

        expect(screen.getByText('500+')).toBeInTheDocument();
    });

    it('applies the prefix and suffix together', () => {
        renderEdit({ ...baseAttributes, value: 99, prefix: '$', suffix: 'k' });

        expect(screen.getByText('$99k')).toBeInTheDocument();
    });

    it('formats decimal places', () => {
        renderEdit({
            ...baseAttributes,
            value: 4.9,
            decimals: 1,
            prefix: '',
            suffix: '',
        });

        expect(screen.getByText('4.9')).toBeInTheDocument();
    });

    it('adds thousands separators for large values', () => {
        renderEdit({ ...baseAttributes, value: 12000, suffix: '' });

        expect(screen.getByText('12,000')).toBeInTheDocument();
    });

    it('renders the label placeholder when empty', () => {
        renderEdit(baseAttributes);

        expect(screen.getByLabelText('Add a label…')).toBeInTheDocument();
    });
});

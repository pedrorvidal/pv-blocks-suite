import { render, screen } from '@testing-library/react';

import Edit from '../edit';
import type { TabItemAttributes } from '../types';

jest.mock('@wordpress/block-editor', () => {
    const actual = jest.requireActual('@wordpress/block-editor');
    return {
        ...actual,
        InnerBlocks: () => <div data-testid="inner-blocks" />,
    };
});

const baseAttributes: TabItemAttributes = {
    label: '',
    tabId: '',
};

function renderEdit(
    attributes: TabItemAttributes,
    clientId = 'test-client-id'
) {
    const setAttributes = jest.fn();
    const utils = render(
        <Edit
            attributes={attributes}
            setAttributes={setAttributes}
            clientId={clientId}
            isSelected={true}
            context={{}}
            className=""
        />
    );
    return { ...utils, setAttributes };
}

describe('tab-item block edit', () => {
    it('renders the label and the content area', () => {
        renderEdit({ ...baseAttributes, label: 'Overview' });

        expect(screen.getByText('Overview')).toBeInTheDocument();
        expect(screen.getByTestId('inner-blocks')).toBeInTheDocument();
    });

    it('shows a placeholder when the label is empty', () => {
        renderEdit(baseAttributes);

        expect(screen.getByLabelText('Tab label…')).toBeInTheDocument();
    });

    it('persists the client id as tabId when none is set yet', () => {
        const { setAttributes } = renderEdit(baseAttributes, 'abc-123');

        expect(setAttributes).toHaveBeenCalledWith({ tabId: 'abc-123' });
    });

    it('does not overwrite an existing tabId', () => {
        const { setAttributes } = renderEdit(
            { ...baseAttributes, tabId: 'already-set' },
            'abc-123'
        );

        expect(setAttributes).not.toHaveBeenCalled();
    });
});

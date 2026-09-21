import { render, screen } from '@testing-library/react';

import Edit from '../edit';

// InnerBlocks needs a full BlockEditorProvider to render for real; the
// Edit component only cares that it's rendered as the block's content
// area, so stub it out (same approach as accordion's edit.test.tsx).
jest.mock('@wordpress/block-editor', () => {
    const actual = jest.requireActual('@wordpress/block-editor');
    return {
        ...actual,
        InnerBlocks: () => <div data-testid="inner-blocks" />,
    };
});

describe('tabs block edit', () => {
    it('renders the InnerBlocks content area', () => {
        render(<Edit />);

        expect(screen.getByTestId('inner-blocks')).toBeInTheDocument();
    });
});

import { render, screen } from '@testing-library/react';

import Edit from '../edit';
import type { TestimonialItemAttributes } from '../types';

const baseAttributes: TestimonialItemAttributes = {
    avatarUrl: '',
    avatarAlt: '',
    quote: '',
    name: '',
    role: '',
    rating: 5,
    showRating: true,
};

function renderEdit(attributes: TestimonialItemAttributes) {
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

describe('testimonial-item block edit', () => {
    it('renders the quote and name fields', () => {
        renderEdit({
            ...baseAttributes,
            quote: 'This product changed how we work.',
            name: 'Alex Rivera',
        });

        expect(
            screen.getByText('This product changed how we work.')
        ).toBeInTheDocument();
        expect(screen.getByText('Alex Rivera')).toBeInTheDocument();
    });

    it('renders the role text when set', () => {
        renderEdit({ ...baseAttributes, role: 'CTO, Nimbus Labs' });

        expect(screen.getByText('CTO, Nimbus Labs')).toBeInTheDocument();
    });

    it('does not render an image when avatarUrl is empty', () => {
        renderEdit(baseAttributes);

        expect(screen.queryByRole('img')).not.toBeInTheDocument();
    });

    it('renders the avatar image with its alt text', () => {
        renderEdit({
            ...baseAttributes,
            avatarUrl: 'https://example.org/photo.jpg',
            avatarAlt: 'Portrait of Alex Rivera',
        });

        const image = screen.getByRole('img');

        expect(image).toHaveAttribute(
            'src',
            'https://example.org/photo.jpg'
        );
        expect(image).toHaveAttribute('alt', 'Portrait of Alex Rivera');
    });

    it('renders five stars matching the configured rating', () => {
        const { container } = renderEdit({ ...baseAttributes, rating: 3 });

        const stars = container.querySelectorAll(
            '.wp-block-pv-blocks-suite-testimonial-item__star'
        );

        expect(stars).toHaveLength(5);
    });

    it('does not render the rating when showRating is false', () => {
        const { container } = renderEdit({
            ...baseAttributes,
            showRating: false,
        });

        expect(
            container.querySelector(
                '.wp-block-pv-blocks-suite-testimonial-item__rating'
            )
        ).not.toBeInTheDocument();
    });

    it('applies overflow: hidden when a border radius is set', () => {
        const { container } = renderEdit({
            ...baseAttributes,
            style: { border: { radius: '12px' } },
        });

        const wrapper = container.firstElementChild as HTMLElement;

        expect(wrapper).toHaveStyle({ overflow: 'hidden' });
    });

    it('omits overflow when no border radius is set', () => {
        const { container } = renderEdit(baseAttributes);

        const wrapper = container.firstElementChild as HTMLElement;

        expect(wrapper.style.overflow).toBe('');
    });
});

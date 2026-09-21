import {
    store,
    getContext,
    getElement,
    withSyncEvent,
} from '@wordpress/interactivity';

interface BeforeAfterContext {
    position: number;
    isDragging: boolean;
}

// Event objects passed to actions are wrapped to warn (in SCRIPT_DEBUG) on
// synchronous access to `currentTarget`/`preventDefault`/`stopPropagation`/
// `stopImmediatePropagation` unless the action is wrapped in
// withSyncEvent(). Rather than reach for withSyncEvent() everywhere, the
// pointer handlers below avoid those members entirely: getElement().ref
// (the real DOM node) stands in for event.currentTarget, and
// touch-action: none / user-select: none in style.scss stand in for
// preventDefault(). onKeyDown is the one handler that legitimately needs
// preventDefault() (to stop arrow keys from doing anything else while the
// handle has focus), so it's the only one wrapped in withSyncEvent().
const { actions } = store<{
    state: {
        clipPath: string;
        handleLeft: string;
        roundedPosition: number;
    };
    actions: {
        setPositionFromClientX: (clientX: number) => void;
        onPointerDown: (event: PointerEvent) => void;
        onPointerMove: (event: PointerEvent) => void;
        onPointerUp: (event: PointerEvent) => void;
        onKeyDown: (event: KeyboardEvent) => void;
    };
}>('pv-blocks-suite/before-after', {
    state: {
        get clipPath(): string {
            const { position } = getContext<BeforeAfterContext>();
            return `inset(0 ${100 - position}% 0 0)`;
        },
        get handleLeft(): string {
            const { position } = getContext<BeforeAfterContext>();
            return `${position}%`;
        },
        get roundedPosition(): number {
            const { position } = getContext<BeforeAfterContext>();
            return Math.round(position);
        },
    },
    actions: {
        setPositionFromClientX(clientX: number) {
            const context = getContext<BeforeAfterContext>();
            const { ref } = getElement();
            const rect = ref?.getBoundingClientRect();

            if (!rect || rect.width === 0) {
                return;
            }

            const ratio = (clientX - rect.left) / rect.width;
            context.position = Math.min(100, Math.max(0, ratio * 100));
        },
        onPointerDown(event: PointerEvent) {
            const context = getContext<BeforeAfterContext>();
            context.isDragging = true;
            actions.setPositionFromClientX(event.clientX);
            getElement().ref?.setPointerCapture(event.pointerId);
        },
        onPointerMove(event: PointerEvent) {
            const context = getContext<BeforeAfterContext>();

            if (!context.isDragging) {
                return;
            }

            actions.setPositionFromClientX(event.clientX);
        },
        onPointerUp(event: PointerEvent) {
            const context = getContext<BeforeAfterContext>();
            context.isDragging = false;
            getElement().ref?.releasePointerCapture(event.pointerId);
        },
        onKeyDown: withSyncEvent((event: KeyboardEvent) => {
            const context = getContext<BeforeAfterContext>();
            const step = event.shiftKey ? 10 : 2;

            switch (event.key) {
                case 'ArrowLeft':
                case 'ArrowDown':
                    event.preventDefault();
                    context.position = Math.max(0, context.position - step);
                    break;
                case 'ArrowRight':
                case 'ArrowUp':
                    event.preventDefault();
                    context.position = Math.min(100, context.position + step);
                    break;
                case 'Home':
                    event.preventDefault();
                    context.position = 0;
                    break;
                case 'End':
                    event.preventDefault();
                    context.position = 100;
                    break;
                default:
                    break;
            }
        }),
    },
});

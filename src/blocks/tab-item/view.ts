import { store, getContext, getElement, withSyncEvent } from '@wordpress/interactivity';

interface TabsContext {
    activeTabId: string;
}

interface TabButtonContext extends TabsContext {
    tabId: string;
}

const NAVIGATION_KEYS = ['ArrowLeft', 'ArrowRight', 'Home', 'End'];

// Registered once here (the child), even though both the parent's
// tablist buttons and this block's own panels reference this same
// namespace's state/actions — matches stat-item's precedent of the
// interactive child owning the store definition for its whole family.
store<{
    state: {
        ariaSelected: 'true' | 'false';
        tabIndexValue: number;
        isHidden: boolean;
    };
    actions: {
        selectTab: () => void;
        onTabKeyDown: (event: KeyboardEvent) => void;
    };
}>('pv-blocks-suite/tabs', {
    state: {
        get ariaSelected(): 'true' | 'false' {
            const { activeTabId, tabId } = getContext<TabButtonContext>();
            return activeTabId === tabId ? 'true' : 'false';
        },
        get tabIndexValue(): number {
            const { activeTabId, tabId } = getContext<TabButtonContext>();
            return activeTabId === tabId ? 0 : -1;
        },
        get isHidden(): boolean {
            const { activeTabId, tabId } = getContext<TabButtonContext>();
            return activeTabId !== tabId;
        },
    },
    actions: {
        selectTab() {
            const context = getContext<TabButtonContext>();

            // `activeTabId` isn't declared in this button's own local
            // context object (only `tabId` is) — per the proxy `set`
            // trap in @wordpress/interactivity's proxies/context.ts,
            // writing to a key that only exists in the inherited
            // (ancestor) context writes through to that shared object
            // instead of shadowing it locally, so this correctly updates
            // every sibling tab button and panel reading the same
            // `activeTabId`, not just this one button.
            context.activeTabId = context.tabId;
        },
        onTabKeyDown: withSyncEvent((event: KeyboardEvent) => {
            if (!NAVIGATION_KEYS.includes(event.key)) {
                return;
            }

            // Stops the arrow keys from doing anything else (e.g. page
            // scroll) while a tab button has focus — same withSyncEvent()
            // use as before-after's keyboard handler.
            event.preventDefault();

            const { ref } = getElement();
            const tablist = ref?.closest<HTMLElement>('[role="tablist"]');
            if (!ref || !tablist) {
                return;
            }

            // No built-in "list of my siblings" API in the Interactivity
            // API — plain DOM traversal is the simplest correct way to
            // find the ordered set of tab buttons for arrow-key
            // navigation.
            const buttons = Array.from(
                tablist.querySelectorAll<HTMLElement>('[role="tab"]')
            );
            const currentIndex = buttons.indexOf(ref);
            if (currentIndex === -1) {
                return;
            }

            let nextIndex = currentIndex;
            if (event.key === 'ArrowLeft') {
                nextIndex = (currentIndex - 1 + buttons.length) % buttons.length;
            } else if (event.key === 'ArrowRight') {
                nextIndex = (currentIndex + 1) % buttons.length;
            } else if (event.key === 'Home') {
                nextIndex = 0;
            } else if (event.key === 'End') {
                nextIndex = buttons.length - 1;
            }

            const nextButton = buttons[nextIndex];
            const nextTabId = nextButton?.dataset.tabId;
            if (!nextButton || !nextTabId) {
                return;
            }

            const context = getContext<TabsContext>();
            context.activeTabId = nextTabId;
            nextButton.focus();
        }),
    },
});

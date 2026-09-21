import { store, getContext, getElement, withScope } from '@wordpress/interactivity';

interface StatItemContext {
    targetValue: number;
    displayValue: number;
    decimals: number;
    prefix: string;
    suffix: string;
    duration: number;
    hasAnimated: boolean;
}

function easeOutCubic(t: number): number {
    return 1 - Math.pow(1 - t, 3);
}

// Locale hardcoded to 'en-US' rather than left undefined/browser-dependent,
// so the client-side animated formatting always matches the server-rendered
// value (PHP's number_format() always uses ','/'.' regardless of visitor
// locale) — leaving the locale undefined would let a non-English browser
// locale render differently client-side than the initial server-rendered
// markup, a real, if subtle, inconsistency.
function formatNumber(value: number, decimals: number): string {
    return value.toLocaleString('en-US', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    });
}

// getContext()/getElement() only work from within an active "scope" —
// synchronously inside a directive's evaluation, or inside a callback
// WordPress itself already wrapped in scope-restoring logic (data-wp-init's
// callback, data-wp-on--*'s handler, etc.). An IntersectionObserver's own
// callback, and a requestAnimationFrame tick loop, are NOT automatically
// scoped — they run later, invoked by the browser outside any Preact
// render cycle. Calling getContext() inside them directly throws "Cannot
// call getContext() when there is no scope" at runtime — a failure no
// automated check here (tsc/build/Jest/PHPUnit) can catch. withScope()
// captures the scope active when it's called and re-establishes it when
// the wrapped function actually runs, which is exactly what both
// callbacks below need.
const { actions } = store<{
    state: {
        formattedValue: string;
    };
    actions: {
        animateCount: () => void;
    };
    callbacks: {
        startObserving: () => (() => void) | void;
    };
}>('pv-blocks-suite/stat-item', {
    state: {
        get formattedValue(): string {
            const { displayValue, decimals, prefix, suffix } =
                getContext<StatItemContext>();
            return `${prefix}${formatNumber(displayValue, decimals)}${suffix}`;
        },
    },
    actions: {
        animateCount() {
            const context = getContext<StatItemContext>();
            const target = context.targetValue;
            const duration = context.duration;
            const start = performance.now();
            context.displayValue = 0;

            const tick = withScope((now: number) => {
                const ctx = getContext<StatItemContext>();
                const elapsed = now - start;
                const progress = Math.min(1, elapsed / duration);
                ctx.displayValue = target * easeOutCubic(progress);

                if (progress < 1) {
                    requestAnimationFrame(tick);
                } else {
                    ctx.displayValue = target;
                }
            });

            requestAnimationFrame(tick);
        },
    },
    callbacks: {
        startObserving() {
            const context = getContext<StatItemContext>();
            const { ref } = getElement();

            if (!ref || context.hasAnimated) {
                return;
            }

            const prefersReducedMotion = window.matchMedia(
                '(prefers-reduced-motion: reduce)'
            ).matches;

            // Same principle cta's button hover animation already
            // established in this project: skip the animation entirely
            // for users who've asked the OS to minimize motion, rather
            // than playing a shortened/instant version of it.
            if (prefersReducedMotion) {
                context.hasAnimated = true;
                return;
            }

            const observer = new IntersectionObserver(
                withScope((entries: IntersectionObserverEntry[]) => {
                    const [entry] = entries;

                    if (entry?.isIntersecting && !context.hasAnimated) {
                        context.hasAnimated = true;
                        actions.animateCount();
                        observer.disconnect();
                    }
                }),
                { threshold: 0.3 }
            );

            observer.observe(ref);

            return () => observer.disconnect();
        },
    },
});

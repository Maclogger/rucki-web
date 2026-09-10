import { computed, shallowRef, watch } from "vue";
import type { ComputedRef, ShallowRef } from "vue";
import { useElementBounding, useWindowSize } from "@vueuse/core";
import type { MaybeComputedElementRef } from "@vueuse/core";

/**
 * Smallest progress change worth pushing into a template. Scrolling moves the
 * numbers by a fraction of a pixel per frame, which would otherwise re-render
 * every scroll-linked section on every frame for no visible difference.
 */
const COMMIT_THRESHOLD = 0.002;

const clamp = (value: number, min: number, max: number): number => Math.min(max, Math.max(min, value));

/**
 * Mirrors `source` into a ref, but only once it has moved far enough to be seen.
 * The two endpoints are exempt: without that, progress creeps to 0.998 and never
 * actually settles on 1, so anything keyed to "fully scrolled" would never fire.
 */
function useCommittedProgress(source: ComputedRef<number>): Readonly<ShallowRef<number>> {
    const progress = shallowRef(0);

    watch(source, (value) => {
        const isEndpoint = value === 0 || value === 1;
        if (isEndpoint || Math.abs(value - progress.value) > COMMIT_THRESHOLD) {
            progress.value = value;
        }
    }, { immediate: true });

    return progress;
}

/**
 * Progress through a tall wrapper holding a `position: sticky` child: 0 while the
 * wrapper's top is at the viewport top, 1 once its bottom has arrived. This is what
 * makes a section hold still on screen while the scroll drives its contents.
 *
 * The wrapper must be taller than the viewport; if it is not, progress stays 0.
 */
export function usePin(target: MaybeComputedElementRef): Readonly<ShallowRef<number>> {
    const { top, height } = useElementBounding(target);
    const { height: viewportHeight } = useWindowSize();

    return useCommittedProgress(computed(() => {
        const span = height.value - viewportHeight.value;
        return span <= 0 ? 0 : clamp(-top.value / span, 0, 1);
    }));
}

/**
 * Reveal progress as an element rises up the viewport: 0 while its top still sits
 * below `from`, ramping to 1 once it has passed `to`. Both are fractions of the
 * viewport height measured from the top, so `from` is the lower point on screen and
 * therefore the larger number.
 */
export function useEnter(
    target: MaybeComputedElementRef,
    from = 0.92,
    to = 0.4,
): Readonly<ShallowRef<number>> {
    const { top } = useElementBounding(target);
    const { height: viewportHeight } = useWindowSize();

    return useCommittedProgress(computed(() => {
        const span = (from - to) * viewportHeight.value;
        return span <= 0 ? 0 : clamp((from * viewportHeight.value - top.value) / span, 0, 1);
    }));
}

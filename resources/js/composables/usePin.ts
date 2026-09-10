import { computed } from "vue";
import { useElementBounding, useWindowSize } from "@vueuse/core";
import type { MaybeComputedElementRef } from "@vueuse/core";

/**
 * How far the page has scrolled through a tall section, from 0 to 1. A sticky child
 * inside that section then holds still on screen while this drives what it looks like.
 */
export function usePin(target: MaybeComputedElementRef) {
    const { top, height } = useElementBounding(target);
    const { height: viewportHeight } = useWindowSize();

    return computed(() => {
        const scrollableDistance = height.value - viewportHeight.value;
        const isTallerThanViewport = scrollableDistance > 0;
        if (!isTallerThanViewport) return 0;

        const scrolled = -top.value / scrollableDistance;
        return Math.min(1, Math.max(0, scrolled));
    });
}

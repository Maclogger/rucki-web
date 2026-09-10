import { watchEffect } from "vue";
import { unrefElement, useMouse, usePreferredReducedMotion, useWindowSize } from "@vueuse/core";
import type { MaybeComputedElementRef } from "@vueuse/core";

/**
 * Publishes the cursor on `target` as `--cx` / `--cy`, each -1…1 across the viewport,
 * so parallax transforms stay in CSS and mouse movement never re-renders a component.
 * Both properties are removed while no pointer is present, or reduced motion is asked for.
 */
export function useCursorParallax(target: MaybeComputedElementRef, strength = 1) {
    const { x, y, sourceType } = useMouse({ type: "client", touch: false });
    const { width, height } = useWindowSize();
    const reducedMotion = usePreferredReducedMotion();

    // Distance from the center of the viewport as a fraction: position / extent
    // gives 0…1, then * 2 - 1 recenters it to -1…1, and strength scales the range.
    // e.g. x = 960 in a 1280px window: 960 / 1280 = 0.75, 0.75 * 2 - 1 = "0.500".
    const toOffsetFromCenter = (position: number, extent: number) =>
        (((position / extent) * 2 - 1) * strength).toFixed(3);

    watchEffect(() => {
        const element = unrefElement(target);
        if (!(element instanceof HTMLElement)) return;

        const hasSeenPointer = sourceType.value !== null;
        const prefersReducedMotion = reducedMotion.value === "reduce";
        const shouldTrackCursor = hasSeenPointer && !prefersReducedMotion;

        if (shouldTrackCursor) {
            element.style.setProperty("--cx", toOffsetFromCenter(x.value, width.value));
            element.style.setProperty("--cy", toOffsetFromCenter(y.value, height.value));
        } else {
            element.style.removeProperty("--cx");
            element.style.removeProperty("--cy");
        }
    });
}

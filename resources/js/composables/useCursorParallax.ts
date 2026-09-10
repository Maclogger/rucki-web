import { watchEffect } from "vue";
import { unrefElement, useMouse, usePreferredReducedMotion, useWindowSize } from "@vueuse/core";
import type { MaybeComputedElementRef } from "@vueuse/core";

/**
 * Publishes the cursor on `target` as `--cx` / `--cy`, each -1…1 across the viewport,
 * so parallax transforms stay in CSS and mouse movement never re-renders a component.
 * Both properties are removed while no pointer is present, or reduced motion is asked for.
 */
export function useCursorParallax(target: MaybeComputedElementRef, strength = 1): void {
    const { x, y, sourceType } = useMouse({ type: "client", touch: false });
    const { width, height } = useWindowSize();
    const reducedMotion = usePreferredReducedMotion();

    const toOffsetFromCenter = (position: number, extent: number): string =>
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

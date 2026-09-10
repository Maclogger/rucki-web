import { watchEffect } from "vue";
import { unrefElement, useMouse, usePreferredReducedMotion, useWindowSize } from "@vueuse/core";
import type { MaybeComputedElementRef } from "@vueuse/core";

/**
 * Publishes the cursor position on `target` as the custom properties `--cx` and
 * `--cy`, each normalised to -1…1 across the viewport.
 *
 * Nothing lands in component state, so moving the mouse never triggers a re-render.
 * The parallax itself lives entirely in CSS, which means any descendant can opt in
 * by reading the two properties with its own depth multiplier, and the `transition`
 * on each element provides the smoothing.
 *
 * Both properties stay unset until a pointer is actually seen, and are removed again
 * under `prefers-reduced-motion: reduce`, so every transform falls back to the
 * `var(--cx, 0)` resting state instead of snapping to a corner.
 */
export function useCursorParallax(target: MaybeComputedElementRef, strength = 1): void {
    const { x, y, sourceType } = useMouse({ type: "client", touch: false });
    const { width, height } = useWindowSize();
    const reducedMotion = usePreferredReducedMotion();

    watchEffect(() => {
        const element = unrefElement(target);
        if (!(element instanceof HTMLElement)) return;

        if (reducedMotion.value === "reduce") {
            element.style.removeProperty("--cx");
            element.style.removeProperty("--cy");
            return;
        }

        if (sourceType.value === null) return;

        element.style.setProperty("--cx", (((x.value / width.value) * 2 - 1) * strength).toFixed(3));
        element.style.setProperty("--cy", (((y.value / height.value) * 2 - 1) * strength).toFixed(3));
    });
}

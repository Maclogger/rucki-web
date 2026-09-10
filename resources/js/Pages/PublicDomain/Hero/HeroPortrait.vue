<script setup lang="ts">
import { useTemplateRef } from "vue";
import { useCursorParallax } from "@/composables/useCursorParallax";

// The cursor is published here, so both layers below can read --cx / --cy.
const portrait = useTemplateRef("portrait");
useCursorParallax(portrait);
</script>

<template>
    <div
        ref="portrait"
        class="order-1 w-[min(17rem,60vw)] perspective-[1000px] min-[900px]:order-0 min-[900px]:w-[min(22rem,34vw)] min-[900px]:justify-self-start"
    >
        <!-- A rectangle, not a circle: the tilt is only legible if edges converge. -->
        <div
            class="relative aspect-4/5 transform-3d overflow-hidden rounded-[2rem] shadow-2xl ring-1 ring-white/10 transition-transform duration-300 ease-out rotate-x-[calc(var(--cy,0)*-9deg)] rotate-y-[calc(var(--cx,0)*9deg)]"
        >
            <!-- Blurred hard enough to stop reading as a second copy of me. -->
            <img
                src="/images/profile_2026.png"
                alt=""
                class="absolute inset-0 h-full w-full scale-125 object-cover blur-2xl brightness-75"
            />
            <!-- The sharp cut-out rides above and separates as the cursor moves. -->
            <img
                src="/images/hero3d/profile-cutout.webp"
                alt="Marek Rucki"
                class="absolute inset-0 h-full w-full translate-x-[calc(var(--cx,0)*14px)] translate-y-[calc(var(--cy,0)*14px)] scale-105 object-cover drop-shadow-2xl transition-[translate] duration-300 ease-out"
            />
        </div>
    </div>
</template>

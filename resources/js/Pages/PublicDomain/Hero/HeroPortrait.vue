<script setup lang="ts">
import { computed, useTemplateRef } from "vue";
import { usePublicStore } from "@/stores/publicStore";
import { useCursorParallax } from "@/composables/useCursorParallax";

const store = usePublicStore();

const fullName = computed(() => store.getFullName());

// The cursor is published here, so both layers below can read --cx / --cy.
const portrait = useTemplateRef("portrait");
useCursorParallax(portrait);
</script>

<template>
    <div
        ref="portrait"
        class="order-1 w-[min(16rem,54vw)] perspective-[1200px] min-[900px]:order-0 min-[900px]:w-[min(24rem,38vw)] min-[900px]:justify-self-start"
    >
        <div
            class="relative aspect-square transform-3d overflow-hidden rounded-full shadow-lg transition-transform duration-300 ease-out rotate-x-[calc(var(--cy,0)*-7deg)] rotate-y-[calc(var(--cx,0)*7deg)]"
        >
            <!-- The scene stays put and softened, so it reads as the background. -->
            <img
                src="/images/profile_2026.png"
                :alt="fullName"
                class="absolute inset-0 h-full w-full scale-105 object-cover blur-[3px]"
            />
            <!-- The cut-out lines up exactly at rest, and only lifts off as the cursor moves. -->
            <img
                src="/images/hero3d/profile-cutout.webp"
                alt=""
                class="absolute inset-0 h-full w-full translate-x-[calc(var(--cx,0)*12px)] translate-y-[calc(var(--cy,0)*12px)] object-cover transition-[translate] duration-300 ease-out"
            />
        </div>
    </div>
</template>

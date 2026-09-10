<script setup lang="ts">
import { computed, useTemplateRef } from "vue";
import { usePublicStore } from "@/stores/publicStore";
import { useCursorParallax } from "@/composables/useCursorParallax";

const props = defineProps<{
    progress: number;
    lift: string;
}>();

const store = usePublicStore();

const fullName = computed(() => store.getFullName());

// The parallax is published on this root, so the rings and the photo can each
// read --cx / --cy with their own depth.
const portrait = useTemplateRef("portrait");
useCursorParallax(portrait);

const scale = computed(() => 1 - 0.26 * props.progress);
</script>

<template>
    <div
        ref="portrait"
        class="relative order-1 aspect-square w-[min(16rem,54vw)] transition-[translate,scale] duration-200 ease-in-out min-[900px]:order-0 min-[900px]:w-[min(24rem,38vw)] min-[900px]:justify-self-start"
        :style="{ scale: scale, translate: props.lift }"
    >
        <!-- The rings drift against the cursor, and further than the photo, to read as depth. -->
        <span
            class="absolute inset-0 translate-x-[calc(var(--cx,0)*-16px)] translate-y-[calc(var(--cy,0)*-16px)] scale-[1.09] rounded-full border border-base-content/20 transition-[translate] duration-300 ease-in-out"
        ></span>
        <span
            class="absolute inset-0 translate-x-[calc(var(--cx,0)*-30px)] translate-y-[calc(var(--cy,0)*-30px)] scale-[1.22] rounded-full border border-base-content/20 transition-[translate] duration-400 ease-in-out"
        ></span>
        <img
            src="/images/profile_2026.png"
            :alt="fullName"
            class="relative h-full w-full translate-x-[calc(var(--cx,0)*10px)] translate-y-[calc(var(--cy,0)*10px)] rounded-full object-cover shadow-lg transition-[translate] duration-200 ease-in-out"
        />
    </div>
</template>

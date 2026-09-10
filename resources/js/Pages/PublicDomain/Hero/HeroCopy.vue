<script setup lang="ts">
import { computed } from "vue";
import { usePublicStore } from "@/stores/publicStore";

const props = defineProps<{
    progress: number;
    lift: string;
}>();

const store = usePublicStore();

const fullName = computed(() => store.getFullName());
const role = computed(() => store.getConstant("rola"));

// The name tightens up as the hero scrolls away, and the two tagline lines
// settle in one after the other rather than together.
const nameTracking = computed(() => `${-0.02 * props.progress}em`);
const isFirstTaglineShown = computed(() => props.progress > 0.1);
const isSecondTaglineShown = computed(() => props.progress > 0.22);
</script>

<template>
    <div
        class="order-2 max-w-lg transition-[translate] duration-120 ease-linear min-[900px]:order-0 min-[900px]:justify-self-end"
        :style="{ translate: props.lift }"
    >
        <p class="text-[clamp(2.25rem,5.4vw,4.25rem)] leading-[1.02] font-semibold" :style="{ letterSpacing: nameTracking }">
            {{ fullName }}
        </p>
        <p class="mt-1.5 text-[clamp(1rem,1.4vw,1.25rem)] text-primary-light-ultra">{{ role }}</p>

        <div class="mt-8 flex flex-col items-center gap-[0.15rem] min-[900px]:items-start">
            <span
                class="text-[clamp(1rem,1.5vw,1.375rem)] leading-normal transition-[translate,color] duration-400 ease-in-out"
                :class="isFirstTaglineShown ? 'translate-y-0 text-base-content' : 'translate-y-[0.5em] text-gray-400'"
            >
                Staviam webové aplikácie od schémy databázy
            </span>
            <span
                class="text-[clamp(1rem,1.5vw,1.375rem)] leading-normal transition-[translate,color] duration-400 ease-in-out"
                :class="isSecondTaglineShown ? 'translate-y-0 text-base-content' : 'translate-y-[0.5em] text-gray-400'"
            >
                po posledný pixel rozhrania.
            </span>
        </div>

        <p class="mt-10 font-mono text-[0.8125rem] text-gray-400 italic">Žilina, Slovensko</p>
    </div>
</template>

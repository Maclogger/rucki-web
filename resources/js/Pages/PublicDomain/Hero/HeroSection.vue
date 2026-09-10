<script setup lang="ts">
import { computed, useTemplateRef } from "vue";
import { useSectionScrollProgress } from "@/composables/useSectionScrollProgress";
import HeroCopy from "@/Pages/PublicDomain/Hero/HeroCopy.vue";
import HeroPortrait from "@/Pages/PublicDomain/Hero/HeroPortrait.vue";

// The section is twice the viewport height, so the pane inside it stays put for one
// full screen of scrolling while the progress below runs from 0 to 1.
const section = useTemplateRef("section");
const progress = useSectionScrollProgress(section);

// Copy and portrait rise together, so the amount lives here rather than in both.
const lift = computed(() => `0 ${-3 * progress.value}vh`);
const isScrollCueShown = computed(() => progress.value < 0.12);
</script>

<template>
    <section id="hero" ref="section" class="h-[200vh]">
        <div class="sticky top-0 flex h-screen flex-col justify-center">
            <div
                class="mx-auto grid w-full max-w-350 grid-cols-1 items-center justify-items-center gap-[clamp(2rem,6vw,6rem)] px-[clamp(1rem,5vw,4rem)] text-center min-[900px]:grid-cols-2 min-[900px]:text-left"
            >
                <HeroCopy :progress="progress" :lift="lift" />
                <HeroPortrait :progress="progress" :lift="lift" />
            </div>

            <div
                v-if="isScrollCueShown"
                class="absolute bottom-10 left-[clamp(1rem,5vw,4rem)] flex items-center gap-3 font-mono text-xs tracking-[0.14em] text-gray-400 uppercase"
            >
                <span>Skrolujte</span>
                <i class="h-px w-12 bg-linear-to-r from-primary to-transparent"></i>
            </div>
        </div>
    </section>
</template>

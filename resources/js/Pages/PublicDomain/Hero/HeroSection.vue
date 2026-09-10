<script setup lang="ts">
import { computed, useTemplateRef } from "vue";
import { useSectionScrollProgress } from "@/composables/useSectionScrollProgress";
import HeroCopy from "@/Pages/PublicDomain/Hero/HeroCopy.vue";
import HeroPortrait from "@/Pages/PublicDomain/Hero/HeroPortrait.vue";

// The section is taller than the pane inside it, so that pane stays put while the
// page scrolls past it and the progress below runs from 0 to 1. The pane stops short
// of the fold, so the slanted section underneath shows that there is more to come.
const section = useTemplateRef("section");
const progress = useSectionScrollProgress(section);

// Copy and portrait rise together, so the amount lives here rather than in both.
const lift = computed(() => `0 ${-3 * progress.value}vh`);
</script>

<template>
    <section id="hero" ref="section" class="h-[140vh]">
        <div class="sticky top-0 flex h-[85vh] flex-col justify-center">
            <div
                class="mx-auto grid w-full max-w-350 grid-cols-1 items-center justify-items-center gap-[clamp(2rem,6vw,6rem)] px-[clamp(1rem,5vw,4rem)] text-center min-[900px]:grid-cols-2 min-[900px]:text-left"
            >
                <HeroCopy :progress="progress" :lift="lift" />
                <HeroPortrait :progress="progress" :lift="lift" />
            </div>
        </div>
    </section>
</template>

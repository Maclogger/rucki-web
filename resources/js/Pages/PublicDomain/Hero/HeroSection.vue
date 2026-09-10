<script setup lang="ts">
import { computed, useTemplateRef } from "vue";
import { usePublicStore } from "@/stores/publicStore";
import { usePin } from "@/composables/usePin";
import { useCursorParallax } from "@/composables/useCursorParallax";

const store = usePublicStore();

const fullName = computed(() => store.getFullName());
const role = computed(() => store.getConstant("rola"));

// The section is twice the viewport height, so the sticky pane inside it stays put
// for one full screen of scrolling while `progress` runs from 0 to 1.
const section = useTemplateRef("section");
const portrait = useTemplateRef("portrait");

const progress = usePin(section);
useCursorParallax(portrait);

// The portrait shrinks and lifts as you scroll away from it; the copy lifts with it.
const portraitScale = computed(() => 1 - 0.26 * progress.value);
const lift = computed(() => `0 ${-3 * progress.value}vh`);
const nameTracking = computed(() => `${-0.02 * progress.value}em`);

const isFirstTaglineShown = computed(() => progress.value > 0.1);
const isSecondTaglineShown = computed(() => progress.value > 0.22);
const isScrollCueShown = computed(() => progress.value < 0.12);
</script>

<template>
    <section id="hero" ref="section" class="h-[200vh]">
        <div class="sticky top-0 flex h-screen flex-col justify-center">
            <div
                class="mx-auto grid w-full max-w-[1400px] grid-cols-1 items-center justify-items-center gap-[clamp(2rem,6vw,6rem)] px-[clamp(1rem,5vw,4rem)] text-center min-[900px]:grid-cols-2 min-[900px]:text-left"
            >
                <div
                    class="order-2 max-w-[32rem] transition-[translate] duration-[120ms] ease-linear min-[900px]:order-none min-[900px]:justify-self-end"
                    :style="{ translate: lift }"
                >
                    <p
                        class="text-[clamp(2.25rem,5.4vw,4.25rem)] leading-[1.02] font-semibold"
                        :style="{ letterSpacing: nameTracking }"
                    >
                        {{ fullName }}
                    </p>
                    <p class="mt-1.5 text-[clamp(1rem,1.4vw,1.25rem)] text-primary-light-ultra">{{ role }}</p>

                    <div class="mt-8 flex flex-col items-center gap-[0.15rem] min-[900px]:items-start">
                        <span
                            class="text-[clamp(1rem,1.5vw,1.375rem)] leading-normal transition-[translate,color] duration-[400ms] ease-in-out"
                            :class="isFirstTaglineShown ? 'translate-y-0 text-base-content' : 'translate-y-[0.5em] text-gray-400'"
                        >
                            Staviam webové aplikácie od schémy databázy
                        </span>
                        <span
                            class="text-[clamp(1rem,1.5vw,1.375rem)] leading-normal transition-[translate,color] duration-[400ms] ease-in-out"
                            :class="isSecondTaglineShown ? 'translate-y-0 text-base-content' : 'translate-y-[0.5em] text-gray-400'"
                        >
                            po posledný pixel rozhrania.
                        </span>
                    </div>

                    <p class="mt-10 font-mono text-[0.8125rem] text-gray-400 italic">Žilina, Slovensko</p>
                </div>

                <div
                    ref="portrait"
                    class="relative order-1 aspect-square w-[min(16rem,54vw)] transition-[translate,scale] duration-200 ease-in-out min-[900px]:order-none min-[900px]:w-[min(24rem,38vw)] min-[900px]:justify-self-start"
                    :style="{ scale: portraitScale, translate: lift }"
                >
                    <!-- The rings drift against the cursor, and further than the portrait, to read as depth. -->
                    <span
                        class="absolute inset-0 translate-x-[calc(var(--cx,0)*-16px)] translate-y-[calc(var(--cy,0)*-16px)] scale-[1.09] rounded-full border border-base-content/20 transition-[translate] duration-300 ease-in-out"
                    ></span>
                    <span
                        class="absolute inset-0 translate-x-[calc(var(--cx,0)*-30px)] translate-y-[calc(var(--cy,0)*-30px)] scale-[1.22] rounded-full border border-base-content/20 transition-[translate] duration-[400ms] ease-in-out"
                    ></span>
                    <img
                        src="/images/profile_2026.png"
                        :alt="fullName"
                        class="relative h-full w-full translate-x-[calc(var(--cx,0)*10px)] translate-y-[calc(var(--cy,0)*10px)] rounded-full object-cover shadow-lg transition-[translate] duration-200 ease-in-out"
                    />
                </div>
            </div>

            <div
                v-if="isScrollCueShown"
                class="absolute bottom-10 left-[clamp(1rem,5vw,4rem)] flex items-center gap-3 font-mono text-xs tracking-[0.14em] text-gray-400 uppercase"
            >
                <span>Skrolujte</span>
                <i class="h-px w-12 bg-gradient-to-r from-primary to-transparent"></i>
            </div>
        </div>
    </section>
</template>

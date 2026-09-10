<script setup lang="ts">
import HeroTerminal from "@/Pages/PublicDomain/Hero/HeroTerminal.vue";
</script>

<template>
    <div class="w-full max-w-[64rem] perspective-[1800px]">
        <div class="transform-3d rotate-x-[4deg]">
            <!-- The lid stands up from its bottom edge, which is also the hinge. -->
            <div
                class="origin-bottom transform-3d rotate-x-[-9deg] rounded-t-xl border border-white/10 bg-[#16181d] p-[clamp(0.4rem,1vw,0.75rem)] shadow-2xl"
            >
                <div class="aspect-16/9 overflow-hidden rounded-md bg-[#0b0d11] ring-1 ring-white/5">
                    <HeroTerminal />
                </div>
            </div>

            <!-- The base lies back into the scene, so it reads as the keyboard deck. -->
            <div
                class="relative h-[clamp(4rem,9vw,7.5rem)] origin-top rotate-x-[70deg] rounded-b-md border border-white/10 bg-linear-to-b from-[#2a2e36] via-[#1c1f25] to-[#101216]"
            >
                <!-- A trackpad is the one detail that stops the deck reading as a plinth. -->
                <div class="absolute bottom-[18%] left-1/2 h-[38%] w-[22%] -translate-x-1/2 rounded-sm border border-white/10 bg-white/3"></div>
            </div>
        </div>
    </div>
</template>

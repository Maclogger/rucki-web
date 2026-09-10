<script setup lang="ts">
import HeroCopy from "@/Pages/PublicDomain/Hero/HeroCopy.vue";
import HeroPortrait from "@/Pages/PublicDomain/Hero/HeroPortrait.vue";
</script>

<template>
    <!-- Short of a full screen on purpose, so the slanted section below peeks in. -->
    <section id="hero" class="flex min-h-[85vh] flex-col justify-center">
        <div
            class="mx-auto grid w-full max-w-350 grid-cols-1 items-center justify-items-center gap-[clamp(2rem,6vw,6rem)] px-[clamp(1rem,5vw,4rem)] text-center min-[900px]:grid-cols-2 min-[900px]:text-left"
        >
            <HeroCopy />
            <HeroPortrait />
        </div>
    </section>
</template>

<script setup lang="ts">
import HeroCopy from "@/Pages/PublicDomain/Hero/HeroCopy.vue";
</script>

<template>
    <!-- Short of a full screen on purpose, so the slanted section below peeks in. -->
    <section id="hero" class="flex min-h-[85vh] flex-col justify-center">
        <div
            class="mx-auto grid w-full max-w-350 grid-cols-1 items-center justify-items-center gap-[clamp(2rem,6vw,6rem)] px-[clamp(1rem,5vw,4rem)] text-center min-[900px]:grid-cols-2 min-[900px]:text-left"
        >
            <HeroCopy />
            <img
                src="/images/profile_2026.png"
                alt="Marek Rucki"
                class="order-1 aspect-square w-[min(16rem,54vw)] rounded-full object-cover shadow-lg min-[900px]:order-0 min-[900px]:w-[min(24rem,38vw)] min-[900px]:justify-self-start"
            />
        </div>
    </section>
</template>

<script setup lang="ts">

const props = defineProps<{
    repositoryName: string
    repositoryLink?: string
    demoLink?: string
    icons: Array<{
        src: string
        alt: string
        top: string
        left: string
        size?: string
        z?: number
    }>
}>();
</script>

<template>
    <div class="border rounded-2xl p-6 flex flex-col justify-between">
        <div class="flex flex-col gap-6">
            <p class="text-2xl">{{ props.repositoryName }}</p>
            <div class="relative w-[200px] h-[180px] mx-auto">
                <img
                    v-for="(icon, i) in icons"
                    :key="i"
                    :src="icon.src"
                    :alt="icon.alt"
                    class="absolute"
                    :style="{ top: icon.top, left: icon.left, width: icon.size, height: icon.size, zIndex: icon.z || 1 }"
                />
            </div>
        </div>
        <div class="flex flex-col gap-2 min-w-[14.3rem]">
            <a
                v-if="props.demoLink"
                :href="props.demoLink"
                class="btn btn-primary w-full text-center"
                target="_blank"
                rel="noopener noreferrer"
            >
                Výsledný produkt
            </a>
            <a
                v-if="props.repositoryLink"
                :href="props.repositoryLink"
                class="btn btn-neutral w-full text-center"
                target="_blank"
                rel="noopener noreferrer"
            >
                Dokumentácia
            </a>
            <button v-else class="btn btn-neutral w-full" disabled>GitHub</button>
        </div>
    </div>
</template>

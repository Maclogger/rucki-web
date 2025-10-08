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
    <div class="border rounded-2xl p-6 flex flex-col gap-6">
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

        <div class="flex flex-col gap-2 min-w-[14.3rem]">
            <button class="btn btn-primary w-full">Demo</button>
            <button class="btn btn-neutral w-full">GitHub</button>
        </div>


        <!--        <div class="mx-auto w-56 flex">-->
        <!--            <div class="w-1/2 pr-2">-->
        <!--                <button class="btn btn-neutral w-full">Demo</button>-->
        <!--            </div>-->
        <!--            <div class="w-1/2 pl-2">-->
        <!--                <button class="btn btn-primary w-full">GitHub</button>-->
        <!--            </div>-->
        <!--        </div>-->
    </div>
</template>

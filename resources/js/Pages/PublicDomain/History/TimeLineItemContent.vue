<script setup lang="ts">

const props = defineProps<{
    // No props for now
    time: string
    headline: string
    url: string
    imgSrc?: string
    position: 'left' | 'right'
}>();



</script>

<template>
    <time v-if="props.time" class="font-mono italic">{{props.time}}</time>
    <div class="mt-4 flex gap-4 md:flex-nowrap flex-wrap" :class="props.position === 'left' ? 'md:flex-row-reverse' : ''">
        <img v-if="props.imgSrc" :src="props.imgSrc" class="rounded-2xl  h-[6rem]" :alt="props.headline">
        <div>
            <a :href="props.url" target="_blank" class="text-xl font-bold link">
                {{props.headline}}
            </a>
            <slot/>
        </div>
    </div>
</template>



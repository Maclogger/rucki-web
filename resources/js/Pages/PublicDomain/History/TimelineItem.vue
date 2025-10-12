<script setup lang="ts">
import {ref, onMounted, onUnmounted} from 'vue';


const threshold = 50;
const hrRef = ref<HTMLElement | null>(null);
const isInTopHalf = ref(false);

const checkPosition = () => {
    if (!hrRef.value) return;

    const rect = hrRef.value.getBoundingClientRect();
    const viewportHeight = window.innerHeight;
    const elementCenter = rect.top + (rect.height / 2);
    const thresholdPosition = viewportHeight * (threshold / 100);

    isInTopHalf.value = elementCenter < thresholdPosition;
};

onMounted(() => {
    checkPosition();
    window.addEventListener('scroll', checkPosition, {passive: true});
    window.addEventListener('resize', checkPosition, {passive: true});
});

onUnmounted(() => {
    window.removeEventListener('scroll', checkPosition);
    window.removeEventListener('resize', checkPosition);
});
</script>

<template>
    <li>
        <div v-if="$slots.middle" class="timeline-middle mx-4">
            <slot name="middle"/>
        </div>
        <div v-if="$slots.left" class="timeline-start mb-10 md:text-end">
            <slot name="left"/>
        </div>
        <div v-if="$slots.right" class="timeline-end mb-10 md:text-start">
            <slot name="right"/>
        </div>
        <hr ref="hrRef" :class="{ 'bg-white': isInTopHalf }" class="transition-colors duration-400"/>
    </li>
</template>

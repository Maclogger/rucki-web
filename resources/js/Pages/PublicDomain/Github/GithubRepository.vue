<script setup lang="ts">
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

defineProps<{
    repositoryName: string;
    repositoryLink: string;
}>();
</script>

<template>
    <a
        :href="repositoryLink"
        target="_blank"
        rel="noopener noreferrer"
        class="repository w-full h-full rounded-2xl border-my-white border-2 p-4 flex flex-col justify-between"
        :aria-label="`Prejsť na repozitár ${repositoryName}`"
    >
        <div class="flex flex-row items-center">
            <font-awesome-icon class="text-3xl repository-icon" :icon="['fas', 'book-bookmark']" />
            <p class="text-lg font-bold ms-3">{{ repositoryName }}</p>
        </div>
        <div class="flex flex-row gap-3 items-center overflow-y-auto py-2">
            <p class="text-lg">Stack:</p>
            <slot name="stack-icons">
                <p class="text-sm text-gray-500">N/A</p>
            </slot>
        </div>
    </a>
</template>

<style scoped>
/* Väčšina štýlov ostáva rovnaká, len sa aplikujú na <a> značku */
.repository {
    background-color: transparent;
    transition-duration: var(--default-transition-duration);
    /* Dôležité: odstráni podčiarknutie a nastaví farbu textu */
    text-decoration: none;
    color: inherit; /* Zdedí farbu textu z rodiča, aby nebola modrá */
    display: flex; /* Dôležité: Aby sa flex správanie prenieslo na <a> */
    flex-direction: column;
    justify-content: space-between;
}

.repository-icon {
    color: var(--color-primary);
    transition-duration: var(--default-transition-duration);
}

.repository:hover {
    background-color: var(--color-primary);
    cursor: pointer;
    transition-duration: var(--default-transition-duration);
}

.repository:hover .repository-icon {
    color: var(--color-my-white);
    transition-duration: var(--default-transition-duration);
}
</style>

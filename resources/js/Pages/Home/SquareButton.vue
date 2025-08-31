<script setup lang="ts">

import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const props = defineProps<{
    color?: string | null,
    icon?: string | null,
    wip: boolean,
}>();


const getIconStyle = (): string => {
    if (props.color) {
        return `color: ${props.color}`;
    }
    return "";
}

const getIcon = () => {
    return props.icon ?? "fa-solid fa-road-barrier";
}

const isDisabled = (): boolean => {
    return props.wip === true;
}

</script>

<template>
    <button class="btn btn-primary btn-square flex flex-col w-full h-full aspect-square" :disabled="isDisabled()">
        <font-awesome-icon :icon="getIcon()" class="text-6xl" :style="getIconStyle()" />
        <slot />
    </button>
</template>

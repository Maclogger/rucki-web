<script setup lang="ts">

import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const props = defineProps<{
    color?: string | null,
    icon?: string | null,
    wip: boolean,
    onClick?: () => void,
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

const handleClick = (): void => {
    if (!props.onClick) {
        return;
    }
    props.onClick();
}

</script>

<template>
    <button
        class="btn bg-primary-dark-transparent hover:bg-primary btn-square flex flex-col w-full h-full aspect-square"
        :disabled="isDisabled()" @click="handleClick">
        <font-awesome-icon :icon="getIcon()" class="text-6xl" :style="getIconStyle()" />
        <div class="badge badge-primary">
            <slot />
        </div>
    </button>
</template>

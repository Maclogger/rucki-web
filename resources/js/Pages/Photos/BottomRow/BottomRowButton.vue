<script setup lang="ts">
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const props = defineProps<{
    icon: string,
    onClick?: () => void,
    disabled?: boolean,
    class?: string,
}>();

const handleClick = (): void => {
    if (!props.onClick || props.disabled) {
        return;
    }
    props.onClick();
}
</script>

<template>
    <button class="btn w-1/3 rounded-none border-none" :class="props.class ?? ''" @click="handleClick"
        :disabled="props.disabled">
        <font-awesome-icon :icon="props.icon" />
    </button>
</template>

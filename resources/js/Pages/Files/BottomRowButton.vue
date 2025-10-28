<script setup lang="ts">
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const props = defineProps<{
    icon?: string,
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
    <button class="btn border-none" :class="props.class ?? ''" @click="handleClick"
            :disabled="props.disabled">
        <font-awesome-icon v-if="props.icon" :icon="props.icon" />
        <slot />
    </button>
</template>

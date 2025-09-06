<script setup lang="ts">
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { computed } from "vue";

export enum BottomRowColor {
    PRIMARY = "primary",
    PRIMARY_DARK = "primary-dark",
    RED = "red",
}

const props = defineProps<{
    icon: string,
    onClick?: () => void,
    disabled?: boolean,
    color?: BottomRowColor,
}>();

const buttonClasses = computed(() => {
    let classes = [
        "btn",
        "w-1/3",
        "flex",
        "items-center",
        "justify-center",
        "py-2",
        "px-4",
    ];

    if (props.color) {
        switch (props.color) {
            case BottomRowColor.PRIMARY:
                classes.push("btn-primary");
                break;
            case BottomRowColor.PRIMARY_DARK:
                classes.push("bg-primary-dark-transparent");
                break;
            case BottomRowColor.RED:
                classes.push("bg-red-400", "hover:bg-my-white", "hover:text-red-400", "rounded-none", "border-none"); // Triedy pre RED, vrátane špecifických ako rounded-none
                break;
            default:
                classes.push("bg-gray-500", "text-white");
                break;
        }
    }

    return classes;
});

const handleClick = (): void => {
    if (!props.onClick || props.disabled) { // Zablokujeme kliknutie ak je disabled
        return;
    }
    props.onClick();
}
</script>

<template>
    <button :class="buttonClasses" @click="handleClick" :disabled="props.disabled">
        <font-awesome-icon :icon="props.icon" />
    </button>
</template>

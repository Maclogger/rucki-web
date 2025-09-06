<script setup lang="ts">


import ToastItem from "@/Components/ToastItem.vue";
import { useToastsStore } from "@/stores/toastsStore";
import { computed } from "vue";

const toastsStore = useToastsStore();

const toasts = computed(() => {
    return toastsStore.toasts;
});

</script>

<template>
    <div v-if="toasts.size > 0" class="toast toast-top toast-end">
        <div v-for="(toastKey) in toasts.keys()">
            <ToastItem :key="toastKey" :id-of-toast-in-store="toastKey" />
        </div>
    </div>
</template>

<style scoped></style>

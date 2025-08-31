<script setup lang="ts">
import { ToastProps, ToastSeverity, useToastsStore } from '@/stores/toastsStore';
import { router } from '@inertiajs/vue3';

defineProps<{}>()

const toastStore = useToastsStore();

const onClicked = () => {
    console.log("JUPI");
    router.post("/fetch-github-contributions", {}, {
        onFinish: () => {
            const toast: ToastProps = {
                message: "GitHub contributions were fetched successfully.",
                severity: ToastSeverity.SUCCESS,
            }
            toastStore.displayToast(toast);
        },
    });
}
</script>


<template>
    <slot :onClicked="onClicked" />
</template>

<script setup lang="ts">
import { ToastProps, ToastSeverity, useToastsStore } from '@/stores/toastsStore';
import { router } from '@inertiajs/vue3';
import SquareButton from "@/Pages/Home/SquareButton.vue";

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
    <SquareButton @click="onClicked" icon="fa-brands fa-github" :wip="false" label="Fetch Data" />
</template>

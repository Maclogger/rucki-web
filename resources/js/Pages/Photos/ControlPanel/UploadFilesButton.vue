<script setup lang="ts">
import { ToastProps, ToastSeverity, useToastsStore } from '@/stores/toastsStore';
import { useForm } from '@inertiajs/vue3';
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const form = useForm({
    files: [] as File[],
});

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        form.files = Array.from(target.files);
        submit();
    }
}

const submit = () => {
    form.post("/files-upload", {
        onSuccess: () => {
            const toast: ToastProps = {
                message: "Fotky boli úspešne nahraté",
                severity: ToastSeverity.SUCCESS,
            };
            useToastsStore().displayToast(toast);

            form.reset();
        },
        onError: (errors) => {
            console.error("Errors during photo upload:", errors);
            const toast: ToastProps = {
                message: "Chyba pri nahrávaní fotiek",
                severity: ToastSeverity.ERROR,
            };
            useToastsStore().displayToast(toast);
        },
        onFinish: () => {
            form.files = [];
        }
    });
}
</script>

<template>
    <div class="btn btn-primary rounded-xl flex flex-row gap-6 h-24 p-4 relative overflow-hidden cursor-pointer">
        <div class="text-md p-0 m-0 flex flex-row gap-6">
            <font-awesome-icon icon="fa-solid fa-upload" class="m-0 p-0 text-2xl" />
            <p class="m-0 p-0 text-lg">Nahrať súbory</p>
        </div>

        <input type="file" @change="handleFileChange" multiple :disabled="form.processing"
            class="absolute inset-0 opacity-0 cursor-pointer w-full h-full" aria-label="Vybrať súbory na nahratie" />

        <progress v-if="form.progress" class="progress progress-secondary absolute bottom-0 left-0 w-full"
            :value="form.progress.percentage" max="100" />
    </div>
</template>

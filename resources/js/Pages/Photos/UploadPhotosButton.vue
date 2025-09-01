<script setup lang="ts">
import { ToastProps, ToastSeverity, useToastsStore } from '@/stores/toastsStore';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    photos: [] as File[],
});

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files) {
        form.photos = Array.from(target.files);
    }
}

const submit = () => {
    form.post("photos-upload", {
        onSuccess: () => {
            const toast: ToastProps = {
                message: "Fotky boli úspešne nahraté",
                severity: ToastSeverity.SUCCESS,
            };
            useToastsStore().displayToast(toast);
        },
        onError: (errors) => {
            console.log(errors);
            console.log("Errors:");
        }
    });
}
</script>

<template>
    <form @submit.prevent="submit">
        <input type="file" @input="handleFileChange" multiple />
        <progress v-if="form.progress" :value="form.progress.percentage" max="100">
            {{ form.progress.percentage }}%
        </progress>
        <button :disabled="form.processing">Submit</button>
    </form>
</template>

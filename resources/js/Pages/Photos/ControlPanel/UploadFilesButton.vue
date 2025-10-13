<script setup lang="ts">
import {ToastProps, ToastSeverity, useToastsStore} from '@/stores/toastsStore';
import {useForm} from '@inertiajs/vue3';
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {computed, ref} from "vue";

const props = defineProps<{
    bufferCode?: string;
}>();

const emit = defineEmits(['uploadFinished']);


const form = useForm({
    files: [] as File[],
    bufferCode: props.bufferCode,
});

const inputRef = ref<HTMLInputElement | null>(null);

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        form.files = Array.from(target.files);
        submit();
    }
}

const submit = () => {
    const url = props.bufferCode ? '/buffer/files-upload' : "/files-upload";
    form.post(url, {
        onSuccess: () => {
            useToastsStore().displayToast({
                message: "Fotky boli úspešne nahraté",
                severity: ToastSeverity.SUCCESS,
            });
        },
        onError: (errors) => {
            form.reset();
            console.error("Errors during photo upload:", errors);
            const toast: ToastProps = {
                message: "Chyba pri nahrávaní súborov",
                severity: ToastSeverity.ERROR,
            };
            useToastsStore().displayToast(toast);
        },
        onFinish: () => {
            form.files = [];
            form.reset();
            emit('uploadFinished');
        }
    });
}

const openFileDialog = () => {
    inputRef.value?.click();
}

defineExpose({
    openFileDialog
})

const active = computed(() => {
    return props.bufferCode != undefined && props.bufferCode.length >= 4;
});


// Dynamické len to, čo sa mení podľa props
const borderClass = computed(() =>
    active.value
        ? "border-my-white ring-2 ring-my-white scale-102 animate-pulse"
        : "border-gray-300 dark:border-gray-700"
);

const buttonColor = computed(() =>
    form.processing || (props.bufferCode != undefined && !active.value)
        ? "btn-disabled"
        : "btn-primary cursor-pointer"
);

</script>

<template>
    <div class="btn rounded-xl flex flex-row gap-6 h-24 p-4 relative transition-all duration-200
                 overflow-hidden" :class="borderClass + ' ' + buttonColor">
        <div class="text-md p-0 m-0 flex flex-row gap-6">
            <font-awesome-icon icon="fa-solid fa-upload" class="m-0 p-0 text-2xl"/>
            <p class="m-0 p-0 text-lg">Nahrať súbory</p>
        </div>

        <input ref="inputRef" type="file" @change="handleFileChange" multiple
               :disabled="form.processing || (props.bufferCode != undefined && !active)"
               class="absolute inset-0 opacity-0 cursor-pointer w-full h-full" aria-label="Vybrať súbory na nahratie"/>

        <progress v-if="form.progress" class="progress progress-secondary absolute bottom-0 left-0 w-full"
                  :value="form.progress.percentage" max="100"/>
    </div>
</template>

<script setup lang="ts">
import { inject, onMounted, Ref, ref } from 'vue';
import { Photo, PhotoStatus } from '@/Classes/Photo';
import { ToastSeverity, useToastsStore } from '@/stores/toastsStore';

const photo: Photo = inject("photo")!;

const imageRef: Ref<HTMLImageElement | null> = ref<HTMLImageElement | null>(null);

onMounted(() => {
    if (!imageRef.value) {
        useToastsStore().displayToast({
            message: "ImageRef was not initialized in onMounted function. PhotoImage.vue",
            severity: ToastSeverity.ERROR,
        });
        return;
    }
    photo.status = PhotoStatus.LOADING;
    imageRef.value.onload = () => {
        photo.status = PhotoStatus.LOADED;
        photo.imgElement = imageRef.value;
    };
});

</script>


<template>
    <div class="bg-orange-400 w-full h-full">
        <img ref="imageRef" :src="photo.getFilePath()" class="w-full h-full object-center object-cover" />
    </div>
</template>

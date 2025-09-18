<script setup lang="ts">
import { inject, onMounted, Ref, ref } from 'vue';
import { Photo, PhotoStatus } from '@/Classes/Photo';
import { ToastSeverity, useToastsStore } from '@/stores/toastsStore';

const photo = inject<Ref<Photo>>("photo")!;

const imageRef: Ref<HTMLImageElement | null> = ref<HTMLImageElement | null>(null);

onMounted(() => {
    if (!imageRef.value) {
        useToastsStore().displayToast({
            message: "ImageRef nebol inicializovaný vo funkcii onMounted. PhotoImage.vue",
            severity: ToastSeverity.ERROR,
        });
        return;
    }
    photo.value.status = PhotoStatus.LOADING;
    photo.value.imgElement = imageRef.value;
    imageRef.value.onload = () => {
        photo.value.status = PhotoStatus.LOADED;
    };
});

</script>


<template>
    <div class="bg-orange-400 w-full h-full">
        <img ref="imageRef" :src="photo.getFilePath()" class="w-full h-full object-center object-cover" />
    </div>
</template>

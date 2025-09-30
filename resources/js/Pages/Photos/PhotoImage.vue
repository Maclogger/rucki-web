<script setup lang="ts">
import { inject, onMounted, Ref, ref } from 'vue';
import { File } from '@/Classes/File';
import { ToastSeverity, useToastsStore } from '@/stores/toastsStore';
import { Photo, PhotoStatus } from '@/Classes/Photo';

const file = inject<Ref<File>>("file")!;

const imageRef: Ref<HTMLImageElement | null> = ref<HTMLImageElement | null>(null);

const setImageRef = (photo: Photo) => {
    if (!imageRef.value) {
        useToastsStore().displayToast({
            message: "ImageRef nebol inicializovaný vo funkcii onMounted. PhotoImage.vue",
            severity: ToastSeverity.ERROR,
        });
        return;
    }

    photo.status = PhotoStatus.LOADING;
    photo.imgElement = imageRef.value;
    photo.imgElement.onload = () => {
        photo.status = PhotoStatus.LOADED;
    };
}


onMounted(() => {
    if (!file.value) {
        useToastsStore().displayToast({
            message: "Nebol načítaný file vo PhotoImage.vue",
            severity: ToastSeverity.ERROR,
        });
        return;
    }

    if (file.value instanceof Photo) {
        setImageRef(file.value);
    } else {
        useToastsStore().displayToast({
            message: "This is a file.",
        });
    }
});

</script>


<template>
    <div v-if="file instanceof Photo">
        <div class="bg-orange-400 w-full h-full">
            <img ref="imageRef" :src="file.getFilePath()" class="w-full h-full object-center object-cover"  alt="imageRef"/>
        </div>
    </div>
</template>

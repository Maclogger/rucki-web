<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import PhotoGallery from '@/Pages/Photos/PhotoGallery.vue';
import PhotoControlPanel from './ControlPanel/PhotoControlPanel.vue';
import { onMounted } from 'vue';
import { usePhotosStore } from '@/stores/photosStore';

const photoStore = usePhotosStore();

onMounted(() => {
    photoStore.refresh();
});


</script>


<template>
    <AuthLayout>
        <template #headline>
            <p class="text-2xl">Fotky</p>
        </template>
        <template #default>
            <div class="flex flex-row">
                <PhotoGallery class="w-2/3" />
                <PhotoControlPanel />
            </div>
        </template>
    </AuthLayout>
</template>

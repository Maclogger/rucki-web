<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import UploadPhotosButton from '@/Pages/Photos/UploadPhotosButton.vue';
import PhotoGallery from '@/Pages/Photos/PhotoGallery.vue';



</script>


<template>
    <AuthLayout>
        <template #headline>
            <p class="text-2xl">Fotky</p>
        </template>
        <template #default>
            <div class="flex flex-row">
                <PhotoGallery class="w-2/3" />
                <UploadPhotosButton class="bg-red-300 flex-1" />
            </div>
        </template>
    </AuthLayout>
</template>

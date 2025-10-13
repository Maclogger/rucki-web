<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { onMounted } from 'vue';
import { useFilesStore } from '@/stores/filesStore';
import FilesControlPanel from './ControlPanel/FilesControlPanel.vue';
import FileGalleryComp from './FileGalleryComp.vue';

const filesStore = useFilesStore();

onMounted(() => {
    if (filesStore.files.length <= 0) {
        filesStore.refresh();
    }
});

</script>

<template>
    <AuthLayout>
        <template #headline>
            <p class="text-2xl">Súbory</p>
        </template>
        <template #default>
            <div class="flex flex-row">
                <FileGalleryComp class="w-2/3" />
                <FilesControlPanel />
            </div>
        </template>
    </AuthLayout>
</template>

<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { computed, onMounted } from 'vue';
import { useEcho, } from '@laravel/echo-vue';
import { useUserStore } from '@/stores/userStore';
import { File, FilesResponse } from '@/Classes/File';
import { useFilesStore } from '@/stores/filesStore';

const filesStore = useFilesStore();
const userStore = useUserStore();

const user = computed(() => {
    return userStore.getUser!;
});

const userChannel = computed(() => {
    return `users.${user.value.id}.files`;
});


interface FileUploadedEvent {
    file: FilesResponse,
}

interface FileDeletedEvent {
    fileId: number,
    idUser: number,
}

const { channel } = useEcho<FileUploadedEvent>(
    userChannel.value, "FileUploaded", (e) => {
        const file = new File(e.file);
        filesStore.newFileAdded(file);
    }
);

useEcho<FileDeletedEvent>(
    userChannel.value, "FileDeleted", (e) => {
        filesStore.fileDeleted(e.fileId);
    }
);

const registerStateHooks = () => {
    const connection = (channel() as any).pusher.connection;
    connection.bind('connected', () => {
        console.log("Websocket connected.");
        filesStore.websocketConnection = true;
    });
    connection.bind('connecting', () => {
        console.log("Websocket disconnected. :(");
        filesStore.websocketConnection = false;
    });
}


onMounted(() => {
    if (filesStore.files.length <= 0) {
        filesStore.refresh();
    }
    registerStateHooks();
});

</script>


<template>
    <AuthLayout>
        <template #headline>
            <p class="text-2xl">Súbory</p>
        </template>
        <template #default>
            <div class="flex flex-row">
                <!-- <PhotoGalleryComp class="w-2/3" /> -->
                <!-- <PhotoControlPanel /> -->
                <!-- <DebugButton /> -->
            </div>
        </template>
    </AuthLayout>
</template>

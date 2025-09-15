<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import PhotoGallery from '@/Pages/Photos/PhotoGallery.vue';
import PhotoControlPanel from './ControlPanel/PhotoControlPanel.vue';
import DebugButton from '@/Components/DebugButton.vue';
import { computed, onMounted } from 'vue';
import { usePhotosStore } from '@/stores/photosStore';
import { useEcho, } from '@laravel/echo-vue';
import { useUserStore } from '@/stores/userStore';
import { Photo, PhotoResponse } from '@/Classes/Photo';

const photoStore = usePhotosStore();
const userStore = useUserStore();

const user = computed(() => {
    return userStore.getUser!;
});

const userChannel = computed(() => {
    return `users.${user.value.id}.photos`;
});


interface PhotoUploadedEvent {
    photo: PhotoResponse,
}

interface PhotoDeletedEvent {
    photoId: number,
    idUser: number,
}

const { channel } = useEcho<PhotoUploadedEvent>(
    userChannel.value, "PhotoUploaded", (e) => {
        console.log("Nová fotka nahraná.");

        const photo = new Photo(e.photo);
        photoStore.newPhotoAdded(photo);
    }
);

useEcho<PhotoDeletedEvent>(
    userChannel.value, "PhotoDeleted", (e) => {
        console.log("Fotka zmazaná.");
        photoStore.photoDeleted(e.photoId);
    }
);

const registerStateHooks = () => {
    const connection = (channel() as any).pusher.connection;
    connection.bind('connected', () => {
        console.log("Websocket connected.");
        photoStore.websocketConnection = true;
    });
    connection.bind('connecting', () => {
        console.log("Websocket disconnected. :(");
        photoStore.websocketConnection = false;
    });
}


onMounted(() => {
    if (photoStore.photos.length <= 0) {
        photoStore.refresh();
    }
    registerStateHooks();
});

//
// useEcho.connector.pusher.connection.bind('connected', () => {
//       console.log('connected');
//     });


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
                <DebugButton />
            </div>
        </template>
    </AuthLayout>
</template>

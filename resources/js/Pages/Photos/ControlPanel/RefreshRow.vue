<script setup lang="ts">
import { usePhotosStore } from '@/stores/photosStore';
import { toFormattedDate } from '@/utils/dateHelper';
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { computed } from 'vue';


const photoStore = usePhotosStore();

const handleRefreshClick = () => {
    photoStore.refresh();
}

const lastRefreshDate = computed(() => {
    return toFormattedDate(photoStore.refreshedAt, "dd.MM.yyyy HH:mm:ss");
})

const websocketConnection = computed(() => {
    return photoStore.websocketConnection;
})

const getColorClass = () => {
    const connectedToWebSocket = !websocketConnection.value;
    return connectedToWebSocket ? "bg-error" : "bg-success";
}

</script>

<template>
    <div class="flex flex-row gap-6">
        <button class="btn btn-primary aspect-square h-24 rounded-xl" @click="handleRefreshClick">
            <font-awesome-icon icon="fa-solid fa-rotate-right" class="text-2xl" />
        </button>
        <div class="bg-primary rounded-xl flex-1 flex justify-center align-middle items-center text-md text-center">
            <p class="p-0 m-0">Aktualizované: <br><strong>{{ lastRefreshDate }}</strong></p>
        </div>
        <div class="rounded-xl h-24 aspect-square flex flex-col justify-center align-middle items-center text-center gap-1"
            :class="getColorClass()">
            <font-awesome-icon v-if="!websocketConnection" icon="fa-solid fa-circle-exclamation" class="text-3xl" />
            <font-awesome-icon v-else icon="fa-solid fa-circle-check" class="text-3xl" />
            <p class="m-0 p-0">WebSocket</p>
        </div>
    </div>
</template>

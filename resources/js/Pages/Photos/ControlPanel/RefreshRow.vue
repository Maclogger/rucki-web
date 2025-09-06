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


</script>

<template>
    <div class="flex flex-row gap-6">
        <button class="btn btn-primary aspect-square h-24 rounded-xl" @click="handleRefreshClick">
            <font-awesome-icon icon="fa-solid fa-rotate-right" class="text-2xl" />
        </button>
        <div class="bg-primary rounded-xl flex-1 flex justify-center align-middle items-center text-md">
            <p class="p-0 m-0">Aktualizované {{ lastRefreshDate }}</p>
        </div>
    </div>
</template>

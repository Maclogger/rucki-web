<script setup lang="ts">
import { computed, ComputedRef, onMounted, onUpdated, ref } from 'vue';
import PhotoComponent from './PhotoComponent.vue';
import PhotoSkeleton from './PhotoSkeleton.vue';
import { useFilesStore } from '@/stores/filesStore';
import { storeToRefs } from 'pinia';
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { File } from '@/Classes/File';

const filesStore = useFilesStore();

const { files, currentPage } = storeToRefs(filesStore);

const maxPageNumber = ref<number>(1);
const PHOTOS_PER_PAGE: number = 9;

interface FileItem {
    file: File | null,
    key: string
}

const makeid = (length: number) => {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

const paginatedFiles: ComputedRef<FileItem[]> = computed(() => {
    const startIndex = (currentPage.value - 1) * PHOTOS_PER_PAGE;
    const endIndex = startIndex + PHOTOS_PER_PAGE;
    const currentPhotos = files.value.slice(startIndex, endIndex);

    const fileItems: Array<FileItem> = currentPhotos.map(file => {
        return {
            file: file,
            key: file.fileName,
        };
    });

    if (currentPhotos.length < PHOTOS_PER_PAGE) {
        const skeletonsToAdd = PHOTOS_PER_PAGE - currentPhotos.length;
        for (let i = 0; i < skeletonsToAdd; i++) {
            fileItems.push({ file: null, key: makeid(5) });
        }
    }
    return fileItems;
});

onMounted(() => {
    currentPage.value = 1;
    updateMaxPageNumber();
});

const updateMaxPageNumber = () => {
    maxPageNumber.value = Math.ceil(files.value.length / PHOTOS_PER_PAGE);
}

const nextPageClick = () => {
    currentPage.value++;
}

const previousPageClick = () => {
    currentPage.value--;
}

const firstPageClick = () => {
    currentPage.value = 1;
}

const lastPageClick = () => {
    currentPage.value = maxPageNumber.value;
}

onUpdated(() => {
    updateMaxPageNumber();
})

</script>

<template>
    <div class="flex flex-col gap-4">
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-3 gap-4">
            <div id="all-photos-div" v-for="(photoItem) in paginatedFiles" :key="photoItem.key">
                <template v-if="photoItem.file">
                    <PhotoComponent :photo="photoItem.file" />
                </template>
                <template v-else>
                    <PhotoSkeleton />
                </template>
            </div>
        </div>
        <div class="join w-full items-center justify-center">
            <button class="join-item btn" @click="firstPageClick">
                <font-awesome-icon icon="fa-solid fa-backward" />
            </button>
            <button class="join-item btn" @click="previousPageClick" :disabled="currentPage == 1">
                <font-awesome-icon icon="fa-solid fa-backward-step" />
            </button>
            <button class="join-item btn btn-primary">
                {{ currentPage }} / {{ maxPageNumber }}
            </button>
            <button class="join-item btn" @click="nextPageClick" :disabled="currentPage == maxPageNumber">
                <font-awesome-icon icon="fa-solid fa-forward-step" />
            </button>
            <button class="join-item btn" @click="lastPageClick">
                <font-awesome-icon icon="fa-solid fa-forward" />
            </button>
        </div>
    </div>
</template>

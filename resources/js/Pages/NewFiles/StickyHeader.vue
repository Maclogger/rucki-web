<script setup lang="ts">

import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {useFilesStore} from "@/stores/filesStore";
import {storeToRefs} from "pinia";
import {ref, watch} from "vue";


const filesStore = useFilesStore();

const { getVisibleFiles: visibleFiles } = storeToRefs(filesStore);

const showPicturesFilter = ref(true);
const showFilesFilter = ref(true);

watch(showPicturesFilter, (newValue: boolean) => {
    if (newValue) {
        filesStore.enablePicturesFilter();
    } else {
        filesStore.disablePicturesFilter();
    }
});

watch(showFilesFilter, (newValue: boolean) => {
    if (newValue) {
        filesStore.enableFilesFilter();
    } else {
        filesStore.disableFilesFilter();
    }
});

</script>

<template>
    <div class="bg-primary-dark-transparent rounded-xl h-18">
        <div class="h-full flex flex-row justify-between content-between gap-8 px-4">
            <div class="flex items-center">
                <button class="btn btn-primary">
                    <font-awesome-icon icon="fa-solid fa-filter" class=""/>
                </button>
            </div>
            <div class="flex items-center content-between gap-2">
                <input v-model="showPicturesFilter" class="btn" type="checkbox" name="frameworks" aria-label="Obrázky"/>
                <input v-model="showFilesFilter" class="btn" type="checkbox" name="frameworks" aria-label="Súbory"/>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {computed, ref} from 'vue';
import BottomRowButton from './BottomRowButton.vue';
import {File} from '@/Classes/File';


const props = defineProps<{
    file: File
    afterClick?: () => void
}>();

const isDownloaded = ref<boolean>(false);


const handleClick = async () => {
    props.file.download();
    if (props.afterClick) {
        props.afterClick();
    }
}

const getIcon = computed(() => {
    return "fa-solid " + (isDownloaded.value ? "fa-check" : "fa-download");
});

const getColorClass = computed(() => {
    return isDownloaded.value ? "bg-success" : "bg-primary";
});

</script>

<template>
    <BottomRowButton :icon="getIcon" :onClick="handleClick"
                     class="hover:bg-my-white hover:text-primary" :class="getColorClass">
        Stiahnuť
    </BottomRowButton>
</template>

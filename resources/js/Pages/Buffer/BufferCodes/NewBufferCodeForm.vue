<script setup lang="ts">

import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {useForm} from '@inertiajs/vue3';
import {usePublicStore} from "@/stores/publicStore";
import {computed} from "vue";
import {usesBufferCodesStore} from "@/stores/bufferCodesStore";

const publicStore = usePublicStore();

const bufferCodeLength = computed(() => {
    return parseInt(publicStore.getConstant("bufferCodeLength") ?? "4");
})

const form = useForm({
    code: '',
    enabled: false,
});

const bufferCodesStore = usesBufferCodesStore();

const submit = () => {
    bufferCodesStore.createNewBufferCode(form);
};

const onCodeInputChange = () => {
    form.code = form.code
        .replace(/[^a-zA-Z0-9]/g, '')
        .slice(0, bufferCodeLength.value)
        .toUpperCase();
}

const submitIsDisabled = () => {
    return form.code.length != bufferCodeLength.value;
}

</script>

<template>
    <div
        class="w-full h-full rounded-xl bg-primary-dark-transparent px-4 pb-4 pt-1 flex flex-col gap-4 max-w-90 overflow-hidden">

        <form @submit.prevent="submit">
            <fieldset class="fieldset bg-base-100 border-base-300 rounded-box border p-4 gap-4 w-full">
                <legend class="fieldset-legend text-lg">Nový kód</legend>
                <input v-model="form.code" type="text" placeholder="Kód" class="input" @input="onCodeInputChange"/>
                <div class="flex flex-row gap-8 justify-between content-between">
                    <label class="label">
                        <input v-model="form.enabled" type="checkbox" class="checkbox checkbox-primary"/>
                        Aktivovaný
                    </label>
                    <button class="btn btn-primary" type="submit" :disabled="submitIsDisabled()">
                        <font-awesome-icon icon="fa-solid fa-circle-plus"/>
                        Vytvoriť
                    </button>
                </div>
            </fieldset>
        </form>
    </div>
</template>

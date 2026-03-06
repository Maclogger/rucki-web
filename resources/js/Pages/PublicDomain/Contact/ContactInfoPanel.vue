<script setup lang="ts">

import ContactRow from "@/Pages/PublicDomain/Contact/ContactRow.vue";
import {usePublicStore} from "@/stores/publicStore";
import {computed} from "vue";


const store = usePublicStore();

const fullName = computed(() => store.getFullName());

const phoneNumber = computed(() => store.getConstant("phoneNumber") ?? "+421 918 024 666");
const phoneNumberLink = computed(() => `tel:${phoneNumber.value?.replace(/\s/g, '') ?? "+421918024666"}`);

const email = computed(() => store.getConstant("mail"));
const emailLink = computed(() => `mailto:${email.value ?? 'marek@rucki.sk'}`);

const location = computed(() => store.getConstant("currentLocation") ?? "Žilina");
const locationLink = computed(() => store.getConstant("currentLocationLink") ?? "https://maps.app.goo.gl/dxj9sqUpHG44Vqro6");

const gitHubUserName = computed(() => store.getConstant("githubUserName") ?? "Marek Rucki");
const gitHubLink = computed(() => store.getConstant("gitHubLink") ?? "https://github.com/Maclogger");

</script>

<template>
    <div class="text-center mb-6">
        <img
            src="/images/profile_pic.png"
            alt="profile picture"
            class="rounded-full object-cover w-48 h-48 mx-auto shadow-lg mb-6"/>
        <h2 class="text-3xl font-bold text-white mb-2">{{ fullName }}</h2>
    </div>

    <div class="space-y-4 text-gray-200">
        <ContactRow headline="Telefón" icon="fa-solid fa-phone" :value="phoneNumber" :href="phoneNumberLink"/>
        <ContactRow headline="Email" icon="fa-solid fa-envelope" :value="email" :href="emailLink"/>
        <ContactRow headline="Aktuálne bydlisko" icon="fa-solid fa-location-dot"
                    :value="location" :href="locationLink"/>
        <ContactRow headline="GitHub" icon="fa-brands fa-github" :value="gitHubUserName" :href="gitHubLink"/>
    </div>
</template>

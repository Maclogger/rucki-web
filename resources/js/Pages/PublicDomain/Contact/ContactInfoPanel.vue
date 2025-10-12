<script setup lang="ts">

import ContactRow from "@/Pages/PublicDomain/Contact/ContactRow.vue";
</script>

<template>
    <div class="text-center mb-6">
        <img
            src="/images/profile_pic.png"
            alt="profile picture"
            class="rounded-full object-cover w-48 h-48 mx-auto shadow-lg mb-6"/>
        <h2 class="text-3xl font-bold text-white mb-2">Bc. Marek Rucki</h2>
    </div>

    <div class="space-y-4 text-gray-200">
        <ContactRow headline="Telefón" icon="fa-solid fa-phone" value="+421 918 024 666"/>
        <ContactRow headline="Email" icon="fa-solid fa-envelope" value="marek@rucki.sk"/>
        <ContactRow headline="Aktuálne bydlisko" icon="fa-solid fa-location-dot" value="Žilina"/>
        <ContactRow headline="GitHub" icon="fa-brands fa-github" value="Maclogger"/>
    </div>
</template>

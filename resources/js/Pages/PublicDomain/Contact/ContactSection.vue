<script setup lang="ts">

import ContactInfoPanel from "@/Pages/PublicDomain/Contact/ContactInfoPanel.vue";
import ContactMail from "@/Pages/PublicDomain/Contact/ContactMail.vue";
</script>

<template>
    <div class="container mx-auto py-12">
        <div class="flex flex-col md:flex-row gap-8 items-start">
            <div class="w-full md:w-2/5 bg-gradient-to-br from-primary-dark to-primary-dark-transparent rounded-2xl shadow-2xl p-8">
                <ContactInfoPanel/>
            </div>

            <div class="w-full md:w-3/5">
                <ContactMail/>
            </div>
        </div>
    </div>
</template>

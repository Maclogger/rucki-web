<script setup lang="ts">

</script>

<template>
    <div class="mockup-window border border-base-300 shadow-2xl">
        <div class="bg-base-200 px-8 py-10">
            <h3 class="text-2xl font-bold  mb-6">Napíšte mi správu</h3>
            <form class="space-y-4">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend text-lg">Vaše meno</legend>
                    <input type="text" placeholder="Ján Novák" class="input w-full text-lg"/>
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend text-lg">Váš Email</legend>
                    <input type="email" placeholder="ja.novak@example.com" class="input w-full text-lg"/>
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend text-lg">Predmet</legend>
                    <input type="email" placeholder="O čom chcete hovoriť?" class="input w-full text-lg"/>
                </fieldset>


                <fieldset class="fieldset">
                    <legend class="fieldset-legend text-lg">Správa</legend>
                    <textarea class="textarea h-24 w-full text-lg" placeholder="Bio"></textarea>
                </fieldset>

                <button class="btn btn-primary w-full text-lg py-6">Odoslať správu</button>
            </form>
        </div>
    </div>

</template>

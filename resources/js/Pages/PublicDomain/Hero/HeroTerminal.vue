<script setup lang="ts">
// A fake neofetch: the layout every Linux user recognises, and a good CV at the
// same time. Kept as real text so it stays selectable, indexable and translatable.
const facts = [
    ["role", "Software Developer"],
    ["uptime", "5 rokov v praxi"],
    ["stack", "Vue · Laravel · Java · Oracle"],
    ["editor", "Neovim"],
    ["wm", "Hyprland"],
    ["location", "Žilina, Slovensko"],
];

const projects = ["rucki-web/", "kam-na-obedik/", "nvim-config/", "bakalarska-praca/"];
</script>

<template>
    <div class="h-full overflow-hidden p-[clamp(0.75rem,2vw,1.75rem)] font-mono text-[clamp(0.6rem,1.25vw,1.0625rem)] leading-relaxed">
        <p class="text-gray-400"><span class="text-primary-light-ultra">marek@rucki-sk</span>:~$ neofetch</p>

        <div class="mt-[clamp(0.5rem,1.5vw,1.25rem)] flex items-start gap-[clamp(0.75rem,2.5vw,2rem)]">
            <img
                src="/images/profile_2026.png"
                alt="Marek Rucki"
                class="w-[clamp(4rem,14vw,9rem)] shrink-0 rounded-md object-cover"
            />

            <div class="min-w-0">
                <h1 class="text-primary-light-ultra">Ing. Marek Rucki</h1>
                <p class="text-gray-500">-----------------</p>

                <dl class="mt-1 grid grid-cols-[auto_1fr] gap-x-[clamp(0.5rem,1.5vw,1.5rem)]">
                    <template v-for="[key, value] in facts" :key="key">
                        <dt class="text-primary-light-ultra">{{ key }}</dt>
                        <dd class="truncate">{{ value }}</dd>
                    </template>
                </dl>
            </div>
        </div>

        <p class="mt-[clamp(0.5rem,1.5vw,1.25rem)] text-gray-500">
            # Staviam webové aplikácie od schémy databázy po posledný pixel rozhrania.
        </p>
        <p class="mt-[clamp(0.5rem,1.5vw,1.25rem)] text-gray-400">
            <span class="text-primary-light-ultra">marek@rucki-sk</span>:~$ ls -1 ~/projects
        </p>
        <ul class="text-base-content">
            <li v-for="project in projects" :key="project">{{ project }}</li>
        </ul>

        <p class="mt-[clamp(0.5rem,1.5vw,1.25rem)] text-gray-400">
            <span class="text-primary-light-ultra">marek@rucki-sk</span>:~$
            <span class="ml-1 inline-block h-[1em] w-[0.55em] translate-y-[0.15em] bg-base-content"></span>
        </p>
    </div>
</template>

<script setup lang="ts">

import TimelineItem from "@/Pages/PublicDomain/History/TimelineItem.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
</script>

<template>
    <ul class="timeline timeline-snap-icon max-md:timeline-compact timeline-vertical">
        <TimelineItem>
            <template #middle>
                <font-awesome-icon icon="fa-solid fa-school" class="text-2xl"/>
            </template>
            <template #left>
                <time class="font-mono italic">2017 - 2021</time>
                <div>
                    <a class="text-xl font-bold link" href="https://www.gympd.sk/" target="_blank">Gymnázium Vavrinca Benedikta Nedožerského
                        (Prievidza)</a>
                </div>
            </template>
        </TimelineItem>
        <TimelineItem>
            <template #middle>
                <font-awesome-icon icon="fa-solid fa-school" class="text-2xl"/>
            </template>
            <template #right>
                <time class="font-mono italic">2021 - súčasnosť</time>
                <div class="mt-4 flex gap-4 flex-wrap md:flex-nowrap">
                    <img src="/images/assets/fri.png" class="rounded-2xl w-[5.8rem] h-[6rem]" alt="Fakulta Riadenia a Informatiky">
                    <div>
                        <a class="text-xl font-bold link" href="https://www.fri.uniza.sk/" target="_blank">
                            Žilinská univerzita v Žiline - Fakulta riadenia a informatiky
                        </a>
                        <p>Tu som naučil základy toho, čo milujem 💜 - <strong>PROGRAMOVANIE</strong>.</p>
                    </div>
                </div>
            </template>
        </TimelineItem>
        <TimelineItem>
            <template #middle>
                <font-awesome-icon icon="fa-solid fa-briefcase" class="text-2xl"/>
            </template>
            <template #left>
                <time class="font-mono italic">2024 - 2025</time>
                <div class="mt-4 flex gap-4">
                    <div>
                        <a href="https://infoconsulting.com/" target="_blank" class="text-xl font-bold link">
                            InfoConsulting Slovakia s. r. o.
                        </a>
                        <p>Venoval som sa spravovaniu komplexného <strong>ERP systému</strong> a vykonávaniu jeho úprav podľa
                            požiadaviek klienta.<br/>
                            Backend: <strong>Oracle PL/SQL.</strong>
                            Frontend: <strong>.NET Framework - C#.</strong></p>
                    </div>
                    <img src="/images/assets/ic.png" class="rounded-2xl  h-[6rem]" alt="InfoConsulting">
                </div>
            </template>
        </TimelineItem>
        <TimelineItem>
            <template #middle>
                <font-awesome-icon icon="fa-solid fa-briefcase" class="text-2xl"/>
            </template>
            <template #right>
                <time class="font-mono italic">2025 - súčasnosť</time>
                <div class="mt-4 flex gap-4">
                    <img src="/images/assets/ipesoft.jpg" class="rounded-2xl w-[5.8rem] h-[6rem]" alt="IPESOFT">
                    <div>
                        <a class="text-xl font-bold link" href="https://ipesoft.com/" target="_blank">
                            IPESOFT s.r.o.
                        </a>
                        <p>
                            Pracujem ako <strong>Full-Stack Developer</strong> na vývoji a údržbe aplikácií pre klientov v oblasti
                            energetiky. Frontend tvorím v technológii <strong>React</strong> a Backend v jazyku <strong>Java</strong>,
                            alebo priamo v proprietárnom nástroji <strong>D2000</strong>.
                        </p>
                    </div>
                </div>
            </template>
        </TimelineItem>
    </ul>
</template>


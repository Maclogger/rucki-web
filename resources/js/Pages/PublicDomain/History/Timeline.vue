<script setup lang="ts">

import TimelineItem from "@/Pages/PublicDomain/History/TimelineItem.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import TimeLineItemContent from "@/Pages/PublicDomain/History/TimeLineItemContent.vue";
</script>

<template>
    <ul class="timeline timeline-snap-icon max-md:timeline-compact timeline-vertical">
        <TimelineItem time="2017 - 2021">
            <template #middle>
                <font-awesome-icon icon="fa-solid fa-school" class="text-2xl"/>
            </template>
            <template #left>
                <TimeLineItemContent time="2021 - súčasnosť"
                                     headline="Žilinská univerzita v Žiline - Fakulta riadenia a informatiky"
                                     url="https://www.fri.uniza.sk/"
                                     img-src="/images/assets/fri.png"
                                     position="left">
                    <p>
                        Tu som naučil základy toho, čo milujem 💜 - <strong>PROGRAMOVANIE</strong>.
                    </p>
                </TimeLineItemContent>
            </template>
        </TimelineItem>
        <TimelineItem>
            <template #middle>
                <font-awesome-icon icon="fa-solid fa-school" class="text-2xl"/>
            </template>
            <template #right>
                <TimeLineItemContent time="2020"
                                     headline="Webová stránka - GlowWay s.r.o."
                                     url="https://www.glowway.de/"
                                     img-src="/images/assets/glowway.png"
                                     img-class="bg-white px-4 h-[7rem]"
                                     position="right">
                    <p>
                        Jeden z mojich prvých projektov bol <strong>firemný web</strong> pre spoločnosť GlowWay s.r.o., ktorú
                        som vytvoril pomocou technológie <strong>Wordpress, CSS a trochu JavaScriptu</strong>.
                    </p>
                </TimeLineItemContent>
            </template>
        </TimelineItem>
        <TimelineItem>
            <template #middle>
                <font-awesome-icon icon="fa-solid fa-school" class="text-2xl"/>
            </template>
            <template #left>
                <TimeLineItemContent time="2023 - 2024"
                                     headline="Mobilná aplikácia - Správa pacientov a vyšetrení"
                                     url="https://github.com/Maclogger/bakalarska-praca"
                                     img-src="/images/icons/flutter.svg"
                                     position="left">
                    <p>
                        Ako moja bakalárska práca vznikla <strong>mobilná aplikácia v Flutteri</strong> na evidenciu a správu pacientov a
                        ich vyšetrení. Aplikácia bola navrhnutá s dôrazom na <strong>bezpečnosť</strong>.
                    </p>
                </TimeLineItemContent>
            </template>
        </TimelineItem>
        <TimelineItem>
            <template #middle>
                <font-awesome-icon icon="fa-solid fa-briefcase" class="text-2xl"/>
            </template>
            <template #right>
                <TimeLineItemContent time="2021 - súčasnosť"
                                     headline="InfoConsulting Slovakia s. r. o."
                                     url="https://infoconsulting.com/"
                                     img-src="/images/assets/ic.png"
                                     position="right">
                    <p>
                        Venoval som sa spravovaniu komplexného <strong>ERP systému</strong> a vykonávaniu jeho úprav podľa
                        požiadaviek klienta.<br/>
                        Backend: <strong>Oracle PL/SQL.</strong>
                        Frontend: <strong>.NET Framework - C#.</strong>
                    </p>
                </TimeLineItemContent>
            </template>
        </TimelineItem>
        <TimelineItem>
            <template #middle>
                <font-awesome-icon icon="fa-solid fa-briefcase" class="text-2xl"/>
            </template>
            <template #left>
                <TimeLineItemContent time="2025 - súčasnosť"
                                     headline="IPESOFT s.r.o."
                                     url="https://ipesoft.com/"
                                     img-src="/images/assets/ipesoft.jpg"
                                     position="left">
                    <p>
                        Pracujem ako <strong>Full-Stack Developer</strong> na vývoji a údržbe aplikácií pre klientov v oblasti
                        energetiky. Frontend tvorím v technológii <strong>React</strong> a Backend v jazyku <strong>Java</strong>,
                        alebo priamo v proprietárnom nástroji <strong>D2000</strong>.
                    </p>
                </TimeLineItemContent>
            </template>
        </TimelineItem>
    </ul>
</template>


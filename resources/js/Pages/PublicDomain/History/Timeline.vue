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
                <TimeLineItemContent time="2017 - 2021"
                                     headline="Gymnázium Vavrinca Benedikta Nedožerského v Prievidzi"
                                     url="https://www.gympd.sk/" position="left"/>
            </template>
        </TimelineItem>
        <TimelineItem>
            <template #middle>
                <font-awesome-icon icon="fa-solid fa-school" class="text-2xl"/>
            </template>
            <template #right>
                <TimeLineItemContent time="2021 - súčasnosť"
                                     headline="Žilinská univerzita v Žiline - Fakulta riadenia a informatiky"
                                     url="https://www.fri.uniza.sk/"
                                     img-src="/images/assets/fri.png"
                                     position="right">
                    <p>
                        Tu som naučil základy toho, čo milujem 💜 - <strong>PROGRAMOVANIE</strong>.
                    </p>
                </TimeLineItemContent>
            </template>
        </TimelineItem>
        <TimelineItem>
            <template #middle>
                <font-awesome-icon icon="fa-solid fa-briefcase" class="text-2xl"/>
            </template>
            <template #left>
                <TimeLineItemContent time="2021 - súčasnosť"
                                     headline="InfoConsulting Slovakia s. r. o."
                                     url="https://infoconsulting.com/"
                                     img-src="/images/assets/ic.png"
                                     position="left">
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
            <template #right>
                <TimeLineItemContent time="2025 - súčasnosť"
                                     headline="IPESOFT s.r.o."
                                     url="https://ipesoft.com/"
                                     img-src="/images/assets/ipesoft.jpg"
                                     position="right">
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


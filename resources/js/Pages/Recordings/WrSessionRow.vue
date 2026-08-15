<script setup lang="ts">

import {WrSession} from "@/Pages/Recordings/recordings.types";
import {toFormattedDate, toNicelyFormattedDate} from "@/utils/dateHelper";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

defineProps<{
    session: WrSession;
    onClick: () => void;
}>();


</script>

<template>


    <tr>
        <th>{{ session.id_session }}</th>
        <td>{{ session.visitor.id_visitor }}</td>
        <td>{{ session.events_count }}</td>
        <td>{{ toFormattedDate(session.created_at, "dd.MM.yyyy HH:mm:ss") }}</td>
        <td>
            <button class="btn btn-primary btn-square" @click="onClick">
                <font-awesome-icon icon="fa-solid fa-circle-play"/>
            </button>
        </td>
    </tr>
</template>

<style scoped>

</style>

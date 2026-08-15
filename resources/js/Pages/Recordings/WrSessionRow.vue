<script setup lang="ts">

import {WrSession} from "@/Pages/Recordings/recordings.types";
import {toFormattedDate} from "@/utils/dateHelper";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

defineProps<{
    session: WrSession;
    onClick: () => void;
    onDelete: () => void;
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
        <td>
            <button class="btn btn-error btn-square" @click="onDelete">
                <font-awesome-icon icon="fa-solid fa-trash-can"/>
            </button>
        </td>
    </tr>
</template>

<style scoped>

</style>

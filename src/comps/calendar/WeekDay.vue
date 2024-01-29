<template>
    <div class="weekDay d-flex flex-column justify-content-start align-items-center rounded-2 overflow-hidden my-2 border border-black border-3">
        <div @click="toggleBody" class="weekdayHeader py-1 px-2 bg-sec justify-content-start align-items-center">
            <h5 class="m-0">{{ weekday }}, {{ day }}.{{ month }}.{{ year }}</h5>
        </div>

        <div :class="{is_visible: bodyVisible}" class="weekdayBody p-1 flex-column justify-content-start align-items-center border border-success">
            <week-eventitem
            v-for="event in dayevents"
            :key="event.id"
            :event="event"
            :trainers="trainers"
            :students="students">

            </week-eventitem>
        </div>
    </div>
</template>

<script setup>
import { defineProps, computed, ref, onMounted } from 'vue';

import WeekEventitem from './WeekEventitem.vue';

let props = defineProps([
    "date",
    "dayevents",
    "trainers",
    "students",
]);
onMounted(() => {
    console.table(props.dayevents);
})
const bodyVisible = ref(true);
function toggleBody() {
    bodyVisible.value = !bodyVisible.value;
}
const weekday = computed(() => {
    return props.date.toLocaleDateString("en-US", {
        weekday: "long",
    });
});
const day = computed(() => {
    return props.date.toLocaleDateString("en-US", {
        day: "2-digit",
    });
});
const month = computed(() => {
    return props.date.toLocaleDateString("en-US", {
        month: "2-digit",
    });
});
const year = computed(() => {
    return props.date.getFullYear();
});
</script>

<style scoped>
.weekdayBody.is_visible {
    display: flex;
}
.weekdayBody {
    width: 100%;
    min-height: 20px;
    display: none;
}
.weekdayHeader {
    width: 100%;
    font-family: "Raleway SBold 600";
}
.weekDay {
    width: 100%;
}
</style>
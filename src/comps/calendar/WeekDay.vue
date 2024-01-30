<template>
    <div class="weekDay mx-xxl-1 d-flex flex-column justify-content-start align-items-center rounded-2 overflow-hidden my-2 border border-black border-3">
        <div @click="toggleBody" class="weekdayHeader py-1 px-2 px-xxl-1 bg-sec justify-content-start align-items-center">
            <h5 class="m-0">{{ weekday }},<br class="d-none d-xxl-block"> {{ day }}.{{ month }}.{{ year }}</h5>
        </div>

        <div :class="{is_visible: bodyVisible}" class="weekdayBody p-1 flex-column justify-content-start align-items-center">
            <week-eventitem
            v-for="event in sorted(dayevents)"
            :key="event.id"
            :event="event"
            :trainers="trainers"
            :students="students"
            @signin-success="handleSigning"
            @trainer-assigned="handleTrainerAssignment">

            </week-eventitem>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits, ref, onMounted, computed } from 'vue';

import WeekEventitem from './WeekEventitem.vue';

let props = defineProps([
    "date",
    "dayevents",
    "trainers",
    "students",
]);

function sorted(events) {
    return events.sort((a, b) => {
        const dateA = new Date(+a.year, +a.month - 1, +a.day,  +a.hour + 1, +a.minute, 0, 0);
        const dateB = new Date(+b.year, +b.month - 1, +b.day,  +b.hour + 1, +b.minute, 0, 0);

        return dateA - dateB;
    });
}
onMounted(() => {
    console.clear();
    if(innerWidth > 1399)
    {
        bodyVisible.value = true;
    }
});
let emits = defineEmits([
    "students-changed",
    "trainer-assigned",
]);
// REDIRECTS CHANGE OF THE PROVIDED EVENT DATA TO <TheCal>
function handleSigning(data) {
    emits("students-changed", data);
}
function handleTrainerAssignment(data) {
    emits("trainer-assigned", data);
}
// TOGGLES VISIBILITY OF WEEKDAY BODIES
const bodyVisible = ref(false);
function toggleBody() {
    if(innerWidth < 1400)
    {
        bodyVisible.value = !bodyVisible.value;
    }
}
// RETURNS THE RELEVANT WEEKDAY DATA TO DISPLAY OUT OF THE WEEKARRAY
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

@media screen and (min-width: 1400px) {
    .weekDay {
        width: unset;
        flex: 1;
    }
    .weekdayHeader {
        min-height: 55px;
    }
    .weekdayHeader h5 {
        font-size: 18px;
    }
}
</style>
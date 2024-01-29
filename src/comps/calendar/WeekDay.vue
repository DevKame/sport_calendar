<template>
    <div class="weekDay d-flex flex-column justify-content-start align-items-center rounded-2 overflow-hidden my-2 border border-black border-3">
        <div class="weekdayHeader py-1 px-2 bg-sec justify-content-start align-items-center border border-info">
            <h5 class="m-0">{{ weekday }}, {{ day }}.{{ month }}.{{ year }}</h5>
        </div>

        <div class="weekdayBody p-3 d-flex flex-column justify-content-start align-items-center border border-success">
            <slot name="body"></slot>
        </div>
    </div>
</template>

<script setup>
import { defineProps, computed, onMounted } from 'vue';
let props = defineProps([
    "date",
    "dayevents",
]);

onMounted(() => {
    console.error(props.dayevents);
})
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
.weekdayBody {
    width: 100%;
    min-height: 20px;
}
.weekdayHeader {
    width: 100%;
}
.weekDay {
    width: 100%;
}
</style>
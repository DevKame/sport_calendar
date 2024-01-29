<template>
    <ov-load v-if="loadingRoute" class="mt-3"></ov-load>
    <div v-else @click="overviewClickHandler" class="overflow-x-hidden pt-1 px-3 d-flex flex-column justify-content-start align-items-center">
        <section class="cal-interface w-100 d-flex justify-content-center align-items-center p-2 border border-danger">
            
            <div class="week-pre h-100 week-nav d-flex flex-column justify-content-start align-items-center me-3">
                <fa-icon icon="fa-solid fa-backward" size="xl"></fa-icon>
            </div>
            
            <div class="h-100">
                <h5 class="m-0">13.01.2023 - 19.01.2023</h5>
            </div>

            <div class="week-next h-100 week-nav d-flex flex-column justify-content-start align-items-center ms-3">
                <fa-icon @click="changeWeek('next')" icon="fa-solid fa-forward" size="xl"></fa-icon>
            </div>

        </section>

        <div  v-if="weekChangeDone" class="weekdayHolder d-flex flex-column justify-content-center align-items-center border border-dark">
            <week-day v-for="day in THIS_WEEK"
            :key="day.getDate()"
            :date="day"
            :dayevents="eventsOfDay(day.getDate())">
            </week-day>
        </div>

    </div>
</template>

<script setup>
import { onMounted, ref, defineEmits } from "vue";
import { useStore } from "vuex";

import WeekDay from "./WeekDay.vue";

let emits = defineEmits([
    "empty-click",
]);
function overviewClickHandler() {
    emits("empty-click");
}

const store = useStore();

// LOADING ANIMATION UNTIL EVERYTHING IS FETCHED
const loadingRoute = ref(true);

// TODAYS DATE, STARTING POINT FOR THIS COMP
const TODAY = ref();
// ARRAY WHERE THE DAYS OF CURRENT WEEK ARE SAVED:
const THIS_WEEK = ref([]);
// FIRST DAY (MONDAY) OF THE CURRENT WEEK INFERRED FROM CURRENT DAY
const FIRST_WEEKDAY = ref("");
// FETCHED GROUPS AND STUDENTS ARE SAVED WITHIN HERE
const groupArray = ref([]);
const studentArray = ref([]);
const trainerArray = ref([]);
const weekeventArray = ref([]);

onMounted(async () => {
    // GET ACTUAL DATE
    TODAY.value = new Date();
    // TODAY.value = new Date(2024, 2, 28);
    // GET ALL GROUPS
    const groupdata = await store.dispatch("groups/getAllGroups");
    groupArray.value = [...groupdata.groups];
    // GET ALL STUDENTS
    const studentreq = {task:"get-name-and-id"};
    const studentdata = await store.dispatch("students/post", studentreq);
    studentArray.value = [...studentdata.students];
    // GET ALL TRAINERS
    const trainerreq = {task:"get-name-and-id"};
    const trainerdata = await store.dispatch("trainers/post", trainerreq);
    trainerArray.value = [...trainerdata.trainers];
    // IDENTIFIES EVERY DATE OF THE ACTUAL WEEK:
    fillWeekArray();
    console.clear();
    console.table(THIS_WEEK.value);
    console.log(THIS_WEEK.value[0].getMonth());
    weekeventArray.value = await getEventsForWeek(THIS_WEEK.value);
    console.table(weekeventArray.value);

    loadingRoute.value = false;
});

const weekChangeDone = ref(true);

function changeWeek(val) {
    weekChangeDone.value = false;
    console.clear();
    console.log("TODAY VOR CHANGE:", TODAY.value);
    switch(val) {
        case "next":
            TODAY.value.setDate(TODAY.value.getDate() + 7);
            break;
    }
    console.log("TODAY NACH CHANGE:", TODAY.value);
    weekChangeDone.value = true;
}
/** FILTERS THE EVENTS FOR THE PARTICULAR DAY day OUT OF ALL FETCHED EVENTS
 *  AND RETU´RNS THEM AS ARRAY
 *  @param {number} day             => DAY VALUE OF DAY IN QUESTION
 *  @returns {Array} dayevents      => ARRAY WITH ALL EVENTS FOR DAY 'day' */
function eventsOfDay(day) {
    day = day.toString();
    console.log(day);
    let dayevents = weekeventArray.value.filter(curr => curr.day === day);
    console.warn(dayevents);
    return dayevents;
}
/** FETCHES EVERY EVENT FROM DATABSE THAT HAPPENS THIS WEEK
 *  @param {Array} w            => THIS_WEEK */
async function getEventsForWeek(w) {
    console.table(w);
    let daydata = [];
    let req =
    {
        task: "fetch_termine_for_week",
    };
    for(let d of w) {
        daydata.push([d.getDate(), d.getMonth() + 1, d.getFullYear()]);
    }
    req.daydata = daydata;
    let weekeventdata = await store.dispatch("events/post", req);
    console.error(weekeventdata);
    return weekeventdata.events;
}
/** FILLS THIS_WEEK WITH THE DATES OF EVERY DAY OF THIS WEEK */
function fillWeekArray() {
    let currentDate = new Date(+TODAY.value.getFullYear(), +TODAY.value.getMonth(), +TODAY.value.getDate());
    switch(currentDate.getDay()) {
        case 1:
            FIRST_WEEKDAY.value =
            new Date(+TODAY.value.getFullYear(), +TODAY.value.getMonth(), +TODAY.value.getDate());
            break;
        case 2:
            FIRST_WEEKDAY.value =
            new Date(+TODAY.value.getFullYear(), +TODAY.value.getMonth(), +TODAY.value.getDate() - 1);
            break;
        case 3:
            FIRST_WEEKDAY.value =
            new Date(+TODAY.value.getFullYear(), +TODAY.value.getMonth(), +TODAY.value.getDate() - 2);
            break;
        case 4:
            FIRST_WEEKDAY.value =
            new Date(+TODAY.value.getFullYear(), +TODAY.value.getMonth(), +TODAY.value.getDate() - 3);
            break;
        case 5:
            FIRST_WEEKDAY.value =
            new Date(+TODAY.value.getFullYear(), +TODAY.value.getMonth(), +TODAY.value.getDate() - 4);
            break;
        case 6:
            FIRST_WEEKDAY.value =
            new Date(+TODAY.value.getFullYear(), +TODAY.value.getMonth(), +TODAY.value.getDate() - 5);
            break;
        case 0:
            FIRST_WEEKDAY.value =
            new Date(+TODAY.value.getFullYear(), +TODAY.value.getMonth(), +TODAY.value.getDate() - 6);
            break;
    }
    for(let i = 0 ; i < 7; i++)
    {
        let newDate =
        new Date(+FIRST_WEEKDAY.value.getFullYear(), +FIRST_WEEKDAY.value.getMonth(), +FIRST_WEEKDAY.value.getDate() + i);
        THIS_WEEK.value.push(newDate);
    }
}
</script>

<style scoped>
.weekdayHolder {
    width: 100%;
}
</style>
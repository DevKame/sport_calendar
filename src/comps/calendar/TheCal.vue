<template>
    <ov-load v-if="loadingRoute" class="mt-3"></ov-load>
    <div v-else @click="overviewClickHandler" class="overflow-x-hidden pt-1 px-3 d-flex flex-column justify-content-start align-items-center">
        <section class="cal-interface w-100 d-flex flex-column justify-content-center align-items-center p-2 border border-danger">
            <div class="w-100 d-flex justify-content-center align-items-center p-1 border border-danger">
                <div @click="changeWeek('prev')" class="week-pre h-100 week-nav d-flex flex-column justify-content-start align-items-center me-3">
                    <fa-icon icon="fa-solid fa-backward" size="xl"></fa-icon>
                </div>
                
                <div class="h-100">
                    <h6 class="m-0">13.01.2023 - 19.01.2023</h6>
                </div>

                <div @click="changeWeek('next')" class="week-next h-100 week-nav d-flex flex-column justify-content-start align-items-center ms-3">
                    <fa-icon icon="fa-solid fa-forward" size="xl"></fa-icon>
                </div>
            </div>
            <div  @click="changeWeek" class="todaysWeekButton border border-black border-2 d-flex justify-content-center align-items-center mt-1 rounded-2">
                <h6 class="m-0 px-2 py-1">Today´s week</h6>
            </div>
        </section>

        <div class="weekdayHolder d-flex flex-column justify-content-center align-items-center border border-dark">
            <week-day v-for="day in THIS_WEEK"
            :key="day.getDate()"
            :date="day"
            :dayevents="eventsOfDay(day)"
            :trainers="trainerArray"
            :students="studentArray">
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
// FETCHED GROUPS, STUDENTS, TRAINERS AND THIS WEEK´S EVENTS
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
    weekeventArray.value = await getEventsForWeek(THIS_WEEK.value);

    loadingRoute.value = false;
});


function changeWeek(val) {
    switch(val) {
        case "next":
            TODAY.value.setDate(TODAY.value.getDate() + 7);
            break;
        case "prev":
            TODAY.value.setDate(TODAY.value.getDate() - 7);
            break;
        default:
            TODAY.value = new Date();
    }
    THIS_WEEK.value.length = 0;
    fillWeekArray();
}
/** FILTERS THE EVENTS FOR THE PARTICULAR DAY day OUT OF ALL FETCHED EVENTS
 *  AND RETURNS THEM AS ARRAY
 *  @param {number} day             => DAY VALUE OF DAY IN QUESTION
 *  @returns {Array} dayevents      => ARRAY WITH ALL EVENTS FOR DAY 'day' */
function eventsOfDay(day) {
    let ndate = day.getDate();
    let nmonth = day.getMonth() + 1;
    let nyear = day.getFullYear();
    if(nmonth === 12) nmonth = 0;
    ndate = ndate.toString();
    nmonth = nmonth.toString();
    nyear = nyear.toString();
    if(ndate.length === 1) ndate = "0" + ndate;
    if(nmonth.length === 1) nmonth = "0" + nmonth;
    let dayevents = weekeventArray.value.filter(curr => curr.day === ndate && curr.month === nmonth && curr.year === nyear);
    return dayevents;
}
/** FETCHES EVERY EVENT FROM DATABSE THAT HAPPENS THIS WEEK
 *  @param {Array} w            => THIS_WEEK */
async function getEventsForWeek(w) {
    let daydata = [];
    let req =
    {
        task: "fetch_events_for_week",
    };
    for(let d of w) {
        daydata.push([d.getDate(), d.getMonth() + 1, d.getFullYear()]);
    }
    req.daydata = daydata;
    let weekeventdata = await store.dispatch("events/post", req);
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
.todaysWeekButton {
    background-image: linear-gradient(to top, #ddd -20%, var(--role-badge), var(--role-badge), #ddd 110%);
    box-shadow: 0 0 5px 1px #333;
    cursor: pointer;
}
.weekdayHolder {
    width: 100%;
}
</style>
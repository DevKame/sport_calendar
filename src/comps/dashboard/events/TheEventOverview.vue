<template>
    <div @click="overviewClickHandler" class="eventOverview overflow-x-hidden ps-2 d-flex flex-column justify-content-start align-items-center border border-danger">

            <itf-card :dashboard-card="true">
                <template #header>
                    <h5 class="m-0">EVENTS</h5>
                </template>

                <template #body>
                    <div class="w-100 h-100 d-flex justify-content-around align-items-center py-2 bg-prim">
                        <transition-group tag="div" name="overview-buttons" class="h-100 w-100 d-flex justify-content-around align-items-center">
                            <router-link :to="{name: 'New-Event'}" key="randomKey" class="px-1 btn-positive border border-black rounded-2 ">
                                New Event
                            </router-link>
                            <a  v-if="oldEventsExistent" @click.prevent="deleteAllOlds" class="px-1 btnDeleteOlds btn-role-badge border border-black rounded-2 ">
                                Delete old events
                            </a>
                        </transition-group>
                    </div>
                </template>
            </itf-card>

            <ov-load v-if="loadingContent" class="mt-3"></ov-load>
            <transition name="no-content">
                <h6 class="noContentHeadline text-center mt-3" v-if="noEventsAvailable">There are no events existent. Click "New Event" to create one</h6>
            </transition>
            <div v-if="!noEventsAvailable" class="listHolder w-100">
                <transition-group tag="ul" name="content-list" class="eventList p-0" mode="out-in">
                    <event-item
                    v-for="(event, idx) in eventArray"
                    :key="event.id"
                    :event="event"
                    :trainer="getTrainerNameFromEventProperties(event.trainer)"
                    :groups="filteredGroups(event.id)"
                    @delete-item="deleteEvent(idx, event.id)"
                    @edit-item="editEvent(event, filteredGroups(event.id))"></event-item>
                </transition-group>
            </div>
            <teleport to="body">
                <transition name="info-box">
                    <info-box @close-infobox="accessInfoActive = false" v-if="accessInfoActive"></info-box>
                </transition>
            </teleport>
    </div>
</template>

<script setup>
import { defineEmits, onMounted, ref, computed } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

import EventItem from "./EventItem.vue";

const router = useRouter();
const store = useStore();

// HANDLING CLICKS FOR DASHBOARD TO KNOW WHEN TO COLLAPSE NAV
let emits = defineEmits([
    "empty-click",
]);
function overviewClickHandler() {
    emits("empty-click");
}

// VALUES REPRESENT IF NO STUDENT IS EXISTENT AND
// IF THE CONTENT IS CURRENTLY LOADING
const noEventsAvailable = ref(false);
const loadingContent = ref(false);

// MANAGES THE USER ROLE AND THE PRIVILEGES
const userRole = computed(() => {
    return store.getters["auth/userRole"];
});
const accessInfoActive = ref(false);

// FETCHED GROUPS AND STUDENTS ARE SAVED WITHIN HERE
const groupArray = ref([]);
const studentArray = ref([]);
const trainerArray = ref([]);
const eventArray = ref([]);

// INITIAL FETCHING OF GROUPS, TRAINERS, TRAININGS AND EVENTS TO BE ABLE TO DISPLAY THEM
onMounted(async () => {
    loadingContent.value = true;


    const groupdata = await store.dispatch("groups/getAllGroups");
    groupArray.value = [...groupdata.groups];
    
    const studentreq = {task:"get-name-and-id"};
    const studentdata = await store.dispatch("students/post", studentreq);
    studentArray.value = [...studentdata.students];
    
    const trainerreq = {task:"get-name-and-id"};
    const trainerdata = await store.dispatch("trainers/post", trainerreq);
    trainerArray.value = [...trainerdata.trainers];
    
    const eventdata = await store.dispatch("events/getAllEvents");
    if(eventdata.events.length === 0)
    {
        noEventsAvailable.value = true;
    }
    else {
        eventArray.value = eventdata.events.sort((a, b) => {
            const dateA = new Date(+a.year, +a.month - 1, +a.day,  +a.hour + 1, +a.minute, 0, 0);
            const dateB = new Date(+b.year, +b.month - 1, +b.day,  +b.hour + 1, +b.minute, 0, 0);

            return dateA - dateB;
        });
        toggleDeleteOldsButton();
    }
    loadingContent.value = false;
});
// CALLBACK FOR CLICKING ON "Delete old events" - BUTTON
async function deleteAllOlds() {
    const olds = eventArray.value.filter(curr => curr.old === 1);
    for(let e of olds)
    {
        try {
            const req = {task: "delete-event", id: e.id};
            await store.dispatch("events/post", req);
            let idx = eventArray.value.indexOf(olds.find(curr => curr.id === req.id));
            eventArray.value.splice(idx, 1);
            const newOlds = eventArray.value.filter(curr => curr.old === 1);
            if(newOlds.length === 0)
            {
                oldEventsExistent.value = false;
            }
        }
        catch {
            router.replace({name: "Error"});
        }
    }
}
/** DELIVERS A DIFFERENT "trainer" PROP TO <EventItem> DEPENDING ON VALUE OF "t"
 * @param {String} t            => "no-trainer" | stringified number
 * @returns {String} result     => "no-trainer" | "You!" | trainer.firstname */
function getTrainerNameFromEventProperties(t) {
    let result = "no-trainer";
    if(t !== "no-trainer")
    {
        let trainername = trainerArray.value.find(curr => curr.id === +t);
        result = trainername.firstname;
        if(+t === store.getters["auth/userID"])
        {
            result = "You!";
        }
    }
    return result;
}
// DETERMINES IF BUTTON FOR DELETING OLD EVENTS SHOULD BE DISPLAYED
const oldEventsExistent = ref(false);
function toggleDeleteOldsButton() {
    oldEventsExistent.value = eventArray.value.find(curr => curr.old === 1) !== undefined ?
    true : false;
}
/** APPLIES THE ACTUAL GROUP NAMES OF ALL GROUPS THE
 *  EVENT HAS TO HIS LISTITEM
 * @param {number} id               => ID OF THE EVENT
 * @returns {Array} namedGroups     => ACTUAL GROUPNAMES THAT FIT TO THE EVENT */
function filteredGroups(id) {
    let student = eventArray.value.find(curr => curr.id === id);
    const studentGroups = JSON.parse(student.groups);
    const namedGroups = [];
    for(let group of groupArray.value)
    {
        if(studentGroups.includes(group.id))
        {
            namedGroups.push(group.name);
        }
    }
    return namedGroups;
}

/** PREPARES STATE WITH NAME AND ID OF TO-BE-EDITED EVENT AND SWITCHES
 *  TO Edit-Event ROUTE TO ENABLE THE ACTUAL EDITING
 * @param {object} event    => THE EVENT ITSELF 
 * @param {Array} groups    => EVENTS GROUPS AS ARRAY */
function editEvent(event, groups) {
    if(userRole.value !== "ADMIN" && userRole.value !== "SENIOR-TRAINER") {
        accessInfoActive.value = true;
    }
    else {
        if(event.old === 0)
        {
            store.commit("events/prepareEventForEdit", {event: event, groups: groups});
            router.push({name: "Edit-Event"});
        }
    }
}

/** DELETES THE CHOSEN EVENT USING HIS ID AND REMOVES THE CORRESPONDING DOM
 * @param {number} index    => ON WHAT IDX IS THE LIST ELEMENT
 * @param {number} id       => ID OF THE EVENT */
async function deleteEvent(index, id) {
    if(userRole.value !== "ADMIN") {
        accessInfoActive.value = true;
    }
    else {
        const req =
        {
            task: "delete-event",
            id: id,
        };
        const deletedata = await store.dispatch("events/post", req);
        if(deletedata.success) {
            eventArray.value.splice(index, 1);
            if(eventArray.value.length === 0)
            {
                noEventsAvailable.value = true;
            }
        }
        else {
            router.replace({name:"Error"});
        }
    }
}
</script>

<style scoped>
.eventList {
    list-style-type: none;
}
.noContentHeadline {
    font-family: "Raleway Reg 400";
}
.content-list-move {
    transition: transform .2s ease;
}
.content-list-enter-from,
.content-list-leave-to {
    opacity: 0;
    transform: translate(100%, 0);
}
.content-list-enter-active {
    transition: all .5s ease;
}
.content-list-leave-active {
    transition: all .5s ease;
    position: absolute;
}
.content-list-enter-to,
.content-list-leave-from {
    opacity: 1;
    transform: translate(0, 0);
}
.no-content-enter-from,
.no-content-leave-to {
    opacity: 0;
    transform: translate(-50%, 0);
}
.no-content-enter-active,
.no-content-leave-active {
    transition: all .3s ease;
}
.no-content-enter-to,
.no-content-leave-from {
    opacity: 1;
    transform: translate(0, 0);
}
.overview-buttons-leave-from,
.overview-buttons-enter-to {
    transform: translate(0, 0);
    opacity: 1;
}
.overview-buttons-enter-active {
    transition: all .3s ease;
}
.overview-buttons-leave-active {
    transition: all .3s ease;
    position: absolute;
}
.overview-buttons-move {
    transition: all .3s ease;
}
.overview-buttons-leave-to,
.overview-buttons-enter-from {
    transform: translate(100%, 0);
    opacity: 0;
}
</style>
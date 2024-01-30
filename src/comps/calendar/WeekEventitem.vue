<template>
    <div :class="usableEvent" class="weekeventitem w-100 bg-trainer-badge rounded-2 overflow-hidden p-1 my-2 d-flex flex-column justify-content-start align-items-start border border-black">
                
        <div class="w-100 d-flex flex-xxl-column justify-content-between justify-content-xxl-start align-items-center align-items-xxl-start">
            <span class="fw-bold">{{ name }} <span v-if="event.old === 1" class="badge text-black bg-delete border border-black">old</span></span>
            <span :class="{is_free: freeSpaces}" class="totalIndicator fw-bold">{{ booked }} / {{ max }}</span>
        </div>

        <div class="w-100 mt-2 d-flex justify-content-between align-items-center">
            <p class="m-0">{{ hour }}:{{ minute }}</p>
            <p class="m-0">Trainer: <span @click="assignTrainer(event.id, event.trainer)" :class="noTrainer" class="badge text-black bg-role-badge border border-dark">{{ trainer }}</span></p>
        </div>

        <div class="w-100 mt-2 d-flex flex-xxl-column justify-content-between justify-content-xxl-start align-items-center align-items-xxl-start">
            <button
            v-if="userRole === 'STUDENT'"
            @click="toggleParticipation(event.id)"
            :class="{canSign: signable}"
            class="partakeButton btn-positive border border-black rounded-2">{{ signingtext }}</button>

            <div class="alertHolder overflow-hidden ms-2 ms-xxl-0 mt-xxl-1 d-flex justify-content-center align-items-center">
                <transition name="errors" mode="out-in">
                    <error-alert v-if="connectionError" @close-alert="connectionError = false">
                        <p class="m-0 fw-bold">Some connection problems occured. Try later</p>
                    </error-alert>

                    <error-alert v-else-if="lengthError" @close-alert="lengthError = false">
                        <p class="m-0 fw-bold">Event is already full</p>
                    </error-alert>
                    
                    <error-alert v-else-if="groupError" @close-alert="groupError = false">
                        <p class="m-0 fw-bold">This event is for particular groups only</p>
                    </error-alert>

                    <success-alert v-else-if="signupSuccess" @close-alert="signupSuccess = false">
                        <p class="m-0 fw-bold">Successfully signed in / out !!</p>
                    </success-alert>
                </transition>
            </div>

        </div>

        <p v-if="infoAvailable" class="fst-italic">{{ info }}</p>
        <h6 v-if="atLeastOneStudent" @click="toggleStudentVisibility" class="fw-bold border border-black rounded-2 w-100 p-1 mt-2 mb-1">Partaking students:</h6>
        <div v-if="atLeastOneStudent && showStudents"  class="w-100 d-flex flex-column justify-content-start align-items-start">
            <p class="m-0 my-1" v-for="st in getPartakingStudents(event.students)" :key="st.id">{{ st.firstname }}</p>
        </div>

    </div>
</template>

<script setup>
import { defineProps, defineEmits, computed, ref } from 'vue';
import { useStore } from 'vuex';

let emits = defineEmits([
    "signin-success",
    "trainer-assigned",
]);
const store = useStore();

let props = defineProps([
    "event",
    "trainers",
    "students",
]);

const usableEvent = computed(() => {
    return props.event.old === 1 ?
    "too-old-to-click" : "";
});
// INDICATES IF USER IS ALREADY PARTAKING OR NOT
const signable = computed(() => {
    return JSON.parse(props.event.students).find(curr => curr === store.getters["auth/userID"]) === undefined ?
    true : false;
})
//DEPENDING ON IF USER PARTAKES OR NOT, ASSIGNS THE PROPER BUTTON COLOR AND TEXT
const signingtext = computed(() => {
    return JSON.parse(props.event.students).find(curr => curr === store.getters["auth/userID"]) !== undefined ?
    "Sign out" : "Sign In";
});
/** RETURNS NAMES OF STUDENTS THAT PARTAKE IN THE PARTICULAR TRAINING
 * @param {JSON String} eventStudents   => ID´s OF PARTAKING STUDENTS
 * @return {Array} parseStudents        => ID, FIRST AND LASTNAME OF STUDENTS */
function getPartakingStudents(eventStudents) {
    let allStudents = props.students;
    let partakeStudents = JSON.parse(eventStudents);
    let parseStudents = [];
    for(let id of partakeStudents) {
        let student = allStudents.find(curr => curr.id === id);
        if(student) {
            parseStudents.push(student);
        }
    }
    return parseStudents;
}
// HELPER VARIABLES FOR ERROR DISPLAYING
const connectionError = ref(false);
const lengthError = ref(false);
const groupError = ref(false);
const signupSuccess = ref(false);
// RESETS ALL ERRORS
function resetErrors() {
    connectionError.value = false;
    lengthError.value = false;
    groupError.value = false;
    signupSuccess.value = false;
}
/** ASSIGNS A TRAINER ID TO AN EVENT
 * @param {number} eid  => EVENT ID
 * @param {number} tid  => TRAINER ID */
async function assignTrainer(eid, tid) {
    if(store.getters.getLoggedUserRole !== "STUDENT" && tid === "no-trainer")
    {
        const req = {
            task: "assign-trainer",
            eid: eid,
            tid: (store.getters["auth/userID"]).toString(),
        };
        let assigndata = await store.dispatch("events/post", req);
        if(!assigndata.success) {
            connectionError.value = true;
        } else {
            emits("trainer-assigned", {eid: eid, tid: req.tid});
        }
    }
}
/** HANDLES THE UPDATING OF DATA WHEN USER SINGS IN/OUT
 *  AND UPDATES THE CORRESPONDING FRONTEND
 * @param {number} eid                      => EVENT ID */
async function toggleParticipation(eid) {
    resetErrors();
    let operation = "";
    // PREPARES REQUEST OBJECT DEPENDING IF ITS A SIGN IN OR OUT
    const req =
    {
        eid: eid,
        sid: store.getters["auth/userID"],
    };
    if(signable.value)
    {
        req.task = "signin-student";
        operation = "in";
    } else {
        req.task = "signout-student";
        operation = "out";
    }
    // MAKE SURE USER GROUPS MATCH EVENT GROUPS
    const eventgroups = JSON.parse(props.event.groups);
    const usergroups = JSON.parse(store.getters.getLoggedUserGroups);
    let groupsMatch = doesGroupsMatch(eventgroups, usergroups);
    let spaceMatch = props.event.booked < props.event.max;
    if(!groupsMatch) {
        groupError.value = true;
    }
    // MAKE SURE THERE IS ENOUGH SPACE WITHIN THE EVENT
    else if(!spaceMatch && operation === "in") {
        lengthError.value = true;
    }
    // INVOKES THE ACTUAL REQUESTS TO BACKEND
    else {
        let signingdata = await store.dispatch("events/post", req);
        if(!signingdata.success)
        {
            switch(signingdata.reason) {
                case "connection-problems":
                    connectionError.value = true;
                    break;
                case "too-long":
                    lengthError.value = true;
                    break;
            }
        } else {
            // EMITS CHANGE OF PROVIDED DATA
            emits("signin-success", {eid: req.eid, sid: req.sid, operation: operation});
            signupSuccess.value = true;
        }
    }
}
/** CHECKS IF THE STUDENT GROUPS FULLY MATCH WITH THE EVENT GROUPS
 * @param {number[]} e  => GROUPS OF THE EVENT
 * @param {number[]} s  => GROUPS OF THE STUDENT
 * @return {Bool} */
function doesGroupsMatch(e, s) {
    let wrongs = 0;
    if(e.length > 0)
    {
        for(let g of e) {
            if(s.indexOf(g) === -1) wrongs++;
        }
    }
    return wrongs === 0;
}

// DETERMINES IF PARTAKING STUDENTS ARE SHOWN OR NOT
const showStudents = ref(false);
function toggleStudentVisibility() {
    showStudents.value = !showStudents.value;
}
// RETURNS THE RELEVANT DATA TO DISPLAY OUT OF THE PROVIDED EVENT PROP
const name = computed(() => {
    return props.event.name;
});
const booked = computed(() => {
    return props.event.booked;
});
const freeSpaces = computed(() => {
    return props.event.booked < props.event.max;
});
const max = computed(() => {
    return props.event.max;
});
const hour = computed(() => {
    return props.event.hour;
});
const minute = computed(() => {
    return props.event.minute;
});
const trainer = computed(() => {
    let result = "no trainer";
    if(props.event.trainer !== "no-trainer")
    {
        const trainer = props.trainers.find(curr => curr.id === +props.event.trainer);
        result = trainer.firstname;
    }
    return result;
});
const noTrainer = computed(() => {
    let result = "";
    if(props.event.trainer === "no-trainer")
    {
        result = "clickable_trainer";
    }
    return result;
});
const infoAvailable = computed(() => {
    return props.event.info.trim() !== "";
})
const info = computed(() => {
    return props.event.info;
});
/** INDICATES IF AT LEAST ONE STUDENT IS PARTAKING
 *  @return {Bool} */
const atLeastOneStudent = computed(() => {
    return props.event.booked > 0;
});

/** RETURNS USER ROLE
 *  @return {String} => STUDENT, TRAINER, SENIO-TRAINER OR ADMIN */
const userRole = computed(() => {
    return store.getters.getLoggedUserRole;
});


</script>

<style scoped>
.too-old-to-click {
    opacity: .55;
    pointer-events: none;
}
.clickable_trainer {
    cursor: pointer;
}
.alertHolder {
    flex: 1;
}
.partakeButton.canSign {
    background-image: linear-gradient(to top, #ddd -20%, var(--create), var(--create), #ddd 110%);
}
.partakeButton {
    background-image: linear-gradient(to top, #ddd -20%, var(--delete), var(--delete), #ddd 110%);
    box-shadow: 0 0 3px 1px #333;
}
::selection {
    all: unset;
}
.totalIndicator {
    color: firebrick;
}
.totalIndicator.is_free {
    color: var(--create);
}
.weekeventitem {
    box-shadow: 0 0 5px 1px #333 inset;
    font-family: "Raleway Reg 400";
}
.errors-enter-from {
    transform: translate(-100%, 0);
}
.errors-enter-to {
    transform: translate(0, 0);
}
.errors-leave-from {
    transform: translate(0, 0);
}
.errors-leave-to {
    transform: translate(100%, 0);
}
.errors-leave-active,
.errors-enter-active {
    transition: transform.3s ease;
}
@media screen and (min-width: 1400px) {
    .partakeButton {
        font-size: .8vw;
    }
    .weekeventitem {
        font-size: 12px;
        font-size: 1vw;
    }
    .calendarOverview {
        max-width: unset;
    }
    .alertHolder {
        flex: unset;
        height: 50px;
        width: 100%;
    }
}
</style>
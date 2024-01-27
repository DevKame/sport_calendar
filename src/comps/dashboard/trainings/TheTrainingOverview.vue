<template>
    <div @click="overviewClickHandler" class="trainingOverview pt-4 ps-2 allOverviews d-flex flex-column justify-content-start align-items-center border border-danger">

            <itf-card :dashboard-card="true">
                <template #header>
                    <h5 class="m-0">TRAININGS</h5>
                </template>

                <template #body>
                    <div class="w-100 h-100 d-flex justify-content-around align-items-center py-2 bg-prim">
                        <router-link :to="{name: 'New-Training'}" class="px-1 btn-positive border border-black rounded-2 ">
                            New Training
                        </router-link>
                    </div>
                </template>
            </itf-card>

            <ov-load v-if="loadingContent" class="mt-3"></ov-load>
            <transition name="no-content">
                <h6 class="noContentHeadline text-center mt-3" v-if="noTrainingsAvailable">There are no trainings existent. Click "New Training to create one"</h6>
            </transition>
            <div v-if="!noTrainingsAvailable" class="listHolder w-100">
                <transition-group tag="ul" name="content-list" class="trainingList p-0" mode="out-in">
                    <trainings-item
                    v-for="(training, idx) in trainingArray"
                    :key="training.id"
                    :name="training.name"
                    :groups="filteredGroups(training.id)"
                    @delete-item="deleteTraining(idx, training.id)"
                    @edit-item="editTraining(training.id, training.name, filteredGroups(training.id))"></trainings-item>
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

import TrainingsItem from "./TrainingsItem.vue";

const router = useRouter();
const store = useStore();

// HANDLING CLICKS FOR DASHBOARD TO KNOW WHEN TO COLLAPSE NAV
let emits = defineEmits([
    "empty-click",
]);
function overviewClickHandler() {
    emits("empty-click");
}

// VALUES REPRESENT IF NO TRAINING IS EXISTENT AND
// IF THE CONTENT IS CURRENTLY LOADING
const noTrainingsAvailable = ref(false);
const loadingContent = ref(false);

// MANAGES THE USER ROLE AND THE PRIVILEGES
const userRole = computed(() => {
    return store.getters["auth/userRole"];
});
const accessInfoActive = ref(false);

// ALL FETCHED TRAININGS AND GROUPS ARE SAVED HERE
const trainingArray = ref([]);
const groupArray = ref([]);

// INITIAL FETCHING OF TRAININGS AND GROUPS TO BE ABLE TO DISPLAY THEM
onMounted(async () => {
    loadingContent.value = true;
    // FETCH ALL GROUPS
    const groupdata = await store.dispatch("groups/getAllGroups");
    loadingContent.value = false;
    groupArray.value = [...groupdata.groups];

    // FETCH ALL TRAININGS:
    const trainerdata = await store.dispatch("trainings/getAllTrainings");
    loadingContent.value = false;
    if(trainerdata.trainings.length === 0)
    {
        noTrainingsAvailable.value = true;
    }
    else {
        trainingArray.value = [...trainerdata.trainings];
    }
});

/** APPLIES THE ACTUAL GROUP NAMES OF ALL GROUPS THE
 *  TRAINING HAS TO ITS LIST ITEM
 * @param {number} id           => ID OF THE TRAINING
 * returns {Array} namedGroups  => ACTUAL GROUPNAMES THAT FIT TO THE TRAINING */
 function filteredGroups(id) {
    let training = trainingArray.value.find(curr => curr.id === id);
    const trainingGroups = JSON.parse(training.groups);
    const namedGroups = [];
    for(let group of groupArray.value)
    {
        if(trainingGroups.includes(group.id))
        {
            namedGroups.push(group.name);
        }
    }
    return namedGroups;
}

/** PREPARES STATE WITH DATA OF TO-BE-EDITED TRAINER AND SWITCHES
 *  TO Edit-Trainer ROUTE TO ENABLE THE ACTUAL EDITING
 * @param {number} id   => ID OF THE TO-BE-EDITED Trainer 
 * @param {String} name => NAME OF THE TO-BE-EDITED Trainer */
function editTraining(id, name, groups) {
    if((userRole.value !== "ADMIN" && userRole.value !== "SENIOR-TRAINER") || id === store.getters["auth/userID"]) {
        accessInfoActive.value = true;
    }
    else {
        store.commit("trainings/prepareTrainingForEdit", {id: id, name: name, groups: groups});
        router.push({name: "Edit-Training"});
    }
}
/** DELETES THE CHOSEN TRAINER USING HIS ID AND REMOVES THE CORRESPONDING DOM
 * @param {number} index    => ON WHAT IDX IS THE LIST ELEMENT
 * @param {number} id       => ID OF THE TRAINER */
async function deleteTraining(index, id) {
    if(userRole.value !== "ADMIN" && userRole.value !== "SENIOR-TRAINER") {
        accessInfoActive.value = true;
    }
    else
    {
        const deletereq =
        {
            task: "delete-training",
            id: id,
        };
        const deletedata = await store.dispatch("trainings/post", deletereq);
        if(deletedata.success) {
            trainingArray.value.splice(index, 1);
            if(trainingArray.value.length === 0)
            {
                noTrainingsAvailable.value = true;
            }
        }
        else {
            router.replace({name:"Error"});
        }
    }
}
</script>

<style scoped>
.trainingList {
    list-style-type: none;
}
.noContentHeadline {
    font-family: "Raleway Reg 400";
}
.content-list-move {
    transition: transform .8s ease;
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
</style>
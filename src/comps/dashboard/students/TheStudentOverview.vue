<template>
    <div @click="overviewClickHandler" class="studentOverview overflow-x-hidden ps-2 allOverviews d-flex flex-column justify-content-start align-items-center border border-danger">

            <itf-card :dashboard-card="true">
                <template #header>
                    <h5 class="m-0">STUDENTS</h5>
                </template>

                <template #body>
                    <div class="w-100 h-100 d-flex justify-content-around align-items-center py-2 bg-prim">
                        <router-link :to="{name: 'New-Student'}" class="px-1 btn-positive border border-black rounded-2 ">
                            New Student
                        </router-link>
                    </div>
                </template>
            </itf-card>

            <ov-load v-if="loadingContent" class="mt-3"></ov-load>
            <transition name="no-content">
                <h6 class="noContentHeadline text-center mt-3" v-if="noStudentsAvailable">There are no students existent. Click "New Student to create one"</h6>
            </transition>
            <div v-if="!noStudentsAvailable" class="listHolder w-100">
                <transition-group tag="ul" name="content-list" class="studentList p-0" mode="out-in">
                    <student-item
                    v-for="(student, idx) in studentArray"
                    :key="student.id"
                    :firstname="student.firstname"
                    :lastname="student.lastname"
                    :email="student.email"
                    :groups="filteredGroups(student.id)"
                    @delete-item="deleteStudent(idx, student.id)"
                    @edit-item="editStudent(student.id, student.email, student.firstname, student.lastname, filteredGroups(student.id))"></student-item>
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

import StudentItem from "./StudentItem.vue";

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
const noStudentsAvailable = ref(false);
const loadingContent = ref(false);

// MANAGES THE USER ROLE AND THE PRIVILEGES
const userRole = computed(() => {
    return store.getters["auth/userRole"];
});
const accessInfoActive = ref(false);

// FETCHED GROUPS AND STUDENTS ARE SAVED WITHIN HERE
const groupArray = ref([]);
const studentArray = ref([]);

// INITIAL FETCHING OF GROUPS AND STUDENTS TO BE ABLE TO DISPLAY THEM
onMounted(async () => {
    loadingContent.value = true;


    const groupdata = await store.dispatch("groups/getAllGroups");
    loadingContent.value = false;
    groupArray.value = [...groupdata.groups];


    const studentdata = await store.dispatch("students/getAllStudents");
    loadingContent.value = false;
    if(studentdata.students.length === 0)
    {
        noStudentsAvailable.value = true;
    }
    else {
        studentArray.value = [...studentdata.students];
    }
});
/** APPLIES THE ACTUAL GROUP NAMES OF ALL GROUPS THE
 *  STUDENT HAS TO HIS LISTITEM
 * @param {number} id           => ID OF THE STUDENT
 * returns {Array} namedGroups  => ACTUAL GROUPNAMES THAT FIT TO THE STUDENT */
function filteredGroups(id) {
    let student = studentArray.value.find(curr => curr.id === id);
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

/** PREPARES STATE WITH NAME AND ID OF TO-BE-EDITED STUDENT AND SWITCHES
 *  TO Edit-Student ROUTE TO ENABLE THE ACTUAL EDITING
 * @param {number} id   => ID OF THE TO-BE-EDITED STUDENT 
 * @param {String} name => NAME OF THE TO-BE-EDITED STUDENT */
function editStudent(id, email, fn, ln, groups) {
    if(userRole.value !== "ADMIN" && userRole.value !== "SENIOR-TRAINER") {
        accessInfoActive.value = true;
    }
    else {
        store.commit("students/prepareStudentForEdit", {email: email, id: id, firstname: fn, lastname: ln, groups: groups});
        router.push({name: "Edit-Student"});
    }
}

/** DELETES THE CHOSEN STUDENT USING HIS ID AND REMOVES THE CORRESPONDING DOM
 * @param {number} index    => ON WHAT IDX IS THE LIST ELEMENT
 * @param {number} id       => ID OF THE STUDENT */
async function deleteStudent(index, id) {
    if(userRole.value !== "ADMIN") {
        accessInfoActive.value = true;
    }
    else {
        const deletereq =
        {
            task: "delete-student",
            id: id,
        };
        const deletedata = await store.dispatch("students/post", deletereq);
        if(deletedata.success) {
            studentArray.value.splice(index, 1);
            if(studentArray.value.length === 0)
            {
                noStudentsAvailable.value = true;
            }
        }
        else {
            router.replace({name:"Error"});
        }
    }
}
</script>

<style scoped>
.studentList {
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
</style>
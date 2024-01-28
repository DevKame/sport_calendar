<template>
  <the-kamedin @show-stack="showStack"></the-kamedin>
  <router-view></router-view>
  <teleport to="body">
    
    <transition name="stack-bd">
        <div v-if="stackShown" @click.self="hideStack" class="stackBackdrop position-fixed d-flex justify-content-center align-items-start">
            
            <div class="stackInfoWindow d-flex flex-column justify-content-start align-items-center rounded-2 overflow-hidden">

                <div class="stachInfoHeader py-1 w-100 bg-sec px-2 d-flex justify-content-content align-items-center">

                    <h3 class="m-0">Information</h3>
                    <div @click="hideStack" class="stackCloseHolder ms-auto rounded-circle border border-black d-flex justify-content-center align-items-center">
                        <fa-icon icon="fa-solid fa-close"></fa-icon>
                    </div>

                </div>

                <div class="stackInfoBody overflow-y-scroll p-2 bg-prim w-100 d-flex flex-column justify-content-start align-items-start">
                    <h4>Tech Stack</h4>
                    <p>This application was made using</p>
                    <ul>
                        <li>Javascript</li>
                        <li>Vue 3</li>
                        <li>Vue-Router</li>
                        <li>Vuex</li>
                        <li>Bootstrap</li>
                        <li>Font Awesome</li>
                        <li>PHP</li>
                    </ul>
                    <p>Checkout the code on <a href="" @click="prevent">Github</a></p>
                    <p>This is an application for a fictive sports club where trainers are able to create new appointments (events).</p>
                    <p>Every event is visible for students on a calendar. Students are able to sign in to every event and receive feedback if its already full or participation is permitted due to the student´s groups</p>
                    <p>To avoid entering the same event name over and over again, trainers can create custom trainings. Within the event-creation menu those trainings are displayed and with selecting them
                        the name and possible groups are instantly selected.</p>
                    <p>If you want to create trainings where only selected students can participate, you can create pre-defined groups and apply them to those students</p>

                </div>

            </div>

        </div>
    </transition>

  </teleport>
</template>

<script setup>
import { ref } from 'vue';
const stackShown = ref(false);
function showStack() {
    stackShown.value = true;
}
function hideStack() {
    stackShown.value = false;
}



</script>

<style>
.stackCloseHolder {
    width: 30px;
    aspect-ratio: 1;
}
.stachInfoHeader {
    font-family: "Raleway SBold 600";
}
.stackInfoWindow {
    width: clamp(300px, 60%, 550px);
    height: 70%;
    font-family: "Raleway Reg 400";
}
.stackBackdrop {
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0,0,0,.7);
    z-index: 22;
    padding-top: 15%;
}
.stack-bd-enter-from,
.stack-bd-leave-to {
    opacity: 0;
}
.stack-bd-enter-active,
.stack-bd-leave-active {
    transition: all .3s ease;
}
.stack-bd-enter-to,
.stack-bd-leave-from {
    opacity: 1;
}
.info-box-enter-from,
.info-box-leave-to {
    opacity: 0;
    transform: scaleX(1.3);
}
.info-box-enter-active,
.info-box-leave-active {
    transition: all .3s ease;
}
.info-box-enter-to,
.info-box-leave-from {
    opacity: 1;
    transform: scaleX(1);
}
</style>

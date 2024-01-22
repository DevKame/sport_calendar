<template>
    <main class="theLogin pt-4 d-flex flex-column justify-content-start align-items-center">
        <header class="logoHeader mb-5 d-flex flex-column justify-content-center align-items-center">
            <img src="@/assets/img/logos/kaizen-630.png" />
            <p>Placebo Kaizen Martial Arts</p>
            <p>Team Calendar</p>
        </header>

        <itf-card>
            <template #header>
                <div class="d-flex justify-content-start align.items-center">
                    <h5 class="m-0">LOGIN</h5>
                </div>
            </template>

            <template #body>
                <div class="loginBody p-2 w-100 d-flex flex-column justify-content-start align-items-center">
                    <form @submit.prevent="try_login" class=" w-100 d-flex flex-column justify-content-start align-items-center">
                        
                        <div class="errorContainer w-100 display position-relative">
                            <transition name="errors">
                                <error-alert v-if="emailError" @close-alert="emailError = false">
                                    <p class="m-0 fw-bold">This email does not exist</p>
                                </error-alert>
                            </transition>
                        </div>

                        <div class="inputWrapper w-100 d-flex flex-column justify-content-start align-items-start overflow-hidden">
                            <div class="w-100 d-flex justify-content-between align-items-center">
                                <transition name="labels">
                                    <label v-if="email_focus_filled" for="email-login">EMAIL</label>
                                </transition>
                                    <label class="opacity-0 position-relative">EMAIL</label>
                            </div>
                            <div class="position-relative w-100 overflow-hidden">
                                <!-- TODO: change Type to "email" for production -->
                                <input @focus="inputStateAfterFocus" @blur="inputStateAfterBlur" type="mail" id="email-login" name="email-login" class="ps-2 w-100 position-relative" :class="{focus_or_filled: email_focus_filled}" v-model.trim="email_login" required/>
                                <transition name="placeholders">
                                    <label v-if="!email_focus_filled" class="position-absolute placeholders fst-italic pe-none">EMAIL</label>
                                </transition>
                            </div>
                        </div>

                        <div class="errorContainer mt-2 w-100 display position-relative">
                            <transition name="errors">
                                <error-alert v-if="passwordError" @close-alert="passwordError = false">
                                    <p class="m-0 fw-bold">Wrong password</p>
                                </error-alert>
                            </transition>
                        </div>
                        
                        <div class="inputWrapper w-100 d-flex flex-column justify-content-start align-items-start overflow-hidden">
                            <div class="w-100 d-flex justify-content-between align-items-center">
                                <transition name="labels">
                                    <label v-if="password_focus_filled" for="password-login">PASSWORD</label>
                                </transition>
                                    <label class="opacity-0 position-relative">PASSWORD</label>
                            </div>
                            <div class="position-relative w-100 overflow-hidden">
                                <input @focus="inputStateAfterFocus" @blur="inputStateAfterBlur" type="password" id="password-login" name="password-login" class="ps-2 w-100 position-relative" :class="{focus_or_filled: password_focus_filled}" v-model.trim="password_login" required/>
                                <transition name="placeholders">
                                    <label v-if="!password_focus_filled" class="position-absolute placeholders fst-italic pe-none">PASSWORD</label>
                                </transition>
                            </div>
                        </div>

                        <div class="errorContainer mt-5 w-100 display position-relative">
                            <transition name="errors">
                                <error-alert v-if="connectionError" @close-alert="connectionError = false">
                                    <p class="m-0 fw-bold">Some connection problems occured. Try later</p>
                                </error-alert>
                            </transition>
                        </div>

                        <input type="submit" value="LOGIN" class="btn-tert border border-black rounded-2 px-3 ms-auto mt-2" />

                    </form>

                    <button @click="openPrivacy" class="bot-button my-1 mt-3 d-flex justify-content-center align-items-center rounded-pill w-100">PRIVACY</button>
                    <button @click="toggleInfo" class="bot-button my-1 d-flex justify-content-center align-items-center rounded-pill w-100">{{ infoOpen ? "COLLAPSE" : "LOGIN WITH WHAT" }}</button>

                    <div v-if="infoOpen" class="infoWindow w-100 d-flex flex-column justify-content-start align-items-start">
                        <p class="m-0">To maintain its purpose as reference work, this
                            application enables you to login as a
                            student with the possibility to see how i
                            manage to build webapplications without 
                            editing/deleting too much of its content:
                            </p>
                        <strong>USERNAME:</strong>
                        <p class="m-0">d.sal@mymail.com</p>
                        <strong>PASSWORD:</strong>
                        <p>w_xG7!AUt-14r</p>
                    </div>
                </div>
            </template>
        </itf-card>
        <teleport to="body">
            <transition name="backdrop">
                <div v-if="privacyOpen" @click="closePrivacy" class="backdrop position-fixed d-flex justify-content-center align-items-center">
                        <itf-card>
                            <template #header>
                                <div class="w-100 h-100 border border-danger d-flex justify-content-between align-items-center">
                                    <h5 class="m-0">PRIVACY</h5>
                                    <div @click="closePrivacy" class="closeWrapper p-1 border border-dark rounded-circle d-flex justify-content-center align-items-center">
                                        <fa-icon icon="fa-solid fa-close"></fa-icon>
                                    </div>
                                </div>
                            </template>

                            <template #body>
                                <div class="privacyText p-2 d-flex flex-column justify-content-center w-100 bg-prim">
                                    <p>No data is sent to any third-party. Third party Resources like fonts, icons, etc are used locally.</p>

                                    <p>Using this application requires you logging in. For that purpose,  we use a cookie to ensure that you dont need to login every single time you visit this page.</p>

                                    <p>By using and logging in you accept the storage of the cookie „KDNAPCKIE“ within your browser</p>
                                </div>
                            </template>
                        </itf-card>
                </div>
            </transition>
        </teleport>
    </main>
    <footer class="mt-5 px-2">
        <small>LOGO by: Majo statt Senf, Licence: https://creativecommons.org/licenses/by-sa/4.0/deed.de</small>
    </footer>
</template>


<script setup>
import {ref} from "vue";
import { useRouter } from "vue-router";
import { useStore } from "vuex";
// ROUTE NAVIGATION - USED TO PROGRAMMATICLY CHANGE ROUTES
const router = useRouter();

const store = useStore();

/** LOGIN - CALLBACK FOR SUBMITTING LOGIN FORM
    THERE ARE 3 POSSIBLE VALUES RETURNED FROM BACKEND FOR data.reason:
    -- "email-doesnt-exist"     => EMAIL DOESNT EXIST
    -- "wrong-pw"               => EMAIL EXISTS, BUT THE ENTERED PW IS WRONG
    -- "connection-problems"    => THROUGHOUT THE PROCESS OF ACCESSING DATABASE THERE WAS A MISTAKE */
async function try_login() {
    // console.clear();
    let data =
    await store.dispatch("try_login", {email: email_login.value, password: password_login.value, task: "try-login"});

    if(!data.success) {
        switch(data.reason)
        {
            case "email-doesnt-exist":
                emailError.value = true;
                break;
            case "wrong-pw":
                passwordError.value = true;
                break;
            case "connection-problems":
                passwordError.value = true;
                break;
        }
    }
    else {
        router.replace({name: "Dashboard"});
    }
}

///////////// FOCUS AND BLUR FOR INPUT ELEMENTS
const email_login = ref("");
const email_focus_filled = ref(false);
function inputStateAfterBlur(e) {
    if(e.target.id === "email-login")
    {
        email_login.value === "" ?
        email_focus_filled.value = false : email_focus_filled.value = true;
    }
    else
    {
        password_login.value === "" ?
        password_focus_filled.value = false : password_focus_filled.value = true;
    }
    
}
function inputStateAfterFocus(e) {
    resetErrors();
    if(e.target.id === "email-login") {
        email_focus_filled.value = true;
    }
    else
    {
        password_focus_filled.value = true;
    }
}
const password_login = ref("");
const password_focus_filled = ref(false);
///////////// FOCUS AND BLUR FOR INPUT ELEMENTS

///////////// DISPLAY OF EMAIL/PASSWORD ERRORS
const emailError = ref(false);
const passwordError = ref(false);
const connectionError = ref(false);
function resetErrors() {
emailError.value = false;
passwordError.value = false;
connectionError.value = false;
}
///////////// DISPLAY OF EMAIL/PASSWORD ERRORS

///////////// TOGGLING PRIVACY WINDOW
const privacyOpen = ref(false);
function closePrivacy() {
    privacyOpen.value = false;
}
function openPrivacy() {
    privacyOpen.value = true;
}
///////////// TOGGLING PRIVACY WINDOW


///////////// TOGGLING LOGIN INFORMATION
const infoOpen = ref(false);
function toggleInfo() {
    infoOpen.value = !infoOpen.value;
}
///////////// TOGGLING LOGIN INFORMATION
</script>


<style scoped>
.backdrop-enter-from {
    opacity: 0;
}
.backdrop-enter-active,
.backdrop-leave-active {
    transition: opacity .1s linear;
}
.backdrop-enter-to,
.backdrop-leave-from {
    opacity: 1;
}
.backdrop-leave-to {
    opacity: 0;
}
footer small {
    font-size: 11px;
    color: var(--weak);
}
.infoWindow p {
    font-size: 14px;
}
.closeWrapper {
    width: 30px;
    aspect-ratio: 1;
}
.backdrop {
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    background-color: rgba(0,0,0,.8);
}
.bot-button {
    font-size: 10px;
    background-color: transparent;
    border: 1px solid var(--weak);
    font-family: "Raleway Light 300";
}
.errors-enter-from {
    transform: translate(-100%, 0);
}
.errors-enter-active,
.errors-leave-active {
    transition: transform .3s ease-out;
}
.errors-enter-to,
.errors-leave-from {
    transform: translate(0, 0);
}
.errors-leave-to {
    transform: translate(100%, 0);
}
.errorContainer {
    height: 35px;
    /* font-family: "Raleway SBold 600"; */
}
.labels-enter-from,

.labels-leave-to {
    transform: translate(0, 100%)
}
.labels-enter-active,
.labels-leave-active {
    transition: transform .3s linear;
}
.labels-enter-to,
.labels-leave-from {
    transform: translate(0, 0);
}
.placeholders-enter-from {
    transform: translate(0, -100%);
}
.placeholders-enter-active,
.placeholders-leave-active {
    transition: transform .3s linear;
}
.placeholders-enter-to,
.placeholders-leave-from {
    transform: translate(0, 0);
}
.placeholders-leave-to {
    transform: translate(0, -100%);
}
.inputWrapper small {
    font-size: .7em;
}
.placeholders {
    top: 2px;
    left: 5px;
    color: var(--weak);
}
header.logoHeader p {
    margin: 0;
    width: 100%;
    text-align: center;
}
img {
    width: 100%;
}
header.logoHeader,
.itfCard {
    width: 250px;
}
.theLogin {
    min-height: calc(100vh - 150px);
}
.theLogin,
.privacyText {
    font-family: "Raleway Reg 400";
}
@media screen and (min-width: 576px) {
    header.logoHeader,
    .itfCard {
        width: 350px;
    }
}
</style>
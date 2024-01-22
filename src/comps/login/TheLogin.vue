<template>
    <main class="theLogin pt-4 d-flex flex-column justify-content-start align-items-center">
        <header class="logoHeader mb-5 d-flex flex-column justify-content-center align-items-center border border-danger">
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
                <div class="loginBody p-2 w-100 border border-warning d-flex flex-column justify-content-start align-items-center">
                    <form @submit.prevent="console.log('submitted')" class="border border-danger w-100 d-flex flex-column justify-content-start align-items-center">
                        
                        <div class="errorContainer w-100 display position-relative border border-danger">
                            <transition name="errors">
                                <error-alert v-if="emailError">
                                    <p class="m-0">This email does not exist</p>
                                </error-alert>
                            </transition>
                        </div>

                        <div class="inputWrapper w-100 d-flex flex-column justify-content-start align-items-start overflow-hidden">
                            <div class="w-100 border border-info d-flex justify-content-between align-items-center">
                                <transition name="labels">
                                    <label v-if="email_focus_filled" for="email-login">EMAIL</label>
                                </transition>
                                    <label class="opacity-0 position-relative">EMAIL</label>
                            </div>
                            <div class="position-relative w-100 overflow-hidden">
                                <input @focus="inputStateAfterFocus" @blur="inputStateAfterBlur" type="mail" id="email-login" name="email-login" class="ps-2 w-100 position-relative" :class="{focus_or_filled: email_focus_filled}" v-model.trim="email_login" required/>
                                <transition name="placeholders">
                                    <label v-if="!email_focus_filled" class="position-absolute placeholders fst-italic pe-none">EMAIL</label>
                                </transition>
                            </div>
                        </div>

                        <div class="errorContainer mt-2 w-100 display position-relative border border-danger">
                            <transition name="errors">
                                <error-alert v-if="passwordError">
                                    <p class="m-0">Wrong password</p>
                                </error-alert>
                            </transition>
                        </div>
                        
                        <div class="inputWrapper w-100 d-flex flex-column justify-content-start align-items-start overflow-hidden">
                            <div class="w-100 border border-info d-flex justify-content-between align-items-center">
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
                        <input type="submit" value="LOGIN" class="btn-tert border border-black rounded-2 px-3 ms-auto mt-5" />

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
            <transition name="privacy">
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
                            <div class="p-2 d-flex flex-column justify-content-center w-100 bg-prim">
                                <p>No data is sent to any third-party. Third party Resources like fonts, icons, etc are used locally.</p>

                                <p>Using this application requires you logging in. For that purpose,  we use a cookie to ensure that you dont need to login every single time you visit this page.</p>

                                <p>By using and logging in you accept the storage of a cookie „KDNAPCKIE“</p>
                            </div>
                        </template>
                    </itf-card>
                </div>
            </transition>
        </teleport>
    </main>
</template>


<script setup>
import {ref} from "vue";

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
// function toggleError(which, val) {
//     switch(which) {
//         case "email":
//             emailError.value = val;
//             break;
//         case "password":
//             passwordError.value = val;
//             break;
//     }
// }
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
.infoWindow p {
    font-size: 14px;
}
.privacy-enter-from {
    opacity: 0;
    transform: scale(.7);
}
.privacy-enter-active,
.privacy-leave-active {
    transition: all .3s ease-out;
}
.privacy-enter-to,
.privacy-leave-from {
    opacity: 1;
    transform: scale(1);
}
.privacy-leave-to {
    opacity: 0;
    transform: scale(.7);
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
}
.labels-enter-from {
    transform: translate(0, 100%);
}
.labels-enter-active,
.labels-leave-active {
    transition: transform .3s linear;
}
.labels-enter-to,
.labels-leave-from {
    transform: translate(0, 0);
}
.labels-leave-to {
    transform: translate(0, -100%);
}
.placeholders-enter-from {
    transform: translate(0, 100%);
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
@media screen and (min-width: 576px) {
    header.logoHeader,
    .itfCard {
        width: 350px;
    }
}
</style>
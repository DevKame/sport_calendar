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
                            <error-alert>
                                <p class="m-0">This email does not exist</p>
                            </error-alert>
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

                        
                        <div class="inputWrapper mt-3 w-100 d-flex flex-column justify-content-start align-items-start overflow-hidden">
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
                </div>
            </template>
        </itf-card>
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
</script>


<style scoped>
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
</style>
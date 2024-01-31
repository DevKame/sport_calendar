<template>
    <div class="wrapper p-4 d-flex flex-column justify-content-start align-items-center">
        <h2>It seems that someone got a little faded away...</h2>
        <router-link :to="{name: 'Start'}" class="nav-btns btn-start mt-5 mb-3">Go back to start</router-link>
        <a href="https://www.kamed.in" target="_blank" class="nav-btns btn-port my-3">Or check my portfolio</a>
    </div>
</template>

<style scoped>
.nav-btns {
    color: black;
    border: 2px solid black;
    border-radius: 10px;
    padding: 10px 20px;
    box-shadow: 0 0 5px 2px #333;
}
.btn-start {
    background-image: linear-gradient(to top, white -30%, var(--create), var(--create), white 140%);
}
.btn-port {
    color: #c7c7c7;
    background-image: linear-gradient(to top, white -30%, #192145, #192145, white 140%);
}
</style>
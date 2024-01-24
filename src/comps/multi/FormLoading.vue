<template>
    <div class="loadingContainer d-flex justify-content-between align-items-end">
        <div></div>
        <div></div>
        <div></div>
    </div>
</template>

<style scoped>
.loadingContainer > div:last-child {
    animation: load 2s .2s linear infinite;
}
.loadingContainer > div:nth-child(2) {
    animation: load 2s .1s linear infinite;
}
.loadingContainer > div:first-child {
    animation: load 2s linear infinite;
}
.loadingContainer > div {
    width: 25%;
    height: 2px;
    background-color: var(--sec);
    opacity: .5;
}
.loadingContainer {
    width: clamp(100px, 30%, 120px);
    min-height: 30px;
}
@keyframes load {
    0% {
        height: 2px;
    }
    25% {
        height: 27px;
    }
    39% {
        height: 18px;
    }
    50% {
        height: 7px;
    }
    62% {
        height: 12px;
    }
    74% {
        height: 2px;
    }
    82% {
        height: 24px;
    }
    94% {
        height: 20px;
    }
    100% {
        height: 2px;
    }
}
</style>
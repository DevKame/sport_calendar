<template>
    <div class="w-100 d-flex justify-content-center align-items-center">
        <div class="ballContainer position-relative">
            <div class="ball position-absolute"></div>
            <div class="ball position-absolute"></div>
            <div class="ball position-absolute"></div>
            <div class="ball position-absolute"></div>
            <div class="ball position-absolute"></div>
            <div class="ball position-absolute"></div>
        </div>
    </div>
</template>

<style scoped>
.ball:nth-child(6) {
    background-color: rgb(34, 64, 88);
    animation: rot 1.5s ease .5s infinite;
}
.ball:nth-child(5) {
    background-color: rgb(42, 73, 99);
    animation: rot 1.5s ease .4s infinite;
}
.ball:nth-child(4) {
    background-color: rgb(58, 97, 129);
    animation: rot 1.5s ease .3s infinite;
}
.ball:nth-child(3) {
    background-color: rgb(72, 110, 141);
    animation: rot 1.5s ease .2s infinite;
}
.ball:nth-child(2) {
    background-color: rgb(81, 119, 150);
    animation: rot 1.5s ease .1s infinite;
}
.ball:first-child {
    background-color: rgb(103, 146, 181);
    animation: rot 1.5s ease infinite;
}
.ballContainer > div {
    top: 40%;
    left: 0;
    width: 20%;
    aspect-ratio: 1;
    border-radius: 50%;
    transform-origin: 250%;
} 
.ballContainer {
    width: clamp(100px, 10vw, 200px);
    aspect-ratio: 1;
}
@keyframes rot {
    0% {
        transform: rotate(0);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
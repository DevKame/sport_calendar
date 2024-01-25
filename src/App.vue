<template>
  <the-kamedin></the-kamedin>
  <router-view></router-view>
</template>

<script>

export default {
}
</script>

<style>
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

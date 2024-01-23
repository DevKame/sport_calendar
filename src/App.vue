<template>
  <the-kamedin></the-kamedin>
  <router-view></router-view>
</template>

<script>

export default {
}
</script>

<style>
#dashboard-itf-card {
    width: 95%;
    height: 80px;
    position: fixed;
    top: clamp(130px, 35vw, 140px);
    left: 50%;
    transform: translate(-50%, 0);
}
#dashboard-itf-card h5 {
    font-size: 14px;
}
#dashboard-itf-card button {
    font-size: 12px;
}
.allOverviews {
    padding-top: clamp(100px, 28vw, 110px);
}
</style>

<template>
    <span class="badge">{{ props.date }}</span>
</template>


<script setup>
import { defineProps } from 'vue';
let props = defineProps([
    "date",
]);
</script>
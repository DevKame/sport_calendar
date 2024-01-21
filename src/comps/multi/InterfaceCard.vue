<template>
    <div class="itfCard overflow-hidden border border-danger d-flex flex-column justify-content-start align-items-center rounded-3">
        <header class="p-2 itfHeader bg-sec border border-warning">
            <slot name="header"></slot>
        </header>

        
        <slot name="body"></slot>
    </div>
</template>

<script setup>
</script>

<!-- SLOT HEADER´S HEADLINE IS ALWAYS A <h4> ELEMENT !! -->
<style scoped>
.itfHeader {
    width: 100%;
    box-shadow: 0 2px 10px 1px black;
}
</style>
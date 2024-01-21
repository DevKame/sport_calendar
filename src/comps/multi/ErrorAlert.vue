<template>
    <div class="errorAlert rounded-2 px-1 py-2 d-flex justify-content-between align-items-center bg-delete">
        <slot></slot>
        <div class="d-flex justify-content-center align-items-center rounded-circle">
            <fa-icon icon="fa-solid fa-close" size="xl"></fa-icon>
        </div>
    </div>
</template>



<style scoped>
.errorAlert > div {
    border: 1px solid var(--delete-dark);
    width: 20px;
    aspect-ratio: 1;
}
.errorAlert {
    border: 1px solid var(--delete-dark);
    color: var(--delete-dark);
    font-size: 10px;
}
</style>
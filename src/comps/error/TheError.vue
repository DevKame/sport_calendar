<template>
    <div class="errorWrapper p-4 d-inline-flex flex-column justify-content-start align-items-start">
        <h2>Something went wrong here.</h2>
        <p>Some unexpected problems with the connection to the database occured.</p>
        <p>Just try out later again!</p>
        <router-link :to="{name: 'Start'}" class="backLink">Back
        </router-link>
    </div>
</template>

<style scoped>
.backLink {
    color: black;
    border: 2px solid black;
    border-radius: 10px;
    padding: 5px 20px;
    background-image: linear-gradient(to top, white -20%, var(--create), var(--create), white 110% );
}
.errorWrapper h2 {
    font-family: "Raleway SBold 600", Arial;
}
.errorWrapper {
    font-family: "Raleway Reg 400", Arial;
}
</style>
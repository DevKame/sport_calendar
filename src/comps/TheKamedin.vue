<template>
    <aside class="theKamedin px-2 d-flex justify-content-between align-items-center">
        <p>powered by <a href="https://www.kamed.in" target="_blank" class="fw-bold">Kamedin Webdesign</a></p>
        <div class="techStack d-flex justify-content-end align-items-center">
            <p class="me-2">Tech Stack</p>
            <fa-icon icon="fasolid fa-microchip" size="sm" class="icon"></fa-icon>
        </div>
    </aside>
</template>

<!-- TODO: add click listener for tech stack and its window -->

<style scoped>
.icon {
    color: #996913;
}
.techStack {
    height: 100%;
    width: 40%;
    max-width: 200px;
}
.theKamedin {
    background-color: #152145;
    width: 100%;
    height: clamp(20px, 5vw, 30px);
    font-family: "Ubuntu Med 500";
}
p {
    margin: 0;
    color: #bac1de;
    font-size: 12px;
}
a {
    color: #996913;
    text-decoration: none;
}

</style>
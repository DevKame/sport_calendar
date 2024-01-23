<template>
    <div class="studentOverview allOverviews d-flex flex-column justify-content-start align-items-center border border-danger">
        <itf-card id="dashboard-itf-card">
            <template #header>
                <h5 class="m-0">STUDENTS</h5>
            </template>

            <template #body>
                <div class="w-100 h-100 d-flex justify-content-around align-items-center py-2 bg-prim">
                    <button class="btn-positive border border-black rounded-2 ">New Student</button>
                </div>
            </template>
        </itf-card>
    </div>
</template>
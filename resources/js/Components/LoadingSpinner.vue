<template>
    <div class="absolute w-full h-screen z-[800]">
        <div class="fixed w-full h-full bg-black opacity-30"></div>
        <div class="flex justify-center items-center h-screen z-[999]">
            <div class="loader-container">
                <div class="loader-circle"></div>
                <div class="loader-text mt-4 text-white text-lg font-semibold">Loading...</div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Loading',
};
</script>

<style scoped>
.loader-container {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.loader-circle {
    width: 60px;
    height: 60px;
    border: 6px solid rgb(255, 254, 254);
    border-top: 6px solid #3490dc;
    /* Tailwind's blue color */
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

.loader-text{
    z-index: 999;
}
@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}
</style>

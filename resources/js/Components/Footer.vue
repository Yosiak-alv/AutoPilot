<script setup>
const currentYear = new Date().getFullYear();

</script>

<template>  
    <footer class="flex-shrink-0 px-6 py-4 bg-white dark:bg-gray-800">
        <p class="text-center text-sm text-gray-600 dark:text-gray-200 ">
            <span>Made By</span>

            <a
                href="https://www.josiasalvarenga.com/"
                target="_blank"
                class="text-redF hover:underline hover:text-red-500"
            >
                Josias Alvarenga
            </a>
            <span>, {{currentYear}} All Rights Reserved.</span>
        </p>
    </footer>
</template>
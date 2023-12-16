<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import Toast from '@/Components/Toast.vue';
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div>
            <Link href="/" class="text-[#D72428] font-extrabold">
                <div class="flex flex-wrap items-center justify-between">
                    <ApplicationLogo class="w-48 first-letter:fill-current text-gray-500 mx-auto" />
                    <div class="mx-auto">
                        <h2 class="text-3xl uppercase">SolidRecordRH </h2>
                        <h5 class="text-2xl">Fe y Alegría</h5>
                    </div>
                </div>
            </Link>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg"
        >
            <slot />
            <Toast/>
        </div>
        
    </div>
</template>

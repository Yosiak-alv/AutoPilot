<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

/* const classes = computed(() =>
    props.active
        ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-red-400 dark:border-red-600 text-start text-base font-medium text-white-700 dark:text-white-300 bg-red-50 dark:bg-red-900/50 focus:outline-none focus:text-white-800 dark:focus:text-white-200 focus:bg-red-100 dark:focus:bg-red-900 focus:border-red-700 dark:focus:border-red-300 transition duration-150 ease-in-out'
        : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-red-50 dark:hover:bg-red-700 hover:border-red-300 dark:hover:border-red-600 focus:outline-none focus:text-gray-800 dark:focus:text-gray-200 focus:bg-red-50 dark:focus:bg-red-700 focus:border-red-300 dark:focus:border-red-600 transition duration-150 ease-in-out'
); */
const classes = computed(() =>
    props.active
        ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-red-200 dark:border-red-200 text-start text-base font-medium text-white-700 dark:text-gray-200 bg-red-50 dark:bg-red-700 focus:outline-none focus:text-white-800 dark:focus:text-gray-200 focus:bg-red-100 dark:focus:bg-red-500 focus:border-red-700 dark:focus:border-red-300 transition duration-150 ease-in-out'
        : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 dark:text-gray-200 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-red-50 dark:hover:bg-red-700 hover:border-red-300 dark:hover:border-red-600 focus:outline-none focus:text-gray-800 dark:focus:text-gray-200 focus:bg-red-50 dark:focus:bg-red-700 focus:border-red-300 dark:focus:border-red-600 transition duration-150 ease-in-out'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>

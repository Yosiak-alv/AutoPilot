<script setup>
import { useDark ,useToggle} from '@vueuse/core';

const isDark = useDark();
const toggle = useToggle(isDark);

</script>

<template>
        
    <div 
        class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300"
    >
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" value="" :checked="isDark" @click="toggle()" class="sr-only peer">
            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-red-300 dark:peer-focus:ring-red-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-red-600"></div>
            <span class="ml-2">{{isDark == true ? 'Dark Mode': 'Light Mode'}}</span>
        </label>
    </div>
</template>
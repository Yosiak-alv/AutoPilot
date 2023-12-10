<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import CardSection from '@/Components/CardSection.vue';
defineProps(['branches','cars','workshops','brands']);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="flex flex-wrap justify-between items-center p-2 space-y-4">
                <CardSection class="mt-4 flex items-center w-full max-w-xs p-4  text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
                    <div class="flex justify-between space-x-4">
                        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8">
                            <svg fill="#E60012" class="w-12 h-12" viewBox="0 0 15 15" xmlns="http://www.w3.org/2000/svg" id="car">
                                <path d="M13.84,6.852,12.6,5.7,11.5,3.5a1.05,1.05,0,0,0-.9-.5H4.4a1.05,1.05,0,0,0-.9.5L2.4,5.7,1.16,6.852A.5.5,0,0,0,1,7.219V11.5a.5.5,0,0,0,.5.5h2c.2,0,.5-.2.5-.4V11h7v.5c0,.2.2.5.4.5h2.1a.5.5,0,0,0,.5-.5V7.219A.5.5,0,0,0,13.84,6.852ZM4.5,4h6l1,2h-8ZM5,8.6c0,.2-.3.4-.5.4H2.4C2.2,9,2,8.7,2,8.5V7.4c.1-.3.3-.5.6-.4l2,.4c.2,0,.4.3.4.5Zm8-.1c0,.2-.2.5-.4.5H10.5c-.2,0-.5-.2-.5-.4V7.9c0-.2.2-.5.4-.5l2-.4c.3-.1.5.1.6.4Z"/>
                            </svg>
                        </div>
                        <div class="mt-1 text-gray-900 dark:text-gray-100">Autos # {{cars}}</div>
                    </div>
                </CardSection>
                <CardSection class="flex items-center w-full max-w-xs p-4  text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
                    <div class="flex justify-between space-x-4">
                        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8">
                            <svg class="w-12 h-12" viewBox="0 0 76 76" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" baseProfile="full" enable-background="new 0 0 76.00 76.00" xml:space="preserve">
                                <path fill="#E60012" fill-opacity="1" stroke-width="0.2" stroke-linejoin="round" d="M 20,51L 28,48L 28,27L 43,23L 43,54L 20.9999,51.2501L 43,59L 56,56L 56,20L 43,17L 20,25L 20,51 Z "/>
                            </svg>
                        </div>
                        <div class="mt-1 text-gray-900 dark:text-gray-100">Centros # {{branches}}</div>
                    </div>
                </CardSection>
                <CardSection class="flex items-center w-full max-w-xs p-4  text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
                    <div class="flex justify-between space-x-4">
                        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8">
                            <svg class="w-12 h-12"  viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16 2L20.7833 10.058L16 18.1159L11.2167 10.1159L16 2ZM16 18.1159H25.3333L30 26H20.6667L16 18.1159ZM16 18.1159H6.66667L2 26H11.3333L16 18.1159Z" fill="#E60012"/>
                            </svg>
                        </div>
                        <div class="mt-1 text-gray-900 dark:text-gray-100">Marcas # {{brands}}</div>
                    </div>
                </CardSection>
                <CardSection class="flex items-center w-full max-w-xs p-4  text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
                    <div class="flex justify-between space-x-4">
                        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8">
                            <svg fill="#E60012" class="w-12 h-12" viewBox="0 0 36 36" version="1.1"  preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>tools-solid</title>
                                <rect x="16.4" y="15.4" width="3.2" height="5.2" class="clr-i-solid clr-i-solid-path-1"></rect><path d="M21,21a1,1,0,0,1-1,1H16a1,1,0,0,1-1-1V19H2v9a2,2,0,0,0,2,2H32a2,2,0,0,0,2-2V19H21Z" class="clr-i-solid clr-i-solid-path-2"></path><path d="M33.71,12.38,29.62,8.29A1,1,0,0,0,28.92,8h-5V6.05A2,2,0,0,0,22,4H13.84A1.92,1.92,0,0,0,12,6.05V8H7.08a1,1,0,0,0-.71.29L2.29,12.38a1,1,0,0,0-.29.71V17H15V15a1,1,0,0,1,1-1h4a1,1,0,0,1,1,1v2H34V13.08A1,1,0,0,0,33.71,12.38ZM22,8H14V6h8Z" class="clr-i-solid clr-i-solid-path-3"></path>
                                <rect x="0" y="0" width="36" height="36" fill-opacity="0"/>
                            </svg>
                        </div>
                        <div class="mt-1 text-gray-900 dark:text-gray-100">Talleres # {{workshops}}</div>
                    </div>
                </CardSection>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

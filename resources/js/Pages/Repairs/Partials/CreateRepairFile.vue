<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm,Link } from '@inertiajs/vue3';
import CardSection from '@/Components/CardSection.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    repairId:{
        type:Number,
        required:true
    },
});

const form = useForm({
    files: [],
});


const storeFileRepair = () => {
    form.post(route('repairs.storeFile', props.repairId));
};
</script>

<template>
    <Head title="Car File Form" />
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-semibold  text-gray-800 dark:text-gray-200 leading-tight">
                <Link class="text-red-500 hover:text-red-700 hover:underline" :href="route('repairs.show',props.repairId)">Regresar</Link>
                <span class="text-red-500 font-medium">/</span> Subir un Archivos
            </h1>
        </template>

        <div class="py-12">
            <CardSection class="max-w-7xl">
                <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
                    <form  @submit.prevent="storeFileRepair()">
                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6"> 
                            <div class="sm:col-span-2">
                                <div class="flex items-center justify-center w-full">
                                    <label for="files" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                            </svg>
                                          
                                            <ul v-if="form.files && form.files.length > 0" class="mt-4" >
                                                <li v-for="(file, index) in form.files" :key="index" class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ file.name }}
                                                </li>
                                            </ul>

                                            <ul v-else class="text-center">
                                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click para Subir</span> o Arrastrelos a la Ventana</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">PDF</p>
                                            </ul>
                                        </div>
                                        
                                        <input id="files" type="file" @input="form.files = $event.target.files"  multiple class="hidden" accept="application/pdf" />
                                                                        
                                        <div v-for="(error, index) in form.errors" :key="index">
                                            <InputError :message="error" class="mt-2" />
                                        </div>
                                    </label>
                                </div> 
                            </div>
                        </div>
                        <PrimaryButton class="mt-4" :disabled="form.processing">Upload</PrimaryButton>
                    </form>
                </div>
            </CardSection>
        </div>
    </AuthenticatedLayout>

</template>
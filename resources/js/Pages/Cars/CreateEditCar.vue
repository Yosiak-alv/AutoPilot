<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import CardSection from '@/Components/CardSection.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    car:{
        type:Object,
        required:false
    },
    brands:{
        type:Object,
        required:true
    },
    branches:{
        type:Object,
        required:true
    },
});

const form = useForm({
    id: props.car?.id ?? '',
    plates: props.car?.plates ?? '',
    VIN: props.car?.VIN ?? '',
    motorId: props.car?.motorId ?? '',
    color: props.car?.color ?? '',
    current_mileage: props.car?.current_mileage ?? '',
    year: props.car?.year ?? '',
    brand_id: props.car?.model.brand.id ?? '',
    model_id: props.car?.model_id ?? '',
    branch_id: props.car?.branch_id ?? '',
    
});
const selectBrandModels = (brand_id) => {
    const selectedBrand = props.brands.find((brand) => brand.id === brand_id);
    return selectedBrand ? selectedBrand.models : [];
}
const storeCar = () => {
    form.post(route('cars.store'));
};
const updateCar = (id) => {
    form.put(route('cars.update', id));
};
</script>

<template>
    <Head title="Car Form" />
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-semibold  text-gray-800 dark:text-gray-200 leading-tight">
                <Link class="text-red-500 hover:text-red-700 hover:underline" :href="props.car == null ? route('cars.index') : route('cars.show',props.car.id)">
                    {{props.car == null ? 'Autos' : props.car.model.name}}
                </Link>
                <span class="text-red-500 font-medium">/</span> {{props.car == null ? 'Crear un Nuevo Auto': 'Actualiza un Auto'}}
            </h1>
        </template>

        <div class="py-12">
            <CardSection class="max-w-7xl">
                <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
                    <form @submit.prevent="props.car == null ? storeCar() : updateCar(form.id)" >
                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                            <div class="sm:col-span-2">
                                <InputLabel for="VIN" value="VIN" />

                                <TextInput
                                    id="VIN"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.VIN"
                                    required
                                />

                                <InputError class="mt-2" :message="form.errors.VIN" />
                            </div>
                            <div class="sm:col-span-2">
                                <InputLabel for="motorId" value="Motor" />

                                <TextInput
                                    id="MotorId"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.motorId"
                                    required
                                />

                                <InputError class="mt-2" :message="form.errors.motorId" />
                            </div>
                            <div class="w-full">
                                <InputLabel for="plates" value="Placas" />

                                <TextInput
                                    id="plates"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.plates"
                                    required
                                />

                                <InputError class="mt-2" :message="form.errors.plates" />
                            </div>
                            <div>
                                <InputLabel for="color" value="Color" />

                                <TextInput
                                    id="color"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.color"
                                    required
                                />

                                <InputError class="mt-2" :message="form.errors.color" />
                            </div>
                            <div class="w-full">
                                <InputLabel for="current_mileage" value="Millaje Actual" />

                                <TextInput
                                    id="current_mileage"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.current_mileage"
                                    step="0.01" 
                                    required
                                />

                                <InputError class="mt-2" :message="form.errors.current_mileage" />
                            </div>
                            <div>
                                <InputLabel for="year" value="Año" />

                                <TextInput
                                    id="year"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.year"
                                    required
                                />

                                <InputError class="mt-2" :message="form.errors.year" />
                            </div>
                            <div>
                                <InputLabel for="branch_id" value="Sucursal" />
                                <select 
                                    id="branch_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500"
                                    v-model="form.branch_id"
                                    required
                                >
                                    <option v-for="branch in props.branches" :value="branch.id" :key="branch.id">
                                        {{branch.name}}
                                    </option>
                                </select>

                                <InputError class="mt-2" :message="form.errors.branch_id" />
                            </div> 
                            <div v-if="props.brands.length != 0">
                                <InputLabel for="brand_id" value="Marca" />
                                <select 
                                    id="brand_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500"
                                    v-model="form.brand_id"
                                    required
                                >
                                    <option v-for="brand in props.brands" :value="brand.id" :key="brand.id">
                                        {{brand.name}}
                                    </option>
                                </select>
                            </div>
                           
                            <div v-if="props.brands.length != 0">
                                <InputLabel for="model_id" value="Modelo"/>
                                <select 
                                    id="model_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500"
                                    v-model="form.model_id"
                                    required
                                >
                                    <option v-for="model in selectBrandModels(form.brand_id)" :value="model.id" :key="model.id">
                                        {{model.name}}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.model_id" />
                            </div>
                            <div v-else>
                                <div class="text-gray-900 dark:text-gray-100">
                                    <h2 class="text-sm font-semibold">No Hay Modelos Registrados, Por Favor Registre una Marca con un Modelo antes de Continuar o Contacte al Administrador.</h2>
                                </div>
                                <InputError class="mt-2" :message="form.errors.model_id" />
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <PrimaryButton class="mt-4" :disabled="form.processing">{{props.car == null ? 'Crear' : 'Actualizar'}}</PrimaryButton>
                        </div>
                    </form>
                </div>
            </CardSection>
        </div>
    </AuthenticatedLayout>

</template>
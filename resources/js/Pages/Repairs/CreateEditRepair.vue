<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import CardSection from '@/Components/CardSection.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextAreaComponent from '@/Components/TextAreaComponent.vue';
import DangerButton from '@/Components/DangerButton.vue';
const props = defineProps({
    repair:{
        type:Object,
        required:false
    },
    cars:{
        type:Object,
        required:true
    },
    repair_status:{
        type:Object,
        required:true
    },
    work_shops:{
        type:Object,
        required:true
    },
});

const form = useForm({
    id: props.repair?.id ?? '',
    car_id: props.repair?.car_id ?? '',

    repair_status_id: props.repair?.repair_status_id ?? '',
    work_shop_id: props.repair?.work_shop_id ?? '',
    details: props.repair?.details ?? [{name:'',description:'',price:''}],
    
});
const addDetail = () => {
    form.details.push({name:'',description:'',price:''});
}
const removeDetail = (index) => {
    form.details.splice(index, 1);
}
const getError = (key) => {
      // Check if the key exists in the form errors, if yes, return the error message
      return form.errors[key] ? form.errors[key] : null;
    };
const storeRepair = () => {
    form.post(route('repairs.store'));
};
const updateRepair = (id) => {
    form.put(route('repairs.update', id));
};
//searching car name by id
const getCarName = (id) => {
    const selectedCar = props.cars.find((car) => car.id === id);
    return selectedCar ? selectedCar.plates : '';
}
</script>

<template>
    <Head title="Repair Form" />
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-semibold  text-gray-800 dark:text-gray-200 leading-tight">
                <Link class="text-red-500 hover:text-red-700 hover:underline" :href="props.repair == null ? route('cars.index') : route('cars.show',props.repair.car_id)">
                    {{props.repair == null ? 'Autos' : getCarName(props.repair.car_id)}}
                </Link>
                <span class="text-red-500 font-medium">/</span> {{props.repair == null ? 'Crear un Nueva Reparacion': 'Actualiza una Reparacion'}}
            </h1>
        </template>

        <div class="py-12">
            <CardSection class="max-w-7xl">
                <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
                    <form @submit.prevent="props.repair == null ? storeRepair() : updateRepair(form.id)" >
                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                            <div>
                                <InputLabel for="car_id" value="Auto" />
                                <select 
                                    id="car_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500"
                                    v-model="form.car_id"
                                    required
                                >
                                    <option v-for="car in props.cars" :value="car.id" :key="car.id">
                                        {{car.plates}}, {{car.model.brand?.name}}, {{car.model.name}}
                                    </option>
                                </select>
                            </div>
                            <div  v-if="props.repair == null">
                                <InputLabel for="repair_status_id" value="Status" />
                                <select 
                                    id="repair_status_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500"
                                    v-model="form.repair_status_id"
                                    required
                                >
                                    <option v-for="status in props.repair_status" :value="status.id" :key="status.id">
                                        {{status.name}}
                                    </option>
                                </select>

                                <InputError class="mt-2" :message="form.errors.motorId" />
                            </div>
                            <div class="w-full">
                                <InputLabel for="work_shop_id" value="Taller" />
                                <select 
                                    id="work_shop_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500"
                                    v-model="form.work_shop_id"
                                    required
                                >
                                    <option v-for="work_shop in props.work_shops" :value="work_shop.id" :key="work_shop.id">
                                        <span class="font-semibold">{{ work_shop.name }}</span> | {{work_shop.state.name}}, {{work_shop.town.name}}, {{work_shop.district.name}}
                                    </option>
                                </select>

                                <InputError class="mt-2" :message="form.errors.work_shop_id" />
                            </div>
                            <div class="sm:col-span-2">
                                <SecondaryButton @click="addDetail()" class="mb-4">
                                    Nuevo Detalle
                                </SecondaryButton>
                                <div class="overflow-auto" style="height: 20rem;">
                                    <div v-for="(detail, index) in form.details" :key="index" class="">
                                        <div> 
                                            <InputLabel for="name" :value="`Nombre de Reparacion # ${index + 1}`" />
                                            <TextInput
                                                id="name"
                                                type="text"
                                                class="mt-1 w-3/4"
                                                v-model="detail.name"    
                                                required                         
                                            />
                                            <DangerButton @click="removeDetail(index)" v-if="form.details.length > 1 " class="ml-2" >
                                                Remove
                                            </DangerButton>
                                            <InputError class="mt-2" :message="getError(`details.${index}.name`)" />
                                        </div>
                                        
                                        <div class="mt-2">
                                            <InputLabel for="description" value="Descripcion" />
                                            <TextAreaComponent
                                                id="description"
                                                class="mt-1 w-full"
                                                rows="3"
                                                v-model="detail.description" 
                                                required                            
                                            />
                                            <InputError class="mt-2" :message="getError(`details.${index}.description`)" />
                                        </div>
                                        <div class="">
                                            <div class="flex flex-row flex-wrap justify-center  py-1">
                                                <div>
                                                    <InputLabel for="price" value="Precio" />
                                                    <TextInput
                                                        id="price"
                                                        type="number"
                                                        class="mt-1 w-3/4"
                                                        :min="1"
                                                        step="0.01"  
                                                        v-model="detail.price"  
                                                        required                           
                                                    />
                                                    <InputError class="mt-2" :message="getError(`details.${index}.price`)" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-4 mt-4 border-b-4"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-start">
                            <PrimaryButton :disabled="form.processing">{{props.repair == null ? 'Crear' : 'Actualizar'}}</PrimaryButton>
                        </div>
                    </form>
                </div>
            </CardSection>
        </div>
    </AuthenticatedLayout>

</template>
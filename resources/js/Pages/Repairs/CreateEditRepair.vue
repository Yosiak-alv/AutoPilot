<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
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
    details: props.repair?.details ?? [{name:'',description:'',price:'',taxes:''}],
    
});
const addDetail = () => {
    form.details.push({name:'',description:'',price:'',taxes:''});
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
</script>

<template>
    <Head title="Repair Form" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{props.repair == null ? 'Crear un Reparacion': 'Actualiza una Reparacion'}}</h2>
        </template>

        <div class="py-12">
        <CardSection class="max-w-7xl">
           <form @submit.prevent="props.repair == null ? storeRepair() : updateRepair(form.id)" class="space-y-6">
                <div class="grid grid-cols-2 gap-4 p-6">
                    <div>
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

                            <InputError class="mt-2" :message="form.errors.car_id" />
                        </div>
                        <div class="mt-2" v-if="props.repair == null">
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

                            <InputError class="mt-2" :message="form.errors.repair_status_id" />
                        </div>
                        <div class="mt-2">
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
                    </div>
                    <div>                       
                        <div class="mt-2">
                            <SecondaryButton @click="addDetail()" class="mb-4">
                                Nuevo Detalle
                            </SecondaryButton>
                            <div class="overflow-auto" style="height: 20rem;">
                                <div v-for="(detail, index) in form.details" :key="index" class="">
                                    <div> 
                                        <InputLabel for="name" value="Nombre de Reparacion" />
                                        <TextInput
                                            id="name"
                                            type="text"
                                            class="mt-1 w-3/4"
                                            v-model="detail.name"                             
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
                                        />
                                        <InputError class="mt-2" :message="getError(`details.${index}.description`)" />
                                    </div>
                                    <div class="mt-2">
                                        <div class="flex flex-row flex-wrap space-x-4">
                                            <div>
                                                <InputLabel for="price" value="Precio" />
                                                <TextInput
                                                    id="price"
                                                    type="number"
                                                    class="mt-1 w-3/4"
                                                    :min="1"
                                                    step="0.01"  
                                                    v-model="detail.price"                             
                                                />
                                                <InputError class="mt-2" :message="getError(`details.${index}.price`)" />
                                            </div>
                                            <div>
                                                <InputLabel for="taxes" value="Impuesto" />
                                                <TextInput
                                                    id="taxes"
                                                    type="number"
                                                    class="mt-1 w-3/4"
                                                    :min="1"
                                                    step="0.01"  
                                                    v-model="detail.taxes"                             
                                                />
                                                <InputError class="mt-2" :message="getError(`details.${index}.taxes`)" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 mt-3">
                        <PrimaryButton :disabled="form.processing">{{props.repair == null ? 'Crear' : 'Actualizar'}}</PrimaryButton>
                    </div>
                </div>
           </form>
        </CardSection>
        </div>
    </AuthenticatedLayout>

</template>
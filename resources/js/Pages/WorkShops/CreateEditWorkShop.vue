<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import CardSection from '@/Components/CardSection.vue';
import TextInput from '@/Components/TextInput.vue';
import TextAreaComponent from '@/Components/TextAreaComponent.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    workshop:{
        type:Object,
        required:false
    },
    states:{
        type:Object,
        required:true
    }
});

const form = useForm({
    id: props.workshop?.id ?? '',
    name: props.workshop?.name ?? '',
    address: props.workshop?.address ?? '',
    email: props.workshop?.email ?? '',
    telephone: props.workshop?.telephone ?? '',
    state_id: props.workshop?.state.id ?? '',
    town_id: props.workshop?.town.id ?? '',
    district_id: props.workshop?.district_id ?? '',
});
const selectStateTowns = (state_id) => {
    const selectedState = props.states.find((state) => state.id === state_id);
    return selectedState ? selectedState.towns : [];
}
const selectStateTownsDistrict = (state_id,town_id) => {
    const selectedState = props.states.find((state) => state.id === state_id);
    const selectedTown = selectedState?.towns.find((town) => town.id === town_id);
    return selectedTown ? selectedTown.districts : [];
}
const storeWorkshop = () => {
    form.post(route('workshops.store'));
};
const updateWorkshop = (id) => {
    form.patch(route('workshops.update', id));
};
</script>

<template>
<Head title="WorkShop Form" />
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-semibold  text-gray-800 dark:text-gray-200 leading-tight">
                <Link class="text-red-500 hover:text-red-700 hover:underline" :href="props.workshop == null ? route('workshops.index') : route('workshops.show',props.workshop.id)">
                    {{props.workshop == null ? 'Talleres' : props.workshop.name}}
                </Link>
                <span class="text-red-500 font-medium">/</span> {{props.workshop == null ? 'Crear Nuevo Taller': 'Actualiza un Taller'}}
            </h1>
        </template>

        <div class="py-12">
            <CardSection class="max-w-7xl">
                <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
                    <form @submit.prevent="props.workshop == null ? storeWorkshop() : updateWorkshop(form.id)" >
                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                            <div class="sm:col-span-2">
                                <InputLabel for="name" value="Nombre" />

                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                />

                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>
                            <div>
                                <InputLabel for="email" value="Correo" />

                                <TextInput
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.email"
                                    required
                                />

                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>
                            <div class="w-full">
                                <InputLabel for="telephone" value="Telefono" />

                                <TextInput
                                    id="telephone"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.telephone"
                                    required
                                />

                                <InputError class="mt-2" :message="form.errors.telephone" />
                            </div>
                            <div class="sm:col-span-2"> 
                                <InputLabel for="state_id" value="Departamento" />
                                <select 
                                    id="state_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500"
                                    v-model="form.state_id"
                                    required
                                >
                                    <option v-for="state in props.states" :value="state.id" :key="state.id">
                                        {{state.name}}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <InputLabel for="town_id" value="Municipio" />
                                <select 
                                    id="town_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500"
                                    v-model="form.town_id"
                                    required
                                >
                                    <option v-for="town in selectStateTowns(form.state_id)" :value="town.id" :key="town.id">
                                        {{town.name}}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <InputLabel for="district_id" value="Distrito"/>
                                <select 
                                    id="district_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500"
                                    v-model="form.district_id"
                                    required
                                >
                                    <option v-for="district in selectStateTownsDistrict(form.state_id,form.town_id)" :value="district.id" :key="district.id">
                                        {{district.name}}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.district_id" />
                            </div>
                            <div class="sm:col-span-2">
                                <InputLabel for="address" value="Direccion" />
                                <TextAreaComponent
                                    id="description"
                                    class="mt-1 w-full"
                                    rows="3"
                                    v-model="form.address"   
                                    required                          
                                />

                                <InputError class="mt-2" :message="form.errors.address" />
                            </div>
                        </div>
                        <div class="flex justify-start mt-2">
                            <PrimaryButton :disabled="form.processing">{{props.workshop == null ? 'Crear' : 'Actualizar'}}</PrimaryButton>
                        </div>
                    </form>
                </div>
            </CardSection>
        </div>
    </AuthenticatedLayout>

</template>
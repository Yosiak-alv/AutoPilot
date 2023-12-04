<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import CardSection from '@/Components/CardSection.vue';
import TextInput from '@/Components/TextInput.vue';
import TextAreaComponent from '@/Components/TextAreaComponent.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    branch:{
        type:Object,
        required:false
    },
    states:{
        type:Object,
        required:true
    }
});

const form = useForm({
    id: props.branch?.id ?? '',
    name: props.branch?.name ?? '',
    address: props.branch?.address ?? '',
    email: props.branch?.email ?? '',
    telephone: props.branch?.telephone ?? '',
    main: props.branch?.main ?? '',
    state_id: props.branch?.state.id ?? '',
    town_id: props.branch?.town.id ?? '',
    district_id: props.branch?.district_id ?? '',
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
const storeBranch = () => {
    form.post(route('branches.store'));
};
const updateBranch = (id) => {
    form.patch(route('branches.update', id));
};
</script>

<template>
<Head title="Branch Form" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{props.branch == null ? 'Crear Nuevo Centro': 'Actualiza un Centro'}}</h2>
        </template>

        <div class="py-12">
        <CardSection class="max-w-7xl">
           <form @submit.prevent="props.branch == null ? storeBranch() : updateBranch(form.id)" class="space-y-6">
                <div class="grid grid-cols-2 gap-4 p-6">
                    <div>
                        <div>
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
                        <div class="mt-2">
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
                        <div class="mt-2">
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
                        <div class="mt-2">
                            <InputLabel for="main" value="Es Central" />

                            <select 
                                id="branch_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500"
                                v-model="form.main"
                                required
                            >
                                <option v-for="value in [{id:0,name:'No'},{id:1,name:'Si'}]" :value="value.id" :key="value.id">
                                    {{value.name}}
                                </option>
                            </select>

                            <InputError class="mt-2" :message="form.errors.main" />
                        </div>
                    </div>
                    <div>                       
                        <div >
                            <InputLabel for="address" value="Direccion" />
                            <TextAreaComponent
                                id="description"
                                class="mt-1 w-full"
                                rows="2"
                                v-model="form.address"                             
                            />

                            <InputError class="mt-2" :message="form.errors.address" />
                        </div>
                        <div class="mt-2">
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

                            <InputLabel for="town_id" value="Municipio" class="mt-2" />
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

                            <InputLabel for="district_id" value="Distrito" class="mt-2" />
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
                    </div>
                    <div class="flex items-center gap-4 mt-3">
                        <PrimaryButton :disabled="form.processing">{{props.branch == null ? 'Crear' : 'Actualizar'}}</PrimaryButton>
                    </div>
                </div>
                
           </form>
        </CardSection>
        </div>
    </AuthenticatedLayout>

</template>
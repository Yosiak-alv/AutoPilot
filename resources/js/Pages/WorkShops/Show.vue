<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, Link} from '@inertiajs/vue3';
import { ref } from 'vue';
import CardSection from '@/Components/CardSection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    workshop:{
        type:Object,
        required:true
    },
});

const edit = () => {
    router.get(route('workshops.edit', props.workshop.id));
}
const destroy = () => {
    router.delete(route('workshops.destroy', props.workshop.id));
}
</script>

<template>
<Head title="WorkShop Show" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Taller #{{props.workshop.id}}</h2>
        </template>
        
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-wrap my-12">
                    <div class="mx-auto">
                        <CardSection>
                            <div class="grid grid-cols-4 gap-4 p-6 items-center">
                                <div class="col-span-3">
                                    <span class="inline text-3xl h-fit">{{ props.workshop.name }}</span>
                                    <div class="mt-2 text-lg">
                                        <span class="font-semibold">Correo:</span> {{ props.workshop.email }}
                                        <br>
                                        <span class="font-semibold">Telefono:</span> {{ props.workshop.telephone }}
                                        <br>
                                        <span class="font-semibold">Address:</span> {{ props.workshop.address }}
                                        <br>
                                        <span class="font-semibold">Departamento:</span> {{ props.workshop.state.name}}
                                        <br>
                                        <span class="font-semibold">Ciudad:</span> {{ props.workshop.town.name}}
                                        <br>
                                        <span class="font-semibold">Distrito:</span> {{ props.workshop.district.name}}
                                        <br>
                                        <span class="font-semibold">Creado:</span> {{ props.workshop.created_at }}
                                        <br>
                                        <span class="font-semibold">Actualizado:</span> {{ props.workshop.updated_at }}
                                    </div>
                                </div>
                                <div class="col-span-1 justify-items-center">
                                    <div>
                                        <PrimaryButton class="ml-8" @click="edit()">
                                            Editar
                                        </PrimaryButton>
                                    </div>
                                    <div>
                                        <DangerButton class="mt-2 ml-8" @click="destroy()" >
                                            Eliminar Centro
                                        </DangerButton>
                                    </div>
                                </div>
                            </div>
                        </CardSection>
                    </div>
                </div>
            </div>    
        </div>
    </AuthenticatedLayout>
</template>
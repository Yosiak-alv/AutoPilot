<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, Link} from '@inertiajs/vue3';
import { ref } from 'vue';
import CardSection from '@/Components/CardSection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    branch:{
        type:Object,
        required:true
    },
});

const edit = () => {
    router.get(route('branches.edit', props.branch.id));
}
const destroy = () => {
    router.delete(route('branches.destroy', props.branch.id));
}
</script>

<template>
<Head title="Branch Show" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Centro #{{props.branch.id}}</h2>
        </template>
        
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-wrap my-12">
                    <div class="mx-auto">
                        <CardSection>
                            <div class="grid grid-cols-4 gap-4 p-6 items-center">
                                <div class="col-span-3">
                                    <span class="inline text-3xl h-fit">{{ props.branch.name }}</span>
                                    <div class="mt-2 text-lg">
                                        <span class="font-semibold">Correo:</span> {{ props.branch.email }}
                                        <br>
                                        <span class="font-semibold">Telefono:</span> {{ props.branch.telephone }}
                                        <br>
                                        <span class="font-semibold">Es Central:</span> {{ props.branch.main == 1 ? 'Si':'No' }}
                                        <br>
                                        <span class="font-semibold">Address:</span> {{ props.branch.address }}
                                        <br>
                                        <span class="font-semibold">Departamento:</span> {{ props.branch.state.name}}
                                        <br>
                                        <span class="font-semibold">Ciudad:</span> {{ props.branch.town.name}}
                                        <br>
                                        <span class="font-semibold">Distrito:</span> {{ props.branch.district.name}}
                                        <br>
                                        <span class="font-semibold">Creado:</span> {{ props.branch.created_at }}
                                        <br>
                                        <span class="font-semibold">Actualizado:</span> {{ props.branch.updated_at }}
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
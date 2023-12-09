<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, Link, usePage} from '@inertiajs/vue3';
import { ref,computed, nextTick } from 'vue';
import CardSection from '@/Components/CardSection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import RestoreMessage from '@/Components/RestoreMessage.vue';

const props = defineProps({
    branch:{
        type:Object,
        required:true
    },
});

const edit = () => {
    router.get(route('branches.edit', props.branch.id));
}
//Destroy Modal
const comfirmingDestroy = ref(false);
const passwordInput = ref(null);

const formDestroy = useForm({
    password: '',
});
const confirmDestroy = () => {
    comfirmingDestroy.value = true;
    nextTick(() => passwordInput.value.focus());
};

const deleteBranch = () => {
    formDestroy.delete(route('branches.destroy',props.branch.id), {
        preserveScroll: true,
        onSuccess: () => closeModalDestroy(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => formDestroy.reset(),
    });
};

const closeModalDestroy = () => {
    comfirmingDestroy.value = false;
    formDestroy.reset();
    formDestroy.clearErrors();
};
//permissions
const permissions = ref(usePage().props.auth.user_permissions);
const hasPermission = (permissionName) => {
    return computed(() => permissions.value.includes(permissionName)).value;
};
</script>

<template>
<Head title="Branch Show" />
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-semibold  text-gray-800 dark:text-gray-200 leading-tight">
                <Link class="text-red-500 hover:text-red-700 hover:underline" :href="route('branches.index')">Centros</Link>
                <span class="text-red-500 font-medium">/</span> {{props.branch.name}}
            </h1>
        </template>
        
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="branch.deleted_at != null" class="space-y-4">
                    <RestoreMessage :permission="hasPermission('restaurar auto')" @restore="router.patch(route('branches.restore',props.branch.id))">
                        Este Centro esta eliminado, si lo restauras podras acceder y editarlo nuevamente.
                    </RestoreMessage>
                    <!-- <ForceDeleteMessage :permission="hasPermission('force-delete auto')" :url="'cars.forceDelete'" :modelToForceDeleteId="props.car.id">
                        Este Centro esta eliminado, si lo eliminas permanentemente no podras acceder a el nuevamente.
                    </ForceDeleteMessage> -->
                </div>
            </div>    
        </div>
        <div class="py-4">
            <CardSection class="max-w-7xl ">
                <div class="flex flex-wrap my-12">
                    <div class="mx-auto">
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
                                <span class="font-semibold">Municipio:</span> {{ props.branch.town.name}}
                                <br>
                                <span class="font-semibold">Distrito:</span> {{ props.branch.district.name}}
                                <br>
                                <span class="font-semibold">Creado:</span> {{ props.branch.created_at }}
                                <br>
                                <span class="font-semibold">Actualizado:</span> {{ props.branch.updated_at }}
                                <br>
                                <span v-if="props.branch.deleted_at" class="font-semibold">Eliminado el:</span> {{props.branch.deleted_at}}
                            </div>
                    </div>
                    <div class="mx-auto items-center text-center">
                        <div class="p-5">
                            <div>
                                <PrimaryButton v-if="hasPermission('editar centro') && branch.deleted_at == null"  @click="edit()">
                                    Editar
                                </PrimaryButton>
                            </div>
                            <div>
                                <DangerButton  v-if="hasPermission('eliminar centro') && branch.deleted_at == null" class="mt-2" @click="confirmDestroy()" >
                                    Eliminar Centro
                                </DangerButton>
                            </div>
                        </div>
                       
                    </div>
                </div>
            </CardSection>
        </div>
    </AuthenticatedLayout>

    <Modal :show="comfirmingDestroy" @close="closeModalDestroy">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    ¿Seguro que quieres eliminar es Centro?
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Una vez eliminado este Centro, permanecer en la base de datos pero no se podra acceder a el,
                    hasta que se restaure, Todos y cada una de sus relaciones se veran afectadas (usuarios y autos ).
                    Esta seguro de que quiere eliminar este Centro ?
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Contraseña" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="formDestroy.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        @keydown.enter="deleteBranch"
                    />

                    <InputError :message="formDestroy.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModalDestroy"> Cancelar </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': formDestroy.processing }"
                        :disabled="formDestroy.processing"
                        @click="deleteBranch"
                    >
                        Eliminar Auto
                    </DangerButton>
                </div>
            </div>
        </Modal>
</template>
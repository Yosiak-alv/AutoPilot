<script setup>
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';
    import DangerButton from '@/Components/DangerButton.vue';
    import Modal from '@/Components/Modal.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import TextInput from '@/Components/TextInput.vue';
    import InputError from '@/Components/InputError.vue';
    import { useForm} from '@inertiajs/vue3';
    import {ref,nextTick,} from 'vue';
    const props = defineProps({
        permission:{
            type:Boolean,
            default:false
        },
        modelToForceDeleteId:{
            type:Number,
            required:true
        },
        url:{
            type:String,
            required:true
        }
    });
    const url = ref(props.url);
    const modelToForceDeleteId = ref(props.modelToForceDeleteId);
    //force Delete Modal
    const comfirmingForceDelete = ref(false);
    const passwordForceInput = ref(null);

    const formForceDelete = useForm({
        password: '',
    });
    const confirmForceDelete = () => {
        comfirmingForceDelete.value = true;
        nextTick(() => passwordForceInput.value.focus());
    };

    const forceDeleteAction = () => {
        formForceDelete.delete(route(url.value, modelToForceDeleteId.value), {
            preserveScroll: true,
            onSuccess: () => closeModalDestroy(),
            onError: () => passwordForceInput.value.focus(),
            onFinish: () => formForceDelete.reset(),
        });
    };
    const closeModalForceDelete = () => {
        comfirmingForceDelete.value = false;
        formForceDelete.reset();
        formForceDelete.clearErrors();
        
        modelToForceDeleteId.value = null;
        url.value = null;
    };
</script>  
<template>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 h-full" v-if="permission" >
        <div class="flex items-center justify-between p-4 bg-red-600 rounded ">
            <div class="flex items-center">
                <svg fill="none"  stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                </svg>
                <div class="text-gray-200 text-sm font-medium">
                    <slot/>
                </div>
            </div>
            <button class="text-gray-200 hover:underline text-sm"  type="button" @click="confirmForceDelete()">Eliminar Permanentemente</button>
        </div>
    </div>

    <Modal :show="comfirmingForceDelete" @close="closeModalForceDelete ">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    ¿Seguro que quieres eliminar Permanentemente este registro? {{url}}, {{modelToForceDeleteId}}
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Una vez eliminado este registro, no podras recuperarlo, se eliminaran permanentemente el registro y todas sus relaciones a el. 
                    ¿Estas seguro que quieres eliminarlo permanentemente?
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Contraseña" />

                    <TextInput
                        id="password"
                        ref="passwordForceInput"
                        v-model="formForceDelete.password"
                        type="password"
                        class="mt-1 block w-3/4"
                    />

                    <InputError :message="formForceDelete.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModalForceDelete "> Cancelar </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': formForceDelete.processing }"
                        :disabled="formForceDelete.processing"
                        @click="forceDeleteAction"
                    >
                        Eliminar Permanentemente
                    </DangerButton>
                </div>
            </div>
        </Modal>
</template>
<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Información sobre el Perfil</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Información de perfil y la dirección de correo electrónico de su cuenta.
            </p>
        </header>

        <div class="mt-6 space-y-6">
            <div>
                <InputLabel for="name" value="Nombre" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    :disabled="true"
                    v-model="user.name"
                    required
                    autofocus
                    autocomplete="name"
                />
            </div>

            <div>
                <InputLabel for="email" value="Correo" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="user.email"
                    required
                    :disabled="true"
                    autocomplete="username"
                />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                    Su dirección de correo electrónico no está verificada.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    >
                        Haga clic aquí para volver a enviar el correo electrónico de verificación.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600 dark:text-green-400"
                >
                    Se ha enviado un nuevo enlace de verificación a su dirección de correo electrónico.
                </div>
            </div>
        </div>
    </section>
</template>

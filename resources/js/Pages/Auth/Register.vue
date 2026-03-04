<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Crear cuenta" />

        <div class="space-y-6 text-white">
            <div>
                <h1 class="text-3xl font-semibold">Crear cuenta TecnoSector</h1>
                <p class="mt-2 text-sm text-white/70">Configura tu perfil para sincronizar compras, direcciones y preferencias.</p>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <InputLabel for="name" value="Nombre" class="text-white/70" />

                    <TextInput
                        id="name"
                        type="text"
                        class="ts-input mt-2"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                    />

                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel for="email" value="Correo electrónico" class="text-white/70" />

                    <TextInput
                        id="email"
                        type="email"
                        class="ts-input mt-2"
                        v-model="form.email"
                        required
                        autocomplete="username"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div>
                    <InputLabel for="password" value="Contraseña" class="text-white/70" />

                    <TextInput
                        id="password"
                        type="password"
                        class="ts-input mt-2"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                    />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div>
                    <InputLabel for="password_confirmation" value="Confirmar contraseña" class="text-white/70" />

                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="ts-input mt-2"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                    />

                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>

                <div class="flex items-center justify-between text-sm text-white/70">
                    <Link
                        :href="route('login')"
                        class="text-white hover:text-white"
                    >
                        ¿Ya tenés cuenta?
                    </Link>

                    <button type="submit" class="ts-btn-primary text-sm"
                        :class="{ 'opacity-40': form.processing }" :disabled="form.processing">
                        Registrarme
                    </button>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>

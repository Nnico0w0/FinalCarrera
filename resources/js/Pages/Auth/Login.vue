<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Iniciar sesión" />

        <div class="space-y-6 text-white">
            <div>
                <h1 class="text-3xl font-semibold">Bienvenido otra vez</h1>
                <p class="mt-2 text-sm text-white/70">Ingresa con tu cuenta TecnoSector para acceder a tus pedidos y configuraciones.</p>
            </div>

            <div v-if="status" class="rounded-2xl border border-emerald-400/30 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-100">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <InputLabel for="email" value="Correo electrónico" class="text-white/70" />

                    <TextInput
                        id="email"
                        type="email"
                        class="ts-input mt-2"
                        v-model="form.email"
                        required
                        autofocus
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
                        autocomplete="current-password"
                    />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="flex items-center justify-between text-sm text-white/70">
                    <label class="flex items-center gap-2">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        Recordarme
                    </label>

                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-white hover:text-white"
                    >
                        ¿Olvidaste la contraseña?
                    </Link>
                </div>

                <button type="submit" class="ts-btn-primary w-full text-center" :class="{ 'opacity-40': form.processing }"
                    :disabled="form.processing">
                    Entrar
                </button>
            </form>
        </div>
    </GuestLayout>
</template>

<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: true,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in"/>

    <div class="container auto">
        <div>
            <div v-if="status" class="fs">
                {{ status }}
            </div>

            <div class="fs2">
                Вход
            </div>

            <form @submit.prevent="submit">
                <div>
                    <input type="email" id="email" v-model="form.email" required
                           autofocus autocomplete="username" placeholder="Email">

                    <div class="err">{{ form.errors.email }}</div>
                </div>

                <div class="mt-4">
                    <input id="password"
                           type="password"
                           v-model="form.password"
                           required
                           autocomplete="current-password" placeholder="Пароль">

                    <div class="err">{{ form.errors.password }}</div>
                </div>

                <div class="flex-header">
                    <a
                        href="/register"
                        class="fs">
                        Еще нет аккаунта?
                    </a>

                    <button type="submit" class="btn btn-primary" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                        Войти
                    </button>
                </div>
            </form>
        </div>
    </div>

</template>

<template>
    <auth-layout>
        <template #title>
            <div>
                <div class="mt-6">
                    <h4 class="font-semibold text-xl text-gray-800">Login to your account</h4>

                    <h6 class="mt-3 font-normal text-base text-gray-500">
                        Thank you for getting back to us. Lets access your account and get you started.
                    </h6>
                </div>
            </div>
        </template>

        <template #form>
            <form @submit.prevent="login" class="w-full">
                <div class="mt-6 block">
                    <app-input type="email" v-model="form.email" autofocus :error="form.errors.email" label="Email address" placeholder="john.doe@example.com"></app-input>
                </div>

                <div class="mt-6 block">
                    <app-input type="password" v-model="form.password" :error="form.errors.password" label="Password" placeholder="cattleFarmer1576@!"></app-input>
                </div>

                <div class="mt-6 flex items-center justify-between">
                    <div>
                        <checkbox id="remember" v-model="form.remember" label="Stay signed in"></checkbox>
                    </div>

                    <div class="text-sm leading-5">
                        Forgot your password?
                    </div>
                </div>

                <div class="mt-6 block">
                    <app-button type="submit" mode="primary" :class="{ 'opacity-25': form.processing }" :loading="form.processing">
                        Sign in <span class="ml-1">&rarr;</span>
                    </app-button>
                </div>
            </form>
        </template>
    </auth-layout>
</template>

<script>
import AuthLayout from '@/Views/Layouts/AuthLayout';
import AppInput from '@/Views/Components/Inputs/Input';
import AppButton from '@/Views/Components/Buttons/Button';
import Checkbox from '@/Views/Components/Inputs/Checkbox';

export default {
    components: {
        AuthLayout,
        AppInput,
        AppButton,
        Checkbox
    },

    data() {
        return {
            form: this.$inertia.form({
                email: null,
                password: null,
                remember: true
            }, {
                bag: 'login',
                resetOnSuccess: true,
            }),
        }
    },

    methods: {
        async login() {
            await this.form.post(route('login.store'), {
                onSuccess: () => this.$inertia.get(route('home'))
            });
        }
    }
};
</script>

<template>
    <auth-layout>
        <template #title>
            <div>
                <div>
                    <logo :title="config('app.name')" classes="h-16 w-auto text-blue-500"></logo>
                </div>

                <h4 class="mt-6 font-semibold text-xl text-gray-800">Create new account</h4>

                <h6 class="mt-3 font-normal text-base text-gray-500">
                    Let's get you all set up so you can verify your personal account and begin setting up your profile.
                </h6>
            </div>
        </template>

        <template #form>
            <form @submit.prevent="register" class="w-full">
                <div class="block">
                    <app-input type="text" v-model="form.name" autofocus :error="form.errors.name" label="Full name" placeholder="Johnathan Doeford"></app-input>
                </div>

                <div class="mt-6 block">
                    <app-input type="email" v-model="form.email" autofocus :error="form.errors.email" label="Email address" placeholder="john.doe@example.com"></app-input>
                </div>

                <div class="mt-6 block">
                    <app-input type="password" v-model="form.password" :error="form.errors.password" label="Password" placeholder="cattleFarmer1576@!"></app-input>
                </div>

                <div class="mt-6 block">
                    <app-button type="submit" mode="primary" :class="{ 'opacity-25': form.processing }" :loading="form.processing">
                        Create account <span class="ml-1">&rarr;</span>
                    </app-button>
                </div>

                <div class="mt-6">
                    <p>
                        Already an account? <app-link :href="route('login.create')">Log in</app-link>
                    </p>
                </div>
            </form>
        </template>
    </auth-layout>
</template>

<script>
import AuthLayout from '@/Views/Layouts/AuthLayout';
import Logo from '@/Views/Components/Logos/Logo';
import AppLink from '@/Views/Components/Base/Link';
import AppInput from '@/Views/Components/Inputs/Input';
import AppButton from '@/Views/Components/Buttons/Button';
import Checkbox from '@/Views/Components/Inputs/Checkbox';

export default {
    components: {
        AuthLayout,
        Logo,
        AppLink,
        AppInput,
        AppButton,
        Checkbox
    },

    data() {
        return {
            form: this.$inertia.form({
                name: null,
                email: null,
                password: null,
                remember: true
            }, {
                bag: 'register',
                resetOnSuccess: true,
            }),
        }
    },

    methods: {
        async register() {
            await this.form.post(route('register.store'), {
                onSuccess: () => this.$inertia.get(route('home'))
            });
        }
    }
};
</script>

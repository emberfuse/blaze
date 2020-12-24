<template>
    <auth-layout>
        <template #title>
            <div>
                <logo class="h-16 w-16"></logo>
            </div>

            <div class="mt-6">
                <h2 class="font-semibold text-3xl text-gray-800">Welcome back</h2>

                <h6 class="text-base font-normal">
                    Don't have an account yet?
                    <router-link :to="{ name: 'register' }" v-slot="{ href }">
                        <app-link :href="href">Sign up</app-link>
                    </router-link>
                </h6>
            </div>
        </template>

        <template #content>
            <form @submit.prevent="login" class="w-full">
                <div class="mt-6 block">
                    <app-input type="email" v-model="form.email" autofocus :error="form.error('email')" label="Email address" placeholder="john.doe@example.com"></app-input>
                </div>

                <div class="mt-6 block">
                    <app-input type="password" v-model="form.password" :error="form.error('password')" label="Password" placeholder="cattleFarmer1576@!"></app-input>
                </div>

                <div class="mt-6 flex items-center justify-between">
                    <div>
                        <checkbox id="remember" v-model="form.remember" label="Stay signed in"></checkbox>
                    </div>

                    <div class="text-sm leading-5">
                        <app-link href="#">Forgot your password?</app-link>
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
import Logo from '@/Views/Components/Logos/Logo';
import AppLink from "@/Views/Components/Base/Link";
import AppInput from "@/Views/Components/Inputs/Input";
import AppButton from "@/Views/Components/Buttons/Button";
import Checkbox from "@/Views/Components/Inputs/Checkbox";

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
            form: this.$form({
                email: null,
                password: null,
                remember: true
            }, {
                resetOnSuccess: false
            })
        };
    },

    methods: {
        async login() {
            await this.$http.get('/sanctum/csrf-cookie').then(async (response) => {
                const token = this.$cookies.get('XSRF-TOKEN');

                this.$store.dispatch('saveToken', {
                    token, remember: this.form.remember
                });

                await this.form.post('/login', { 'X-XSRF-TOKEN': token })
                    .then((response) => {
                        if (!this.form.hasErrors()) {
                            this.redirectTo(response);
                        }
                    });
            });
        },

        redirectTo(response) {
            const intendedUrl = this.$cookies.get('intended_url');

            if (intendedUrl) {
                this.$route.push({ path: intendedUrl });
            } else if (response.data.tfa === true) {
                this.$route.push({ name: "tfa.login" });
            }

            this.$route.push({ name: "home" });
        }
    }
}
</script>

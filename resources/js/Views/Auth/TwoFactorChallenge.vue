<template>
    <auth-layout>
        <template #title>
            <div>
                <div>
                    <logo :title="config('app.name')" classes="h-16 w-auto text-blue-500"></logo>
                </div>

                <h4 class="mt-6 font-semibold text-xl text-gray-800">Confirm you login attempt</h4>

                <div class="mt-3 font-normal text-base text-gray-500">
                    <p v-if="! recovery">
                        Please confirm access to your account by entering the authentication code provided by your authenticator application.
                    </p>

                    <p v-else>
                        Please confirm access to your account by entering one of your emergency recovery codes.
                    </p>
                </div>
            </div>
        </template>

        <template #form>
            <form @submit.prevent="login" class="w-full">
                <div class="block">
                    <app-input type="email" v-model="form.email" :error="form.errors.email" label="Email address" placeholder="john.doe@example.com" required autofocus></app-input>
                </div>

                <div class="mt-6 block">
                    <app-button type="button" @click.prevent="toggleRecovery" mode="primary" :class="{ 'opacity-25': form.processing }" :loading="form.processing">
                        <template v-if="! recovery">
                            Use a recovery code
                        </template>

                        <template v-else>
                            Use an authentication code
                        </template>
                    </app-button>

                    <app-button class="ml-4" type="submit" mode="primary" :class="{ 'opacity-25': form.processing }" :loading="form.processing">
                        Confirm it's me <span class="ml-1">&rarr;</span>
                    </app-button>
                </div>

                <div class="mt-6">
                    <p>
                        Don't have an account yet? <app-link :href="route('register')">join {{ config('app.name') }}</app-link>
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
            recovery: false,

            form: this.$inertia.form({
                code: null,
                recovery_code: null,
            })
        }
    },

    methods: {
        toggleRecovery() {
            this.recovery ^= true

            this.$nextTick(() => {
                if (this.recovery) {
                    this.$refs.recovery_code.focus();

                    this.form.code = '';
                } else {
                    this.$refs.code.focus();

                    this.form.recovery_code = null;
                }
            })
        },

        async login() {
            await this.form.post(this.route('two-factor.login'));
        }
    }
}
</script>

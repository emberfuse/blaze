<template>
    <div>
        <action-section>
            <template #title>
                Two Factor Authentication
            </template>

            <template #description>
                Add additional security to your account using two factor authentication.
            </template>

            <template #content>
                <h6 class="text-base font-semibold text-gray-900" v-if="twoFactorEnabled">
                    You have enabled two factor authentication.
                </h6>

                <h6 class="text-base font-semibold text-gray-900" v-else>
                    You have not enabled two factor authentication.
                </h6>

                <div class="mt-3 max-w-xl">
                    <p class="text-sm text-gray-600">
                        When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application.
                    </p>
                </div>

                <div class="mt-5">
                    <div v-if="! twoFactorEnabled">
                        <confirm-password-modal @confirmed="enableTwoFactorAuthentication">
                            <app-button type="button" mode="primary" :class="{ 'opacity-25': enabling }" :loading="enabling">
                                Enable
                            </app-button>
                        </confirm-password-modal>
                    </div>
                </div>
            </template>
        </action-section>
    </div>
</template>

<script>
import ConfirmPasswordModal from '@/Views/Components/Modals/ConfirmPasswordModal';
import ActionSection from '@/Views/Components/Sections/ActionSection';
import AppButton from '@/Views/Components/Buttons/Button';

export default {
    components: {
        ActionSection,
        AppButton,
        ConfirmPasswordModal
    },

    computed: {
        twoFactorEnabled() {
            return ! this.enabling && this.$page.props.user.two_factor_enabled
        }
    },

    data() {
        return {
            enabling: false,
            disabling: false,

            qrCode: null,
            recoveryCodes: [],
        }
    },

    methods: {
        enableTwoFactorAuthentication() {
            this.enabling = true;

            this.$inertia.post('/user/two-factor-authentication', {}, {
                preserveScroll: true,
                onSuccess: () => Promise.all([
                    this.showQrCode(),
                    this.showRecoveryCodes(),
                ]),
                onFinish: () => (this.enabling = false),
            });
        },

        showQrCode() {
            return this.$http.get('/user/two-factor-qr-code')
                .then(response => this.qrCode = response.data.svg);
        },

        showRecoveryCodes() {
            return this.$http.get('/user/two-factor-recovery-codes')
                .then(response => this.recoveryCodes = response.data);
        },

        regenerateRecoveryCodes() {
            this.$http.post('/user/two-factor-recovery-codes')
                .then(response => this.showRecoveryCodes());
        },

        disableTwoFactorAuthentication() {
            this.disabling = true;

            this.$inertia.delete('/user/two-factor-authentication', {
                preserveScroll: true,
                onSuccess: () => (this.disabling = false),
            });
        },
    },

    computed: {
        twoFactorEnabled() {
            return ! this.enabling && this.$page.props.user.two_factor_enabled;
        }
    }
}
</script>

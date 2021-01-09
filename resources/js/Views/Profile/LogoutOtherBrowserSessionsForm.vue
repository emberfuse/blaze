<template>

</template>

<script>
export default {
    props: ['sessions'],

    data() {
        return {
            confirmingLogout: false,

            form: this.$inertia.form({
                password: null,
            })
        }
    },

    methods: {
        confirmLogout() {
            this.confirmingLogout = true;

            setTimeout(() => this.$refs.password.focus(), 250);
        },

        logoutOtherBrowserSessions() {
            this.form.delete(this.route('other-browser-sessions.destroy'), {
                preserveScroll: true,
                onSuccess: () => this.closeModal(),
                onError: () => this.$refs.password.focus(),
                onFinish: () => this.form.reset(),
            });
        },

        closeModal() {
            this.confirmingLogout = false;

            this.form.reset();
        },
    },
}
</script>

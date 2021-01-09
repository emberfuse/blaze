<template>

</template>

<script>
export default {
    data() {
        return {
            confirmingUserDeletion: false,

            form: this.$inertia.form({
                password: null,
            })
        }
    },

    methods: {
        confirmUserDeletion() {
            this.confirmingUserDeletion = true;

            setTimeout(() => this.$refs.password.focus(), 250);
        },

        deleteUser() {
            this.form.delete(this.route('user.destroy'), {
                preserveScroll: true,
                onSuccess: () => this.closeModal(),
                onError: () => this.$refs.password.focus(),
                onFinish: () => this.form.reset(),
            });
        },

        closeModal() {
            this.confirmingUserDeletion = false;

            this.form.reset();
        },
    },
}
</script>

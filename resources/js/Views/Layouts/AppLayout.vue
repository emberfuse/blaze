<template>
    <div class="min-h-screen min-w-full overflow-x-hidden">
        <!-- Main Header Area -->
        <header></header>

        <!-- Main Content Area -->
        <main class="py-4" role="main">
            <div class="container mx-auto px-4 sm:px-6">
                <slot></slot>
            </div>
        </main>

        <!-- Main Footer Area -->
        <footer></footer>

        <!-- Modal Portal -->
        <portal-target name="modal" multiple></portal-target>
    </div>
</template>

<script>
export default {
    methods: {
        async logout() {
            await this.$http
                .post(route('logout'))
                .then(() => this.$inertia.get(route('welcome')))
        }
    }
}
</script>

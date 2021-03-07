<template>
    <div class="min-h-screen min-w-full overflow-x-hidden">
        <!-- Main Content Area -->
        <main class="py-16" role="main">
            <div class="container mx-auto px-4 sm:px-6">
                <div class="md:grid md:grid-cols-12 gap-6">
                    <div class="md:col-span-6 lg:col-span-5 xl:col-span-4">
                        <div>
                            <slot name="title"></slot>
                        </div>

                        <div class="mt-6">
                            <slot name="form"></slot>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
export default {
    //
}
</script>

<template>
    <app-layout>
        <template #title>
            <div>Title goes here.</div>
        </template>

        <template #content>
            <div>Main content goes here.</div>
        </template>
    </app-layout>
</template>

<script>
import AppLayout from '@/Views/Layouts/AppLayout';

export default {
    components: {
        AppLayout,
    },
};
</script>

<template>
    <footer>
        <app-section>
            <slot></slot>
        </app-section>
    </footer>
</template>

<script>
    import AppSection from './Section';

    export default {
        components: {
            AppSection
        }
    }
</script>

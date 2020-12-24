<template>
    <div class="row">
        <form-section-title>
            <template #title><slot name="title"></slot></template>
            <template #description><slot name="description"></slot></template>
        </form-section-title>

        <form-section-content>
            <slot name="content"></slot>
        </form-section-content>
    </div>
</template>

<script>
    import FormSectionTitle from './FormSectionTitle';
    import FormSectionContent from './FormSectionContent';

    export default {
        components: {
            FormSectionTitle,
            FormSectionContent,
        }
    }
</script>

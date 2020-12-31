<template>
    <app-layout>
        <div>
            <update-profile-information-form :user.sync="user"></update-profile-information-form>

            <section-border></section-border>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Views/Layouts/AppLayout.vue';
import SectionBorder from '@/Views/Components/Sections/ActionSectionBorder.vue';
import UpdateProfileInformationForm from './UpdateProfileInformationForm.vue';

export default {
    components: {
        AppLayout,
        SectionBorder,
        UpdateProfileInformationForm
    },

    props: ['user']
}
</script>

<template>
    <app-layout>
        <div>
            <update-profile-information-form :user.sync="$page.props.user"></update-profile-information-form>

            <section-border></section-border>

            <update-password-form></update-password-form>

            <section-border></section-border>

            <two-factor-authentication-form></two-factor-authentication-form>

            <section-border></section-border>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Views/Layouts/AppLayout';
import SectionBorder from '@/Views/Components/Sections/ActionSectionBorder';
import UpdateProfileInformationForm from './UpdateProfileInformationForm';
import UpdatePasswordForm from './UpdatePasswordForm';
import TwoFactorAuthenticationForm from './TwoFactorAuthenticationForm';

export default {
    components: {
        AppLayout,
        SectionBorder,
        UpdateProfileInformationForm,
        UpdatePasswordForm,
        TwoFactorAuthenticationForm
    },
}
</script>

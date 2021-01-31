<template>
    <app-layout>
        <template #title>
            Profile
        </template>

        <template #content>
            <div>
                <update-profile-information-form :user.sync="$page.props.user"></update-profile-information-form>

                <section-border></section-border>

                <update-password-form></update-password-form>

                <section-border></section-border>

                <two-factor-authentication-form></two-factor-authentication-form>

                <section-border></section-border>

                <logout-other-browser-sessions-form :sessions.sync="$page.props.user.sessions"></logout-other-browser-sessions-form>

                <section-border></section-border>

                <delete-user-form></delete-user-form>
            </div>
        </template>
    </app-layout>
</template>

<script>
import AppLayout from '@/Views/Layouts/AppLayout';
import SectionBorder from '@/Views/Components/Sections/ActionSectionBorder';
import UpdateProfileInformationForm from './UpdateProfileInformationForm';
import UpdatePasswordForm from './UpdatePasswordForm';
import TwoFactorAuthenticationForm from './TwoFactorAuthenticationForm';
import DeleteUserForm from './DeleteUserForm';
import LogoutOtherBrowserSessionsForm from './LogoutOtherBrowserSessionsForm';

export default {
    components: {
        AppLayout,
        SectionBorder,
        UpdateProfileInformationForm,
        UpdatePasswordForm,
        TwoFactorAuthenticationForm,
        DeleteUserForm,
        LogoutOtherBrowserSessionsForm,
    },
}
</script>

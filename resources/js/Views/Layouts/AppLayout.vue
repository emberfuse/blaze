<template>
    <main-section>
        <section-header>
            <navbar class="bg-gray-800">
                <template #logo>
                    <logo classes="h-8 w-8" :title="config('app.name')"></logo>
                </template>

                <template #linksleft>
                    <router-link :to="{ name: 'home' }" v-slot="{ href }">
                        <navbar-link class="text-white hover:bg-gray-900 focus:bg-gray-900" :href="href">
                            Dashboard
                        </navbar-link>
                    </router-link>
                </template>

                <template #linksright>
                    <dropdown align="right">
                        <template #trigger>
                            <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300  transition duration-150 ease-in-out">
                                <img :src="user.profile_photo_url" class="rounded-full object-cover w-8 h-8" :alt="user.name"/>
                            </button>
                        </template>

                        <template #items>
                            <dropdown-link href="#">Profile</dropdown-link>
                            <dropdown-link href="#">API token</dropdown-link>
                            <dropdown-link href="#" @clicked="logout">Sign out</dropdown-link>
                        </template>
                    </dropdown>
                </template>
            </navbar>
        </section-header>

        <section-title>
            <slot name="title"></slot>
        </section-title>

        <section-content>
            <slot name="content"></slot>
        </section-content>

        <section-footer>
            <div class="text-center">
                <span class="text-gray-500 text-xs">{{ copyright }}</span>
            </div>
        </section-footer>
    </main-section>
</template>

<script>
import Logo from '@/Views/Components/Logos/Logo';
import Navbar from '@/Views/Components/Navbars/Navbar';
import NavbarLink from '@/Views/Components/Navbars/NavbarLink';
import Dropdown from '@/Views/Components/Dropdowns/Dropdown';
import DropdownLink from '@/Views/Components/Dropdowns/DropdownLink';
import MainSection from '@/Views/Components/Sections/MainSection';
import SectionTitle from '@/Views/Components/Sections/SectionTitle';
import SectionContent from '@/Views/Components/Sections/SectionContent';
import SectionHeader from '@/Views/Components/Sections/SectionHeader';
import SectionFooter from '@/Views/Components/Sections/SectionFooter';

export default {
    components: {
        Logo,
        Navbar,
        NavbarLink,
        Dropdown,
        DropdownLink,
        MainSection,
        SectionTitle,
        SectionContent,
        SectionHeader,
        SectionFooter,
    },

    computed: {
        user() {
            return this.$store.getters.user;
        },
    },

    data() {
        return {
            copyright: `© ${new Date().getFullYear()} ${this.config(
                'app.name'
            )}. All rights reserved.`,
        };
    },

    methods: {
        async logout() {
            await this.$store.dispatch('logout').then(() => {
                this.$router.push({ name: 'login' });
            });
        },
    },
};
</script>

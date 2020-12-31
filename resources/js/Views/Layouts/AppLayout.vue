<template>
    <div class="min-h-screen min-w-full overflow-x-hidden">
        <!-- Main Header Area -->
        <header>
            <navbar class="bg-gray-800">
                <template #logo>
                    <!-- <logo classes="h-8 w-8" :title="config('app.name')"></logo> -->
                </template>

                <template #linksleft>
                    <navbar-link :href="route('home')" :active="route().current('home')" class="text-white hover:bg-gray-900 focus:bg-gray-900">
                        Dashboard
                    </navbar-link>
                    <navbar-link :href="route('home')" :active="false" class="text-white hover:bg-gray-900 focus:bg-gray-900">
                        Projects
                    </navbar-link>
                    <navbar-link :href="route('home')" :active="false" class="text-white hover:bg-gray-900 focus:bg-gray-900">
                        Issues
                    </navbar-link>
                    <navbar-link :href="route('home')" :active="false" class="text-white hover:bg-gray-900 focus:bg-gray-900">
                        Support
                    </navbar-link>
                </template>

                <template #linksright>
                    <dropdown align="right">
                        <template #trigger>
                            <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300  transition duration-150 ease-in-out">
                                <img :src="$page.props.user.profile_photo_url" class="rounded-full object-cover w-8 h-8" :alt="$page.props.user.name"/>
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
        </header>

        <!-- Main Content Area -->
        <main class="py-4" role="main">
            <div class="container mx-auto px-4 sm:px-6">
                <slot></slot>
            </div>
        </main>

        <!-- Main Footer Area -->
        <footer>
            <div class="text-center">
                <span class="text-gray-500 text-xs">{{ copyright }}</span>
            </div>
        </footer>

        <!-- Modal Portal -->
        <portal-target name="modal" multiple></portal-target>
    </div>
</template>

<script>
import Navbar from '@/Views/Components/Navbars/Navbar';
import NavbarLink from '@/Views/Components/Navbars/NavbarLink';
import Dropdown from '@/Views/Components/Dropdowns/Dropdown';
import DropdownLink from '@/Views/Components/Dropdowns/DropdownLink';

export default {
    components: {
        Navbar,
        NavbarLink,
        Dropdown,
        DropdownLink,
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
            await this.$http
                .post(route('login.destroy'))
                .then(() => this.$inertia.get(route('welcome')))
        }
    }
}
</script>

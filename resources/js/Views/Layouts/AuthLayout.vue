<template>
    <main-section>
        <section-content class="py-16">
            <div class="md:grid md:grid-cols-3 gap-6">
                <div class="md:col-span-2 lg:col-span-1">
                    <div>
                        <slot name="title"></slot>
                    </div>

                    <div>
                        <slot name="content"></slot>
                    </div>
                </div>
            </div>
        </section-content>
    </main-section>
</template>

<script>
import LogoLight from '@/Views/Components/Logos/LogoLight';
import Navbar from '@/Views/Components/Navbars/Navbar';
import NavbarLink from '@/Views/Components/Navbars/NavbarLink';
import Dropdown from '@/Views/Components/Dropdowns/Dropdown';
import DropdownLink from '@/Views/Components/Dropdowns/DropdownLink';
import MainSection from '@/Views/Components/Sections/MainSection';
import SectionContent from '@/Views/Components/Sections/SectionContent';

export default {
    components: {
        LogoLight,
        Navbar,
        NavbarLink,
        Dropdown,
        DropdownLink,
        MainSection,
        SectionContent,
    },
};
</script>

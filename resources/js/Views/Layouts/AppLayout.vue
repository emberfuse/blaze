<template>
    <main-section>
        <section-header>
            <slot name="header"></slot>
        </section-header>

        <section-title>
            <slot name="title"></slot>
        </section-title>

        <section-content>
            <slot name="content"></slot>
        </section-content>

        <section-footer>
            <slot name="footer"></slot>
        </section-footer>
    </main-section>
</template>

<script>
import LogoLight from "@/Views/Components/Logos/LogoLight";
import Navbar from "@/Views/Components/Navbars/Navbar";
import NavbarLink from "@/Views/Components/Navbars/NavbarLink";
import Dropdown from "@/Views/Components/Dropdowns/Dropdown";
import DropdownLink from "@/Views/Components/Dropdowns/DropdownLink";
import MainSection from "@/Views/Components/Sections/MainSection";
import SectionTitle from "@/Views/Components/Sections/SectionTitle";
import SectionContent from "@/Views/Components/Sections/SectionContent";
import SectionHeader from "@/Views/Components/Sections/SectionHeader";
import SectionFooter from "@/Views/Components/Sections/SectionFooter";

export default {
    components: {
        LogoLight,
        Navbar,
        NavbarLink,
        Dropdown,
        DropdownLink,
        MainSection,
        SectionTitle,
        SectionContent,
        SectionHeader,
        SectionFooter
    }
};
</script>

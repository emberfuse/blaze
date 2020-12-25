<template>
    <a @click="$emit('clicked')" class="px-3 py-1.5 rounded-xl hover:bg-opacity-75 focus:bg-opacity-100 opacity-75 hover:opacity-100 focus:outline-none transition ease-in-out duration-150 ml-2">
        <span class="font-semibold text-sm">
            <!-- Link Text -->
            <slot></slot>
        </span>
    </a>
</template>

<script>
    export default {
        //
    }
</script>

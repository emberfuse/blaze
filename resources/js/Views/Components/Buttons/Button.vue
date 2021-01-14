<template>
    <button @click="$emit('clicked')" :disabled="loading" class="inline-flex shadow items-center whitespace-no-wrap px-4 py-2 border border-transparent rounded-xl  focus:outline-none focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150" :class="colors">
        <svg v-show="loading" class="inline animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>

        <span class="font-semibold text-sm">
            <slot></slot>
        </span>
    </button>
</template>

<script>
    export default {
        props: ['mode', 'loading'],

        computed: {
            colors() {
                if (this.mode === 'primary') {
                    return 'text-white hover:text-white focus:text-white active:text-white bg-blue-500 hover:bg-blue-600 focus:bg-blue-600 active:bg-blue-600';
                } else if (this.mode === 'danger') {
                    return 'text-white hover:text-white focus:text-white active:text-white bg-red-500 hover:bg-red-600 focus:bg-red-600 active:bg-red-600';
                } else {
                    return 'text-gray-500 hover:text-gray-600 focus:text-gray-600 active:text-gray-600 bg-white hover:bg-white focus:bg-white active:bg-white border border-gray-100';
                }
            }
        }
    }
</script>

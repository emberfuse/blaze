<template>
    <label :for="id" class="flex items-center cursor-pointer">
        <input :id="id" class="form-checkbox rounded text-blue-500 bg-white border border-gray-100 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 transition duration-150 ease-in-out" v-model="value" @input="$emit('input', ! value)" ref="checkbox" type="checkbox"/>

        <span class="ml-2 text-sm font-medium leading-5">
            <span v-text="label"></span>
        </span>
    </label>
</template>

<script>
    export default {
        props: {
            label: String,
            value: Boolean,
            id: String
        },
    }
</script>

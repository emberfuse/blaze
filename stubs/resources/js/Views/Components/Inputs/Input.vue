<template>
    <div>
        <input-label :text="label">
            <template>
                <input autocomplete :type="type" class="form-input px-4 py-2 mt-1 block w-full rounded-lg bg-white border border-gray-200 focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 placeholder-gray-400 transition ease-in-out duration-150" :value="value" @input="$emit('input', $event.target.value)" ref="input" :placeholder="placeholder ? placeholder : label">
            </template>
        </input-label>

        <input-error :message="error"></input-error>
    </div>
</template>

<script>
    import InputLabel from './InputLabel';
    import InputError from './InputError';

    export default {
        props: [
            'error',
            'value',
            'type',
            'label',
            'placeholder'
        ],

        components: {
            InputLabel,
            InputError,
        },

        methods: {
            focus() {
                this.$refs.input.focus()
            }
        }
    }
</script>


<template>
    <div>
        <button @click="makeRequest">Make request</button>
    </div>
</template>

<script>
export default {
    methods: {
        makeRequest() {
            this.$http.get('/test')
                .then((response) => {
                    console.log(response);
                });
        }
    }
}
</script>

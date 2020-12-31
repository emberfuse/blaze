<script>
    export default {
        props: ['url'],

        created() {
            if (this.url) {
                this.fetchData(this.url);
            }
        },

        data() {
            return {
                data: null
            }
        },

        render() {
            return this.$scopedSlots.default({
                data: this.data
            });
        },

        methods: {
            async fetchData(url) {
                await axios.get(url).then(({ data }) => this.data = data);
            }
        }
    }
</script>

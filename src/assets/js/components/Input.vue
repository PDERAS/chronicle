<template>
    <div class="chronicle-input">
        <textarea @input="$emit('input', description)" v-model="description"></textarea>
    </div>
</template>

<script>
    export default {
        name: 'chronicle-input',

        props: {
            value: {
                validator: (val) =>  { return val === null || typeof val === 'string' },
                required: true
            }
        },

        data: () => ({
            description: null
        }),

        mounted() {
            this.description = this.value;
        }
    }
</script>

<style lang="scss">
    .chronicle-input {
        textarea {
            width: 100%;
            padding: 0;
            resize: none;
            min-height: 50px;
        }
    }
</style>

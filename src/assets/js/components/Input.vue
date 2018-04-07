<template>
    <div class="chronicle-input">
        <textarea placeholder="Enter your note..." @input="$emit('input', description)" v-model="description" :rows="line_breaks + 1" />
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
            description: null,
            line_breaks: 0,
        }),

        watch: {
            description(val) {
                this.line_breaks = (val.match(/\n/g)||[]).length;
            }
        },

        mounted() {
            this.description = this.value;
        }
    }
</script>

<style lang="scss">
    .chronicle-input {
        textarea {
            width: 100%;
            padding: 10px;
            resize: none;
            min-height: 50px;
            box-sizing: border-box;
            outline: none;
        }
    }
</style>

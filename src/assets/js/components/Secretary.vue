<template>
    <div id="secretary" class="secretary">
        <display-controller v-if="showDisplay" />
        <input-controller v-if="showInput" />
    </div>
</template>

<script>
    import DisplayController from './displays/DisplayController';
    import InputController from './inputs/InputController';

    export default {
        name: 'Secretary',

        components: {
            DisplayController,
            InputController
        },

        props: {
            tag: {
                type: String,
                required: true
            },

            refSlug: {
                type: String,
                required: true
            },

            showDisplay: {
                type: Boolean,
                default: true
            },

            showInput: {
                type: Boolean,
                default: true
            }
        },

        created() {
            axios.get('/section', params: { tag: this.tag });
        }
    }

</script>

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
            section: {
                type: String|Number,
                required: true,
                validator: val => !isNaN(val)
            },

            sectionRefId: {
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
            this.$store.dispatch('sections/get', section);
        }
    }

</script>

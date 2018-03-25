<template>
    <div id="chronicle" class="chronicle">
        <template v-if="section">
            <template v-if="showDisplay">
                <chronicle-display :ref-slug="refSlug" :section="section" />
            </template>
            <template v-if="showInput">
                <chronicle-input :ref-slug="refSlug" :section="section" />
            </template>
        </template>
    </div>
</template>

<script>
    import ChronicleDisplay from './Display';
    import ChronicleInput from './Input';

    export default {
        name: 'Chronicle',

        components: {
            ChronicleDisplay,
            ChronicleInput
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

        data: () => ({
            section: null
        }),

        created() {
            axios.get('/sections/' + this.tag).then(r => {
                this.section = r.data.section;
            });
        }
    }
</script>

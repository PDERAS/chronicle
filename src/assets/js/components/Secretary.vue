<template>
    <div id="secretary" class="secretary">
        <template v-if="section">
            <template v-if="showDisplay">
                <secretary-display ref-slug="refSlug" :section="section" />
            </template>
            <template v-if="showInput">
                <secretary-input ref-slug="refSlug" :section="section" />
            </template>
        </template>
    </div>
</template>

<script>
    import SecretaryDisplay from './Display';
    import SecretaryInput from './Input';

    export default {
        name: 'Secretary',

        components: {
            SecretaryDisplay,
            SecretaryInput
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

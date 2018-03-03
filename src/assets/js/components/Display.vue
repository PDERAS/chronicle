<template>
    <div class="secretary-display">
    </div>
</template>

<script>
    export default {
        name: 'secretary-display',

        props: {
            refSlug: {
                type: String,
                required: true
            },

            section: {
                type: Object,
                required: true
            }
        },

        data: () => ({
            notes: []
        }),

        created() {
            var url = '/sections/' + this.section.tag + '/notes';
            var config = {
                params: {
                    slug: this.refSlug
                }
            };
            axios.get(url, config).then(r => {
                this.notes = r.data.notes;
            });
        }
    }
</script>

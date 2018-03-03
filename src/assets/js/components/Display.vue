<template>
    <div class="secretary-display">
        <div class="note-wrapper" v-for="note in notes">
            <div>{{ note.description }}</div>
            <div>{{ note.created_at }}</div>
        </div>
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
            this.getNotes();
        },

        methods: {
            getNotes() {
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
    }
</script>

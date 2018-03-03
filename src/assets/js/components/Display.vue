<template>
    <div class="secretary-display">
        <div class="secretary-header">
            <div class="secretary-header-">
                {{ section.title }}
            </div>
            <div class="secretary-header-btns">

            </div>
        </div>
        <div class="secretary-content">
            <div class="secretary-note-wrapper" v-for="note in notes">
                <div class="secretary-note-header">
                    <span>{{ note.user.name }}</span>
                    <span>{{ note.created_at }}</span>
                </div>
                <div class="secretary-note-content">
                    <div>{{ note.description }}</div>
                </div>
                <div class="secretary-note-btns">

                </div>
            </div>
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

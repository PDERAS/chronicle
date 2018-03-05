<template>
    <div class="secretary-display">
        <div class="secretary-header">
            <div class="secretary-header">
                {{ section.title }}
            </div>
            <div class="secretary-header-btns">
                <button>R</button>
                <button>F</button>
                <button>B</button>
                <button>N</button>
                <button>L</button>
            </div>
        </div>
        <div class="secretary-content">
            <div class="secretary-note-wrapper" v-for="note in notes">
                <div class="secretary-note-header">
                    <span class="secretary-note-user">{{ note.user.name }}</span>
                    <span class="secretary-note-time">{{ note.created_at }}</span>
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
                var url = '/sections/' + this.tag + '/notes';
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

<style lang="scss">
    .secretary-header {
        display: inline-block
        font-family: Helvetica;
        font-size: large;
        padding: 5px;
    }

    .secretary-header-btns {
        display: inline-block;
        text-align: right;
    }

    .secretary-display {
        font-family: Helvetica, sans-serif;
    }

    .secretary-note-wrapper {
        padding: 10px 5px;
    }

    .secretary-note-time {
        color: lighten(black, 60);
        font-size: small;
    }

    .secretary-note-user {
        color: black;
        font-size: medium;
        font-weight: bold;
    }
</style>

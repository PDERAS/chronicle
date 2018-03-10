<template>
    <div class="secretary-display">
        <div class="secretary-header">
            <div class="secretary-header-btns">
                <button class="secretary-header-btn">
                    <i class="fas fa-sync" v-if="useFontAwesome" />
                    <div class="x-small" v-else>Refresh</div>
                </button>
                <button class="secretary-header-btn">
                    <i class="fas fa-arrow-left" v-if="useFontAwesome" />
                    <div class="x-small" v-else>First</div>
                </button>
                <button class="secretary-header-btn">
                    <i class="fas fa-angle-left" v-if="useFontAwesome" />
                    <div class="x-small" v-else>Previous</div>
                </button>
                <button class="secretary-header-btn">
                    <i class="fas fa-angle-right" v-if="useFontAwesome" />
                    <div class="x-small" v-else>Next</div>
                </button>
                <button class="secretary-header-btn">
                    <i class="fas fa-arrow-right" v-if="useFontAwesome" />
                    <div class="x-small" v-else>Last</div>
                </button>
            </div>
            <div class="secretary-header-title">
                {{ section.title }}
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
            },

            useFontAwesome: {
                type: Boolean,
                default: true
            }
        },

        data: () => ({
            notes: []
        }),

        created() {
            this.getNotes();
        },

        mounted() {
            if (this.useFontAwesome) {
                let fontAwesomeScript = document.createElement('script');
                fontAwesomeScript.setAttribute('src', 'https://use.fontawesome.com/releases/v5.0.8/js/all.js');
                fontAwesomeScript.setAttribute('defer', '');
                document.head.appendChild(fontAwesomeScript);
            }
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

<style lang="scss">
    .secretary-content {
        border-bottom: solid thin black;
        border-top: solid thin black;
    }

    .secretary-header-btns {
        float: right;
        padding: 5px;
    }

    .secretary-header-btns {
        -webkit-appearance: none;
        border: none;
        cursor: pointer;

        &:focus {
            outline: none;
        }
    }

    .secretary-header-title {
        font-size: large;
        padding: 5px;
    }

    .secretary-display {
        font-family: Helvetica, sans-serif;
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

    .secretary-note-wrapper {
        border-bottom: solid thin lighten(black, 60);
        padding: 10px 5px;

        &:last-child {
            border-bottom: none;
        }

        &:hover {
            background: lighten(black, 95);
        }
    }

    .secretary-header-btn {
        background: transparent;
        border: none;
        cursor: pointer;
        font-size: 15px;

        &:focus {
            outline: none;
        }

        &:active {
            color: lighten(black, 50);
        }
    }

    .x-small {
        font-size: x-small;
    }
</style>

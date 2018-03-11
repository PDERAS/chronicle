<template>
    <div class="secretary-display">
        <div class="secretary-header">
            <div class="secretary-header-btns">
                <button class="secretary-btn header" @click="getNotes(currentPage)"  >
                    <i class="fas fa-sync" v-if="useFontAwesome" />
                    <div class="x-small" v-else>Refresh</div>
                </button>
                <button class="secretary-btn header" :disabled="currentPage == firstPage" @click="getNotes(firstPage)">
                    <i class="fas fa-arrow-left" v-if="useFontAwesome" />
                    <div class="x-small" v-else>First</div>
                </button>
                <button class="secretary-btn header" :disabled="!previousPage" @click="getNotes(previousPage)">
                    <i class="fas fa-angle-left" v-if="useFontAwesome" />
                    <div class="x-small" v-else>Previous</div>
                </button>
                <button class="secretary-btn header" :disabled="!nextPage" @click="getNotes(nextPage)">
                    <i class="fas fa-angle-right" v-if="useFontAwesome" />
                    <div class="x-small" v-else>Next</div>
                </button>
                <button class="secretary-btn header" :disabled="currentPage == lastPage" @click="getNotes(lastPage)">
                    <i class="fas fa-arrow-right" v-if="useFontAwesome" />
                    <div class="x-small" v-else>Last</div>
                </button>
            </div>
            <div class="secretary-header-title" :class="{ 'alt-title': !showTitle }">
                <template v-if="showTitle">
                    {{ section.title }}
                </template>
                <template v-else>
                    Showing {{ startEntry }} to {{ endEntry }} of {{ totalEntries }}
                </template>
            </div>
        </div>
        <div class="secretary-content">
            <div class="secretary-note-wrapper" v-for="note in notes" @mouseover="showActionBar(note.id)" @mouseleave="hideActionBar">
                <div class="secretary-note-header">
                    <span class="secretary-note-user">{{ note.user.name }}</span>
                    <span class="secretary-note-time">{{ formatDate(createDate(note.created_at)) }}</span>
                </div>
                <div class="secretary-note-content">
                    <div>{{ note.description }}</div>
                </div>

                <div class="secretary-action-bar" v-if="actionBarId == note.id">
                    <button class="secretary-btn action">
                        <i class="fas fa-info" v-if="useFontAwesome" />
                        <div class="x-small" v-else>Info</div>
                    </button>
                    <button class="secretary-btn action">
                        <i class="fas fa-comments" v-if="useFontAwesome" />
                        <div class="x-small" v-else>Comments</div>
                    </button>
                    <button class="secretary-btn action">
                        <i class="fas fa-edit" v-if="useFontAwesome" />
                        <div class="x-small" v-else>Edit</div>
                    </button>
                    <button class="secretary-btn action">
                        <i class="fas fa-trash" v-if="useFontAwesome" />
                        <div class="x-small" v-else>Delete</div>
                    </button>
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

            showTitle: {
                type: Boolean,
                default: true
            },

            useFontAwesome: {
                type: Boolean,
                default: true
            }
        },

        data: () => ({
            actionBarId: null,

            currentPage: null,
            firstPage: null,
            lastPage: null,
            nextPage: null,
            previousPage: null,

            startEntry: 0,
            endEntry: 0,
            totalEntries: 0,

            fontAwesomeUrl: 'https://use.fontawesome.com/releases/v5.0.8/js/all.js',
            notes: []
        }),

        created() {
            this.getNotes(1);
        },

        mounted() {
            if (this.useFontAwesome && !this.isFontAwesomeLoaded()) {
                this.loadFontAwesome();
            }
        },

        methods: {
            createDate(timestamp) {
                if (!timestamp.match(/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01]) ([01][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/)) {
                    throw new Error("Format must be: YYYY-mm-dd hh-mm-ss");
                }

                var a = timestamp.split(" ");
                var d = a[0].split("-");
                var t = a[1].split(":");

                return new Date(Date.UTC(d[0],(d[1]),d[2],t[0],t[1],t[2]));
            },

            formatDate(date) {
                var months = [
                    "January",
                    "February",
                    "March",
                    "April",
                    "May",
                    "June",
                    "July",
                    "August",
                    "September",
                    "October",
                    "November",
                    "December"
                ];

                var returnVal = months[date.getMonth()] + ' ' + date.getDate() + ', ' + date.getFullYear();

                var hours = date.getHours() % 12 == 0 ? 12 : date.getHours() % 12;
                var mins = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
                var period = date.getHours() >= 12 ? 'PM' : 'AM';

                returnVal += ' @ ' + hours + ':' + mins + ' ' + period;

                return returnVal;
            },

            getNotes(page) {
                var url = '/sections/' + this.section.tag + '/notes';
                var config = {
                    params: {
                        page: page,
                        slug: this.refSlug
                    }
                };
                axios.get(url, config).then(r => {
                    this.firstPage = 1;
                    this.lastPage = r.data.notes.last_page;
                    this.currentPage = r.data.notes.current_page;

                    this.nextPage = this.currentPage < this.lastPage ? this.currentPage + 1: null;
                    this.previousPage = this.currentPage > this.firstPage ? this.currentPage - 1 : null;

                    this.startEntry = r.data.notes.from;
                    this.endEntry = r.data.notes.to;
                    this.totalEntries = r.data.notes.total;

                    this.notes = r.data.notes.data;
                });
            },

            hideActionBar(id) {
                this.actionBarId = null;
            },

            isFontAwesomeLoaded() {
                var scripts = document.getElementsByTagName('script');

                for (var i = 0; i < scripts.length; i++) {
                    if (scripts[i].src == this.fontAwesomeUrl) {
                        return true;
                    }
                }
                return false;
            },

            loadFontAwesome() {
                let fontAwesomeScript = document.createElement('script');
                fontAwesomeScript.setAttribute('src', this.fontAwesomeUrl);
                fontAwesomeScript.setAttribute('defer', '');
                document.head.appendChild(fontAwesomeScript);
            },

            showActionBar(id) {
                this.actionBarId = id;
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
        cursor: pointer;

        &:focus {
            outline: none;
        }
    }

    .secretary-header-title {
        font-size: large;
        padding: 5px;

        &.alt-title {
            font-size: small;
            padding: 10px 5px;
            color: lighten(black, 70);
        }
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
        position: relative;

        &:last-child {
            border-bottom: none;
        }

        &:hover {
            background: lighten(black, 95);
        }
    }

    .secretary-btn {
        background: transparent;
        border: none;
        cursor: pointer;

        &.action {
            color: lighten(black, 60);

            &:active, &:hover {
                color: lighten(black, 80);
            }
        }

        &.header {
            font-size: 15px;
        }

        &:disabled {
            cursor: not-allowed;
        }

        &:focus {
            outline: none;
        }

        &:active, &:hover {
            color: lighten(black, 50);
        }
    }

    .secretary-action-bar {
        border: solid thin lighten(black, 60);
        border-radius: 5px;
        position: absolute;
        top: 3px;
        right: 5px;

        .secretary-btn {
            border-right: solid thin lighten(black, 60);
            min-width: 25px;
            padding: 0 5px;
            text-align: center;
            width: auto;

            &:last-child {
                border-right: none;
            }
        }
    }

    .x-small {
        font-size: x-small;
    }
</style>

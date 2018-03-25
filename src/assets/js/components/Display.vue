<template>
    <div class="chronicle-display">
        <div class="chronicle-header">
            <div class="chronicle-header-btns">
                <button class="chronicle-btn header" @click="getNotes(currentPage)"  >
                    <i class="fas fa-sync" v-if="useFontAwesome" />
                    <div class="x-small" v-else>Refresh</div>
                </button>
                <button class="chronicle-btn header" :disabled="currentPage == firstPage" @click="getNotes(firstPage)">
                    <i class="fas fa-arrow-left" v-if="useFontAwesome" />
                    <div class="x-small" v-else>First</div>
                </button>
                <button class="chronicle-btn header" :disabled="!previousPage" @click="getNotes(previousPage)">
                    <i class="fas fa-angle-left" v-if="useFontAwesome" />
                    <div class="x-small" v-else>Previous</div>
                </button>
                <button class="chronicle-btn header" :disabled="!nextPage" @click="getNotes(nextPage)">
                    <i class="fas fa-angle-right" v-if="useFontAwesome" />
                    <div class="x-small" v-else>Next</div>
                </button>
                <button class="chronicle-btn header" :disabled="currentPage == lastPage" @click="getNotes(lastPage)">
                    <i class="fas fa-arrow-right" v-if="useFontAwesome" />
                    <div class="x-small" v-else>Last</div>
                </button>
            </div>
            <div class="chronicle-header-title" :class="{ 'alt-title': !showTitle }">
                <template v-if="showTitle">
                    {{ section.title }}
                </template>
                <template v-else>
                    Showing {{ startEntry }} to {{ endEntry }} of {{ totalEntries }}
                </template>
            </div>
        </div>
        <div class="chronicle-content">
            <div class="chronicle-note-wrapper" v-for="note in notes" @mouseover="showActionBar(note)" @mouseleave="hideActionBar">
                <div class="chronicle-note-header">
                    <span class="chronicle-note-user">{{ note.user.name }}</span>
                    <span class="chronicle-note-time">{{ formatDate(createDate(note.created_at)) }}</span>
                </div>
                <div class="chronicle-note-content">
                    <div class="chronicle-note-description">{{ note.description }}</div>
                </div>

                <div class="chronicle-action-bar" v-if="actionBarId == note.id">
                    <button class="chronicle-btn action" @click="openModal('view', note)">
                        <i class="fas fa-info" v-if="useFontAwesome" />
                        <div class="x-small" v-else>Info</div>
                    </button>
                    <button class="chronicle-btn action">
                        <i class="fas fa-comments" v-if="useFontAwesome" />
                        <div class="x-small" v-else>Comments</div>
                    </button>
                    <button class="chronicle-btn action">
                        <i class="fas fa-edit" v-if="useFontAwesome" />
                        <div class="x-small" v-else>Edit</div>
                    </button>
                    <button class="chronicle-btn action" @click="destroy(note)">
                        <i class="fas fa-trash" v-if="useFontAwesome" />
                        <div class="x-small" v-else>Delete</div>
                    </button>
                </div>
            </div>
        </div>

        <chronicle-modal :action="modalAction" :note="modalNote" :ref-slug="refSlug" v-if="showModal" @close-modal="closeModal" />
    </div>
</template>

<script>
    import ChronicleModal from './Modal';

    export default {
        name: 'chronicle-display',

        components: {
            ChronicleModal
        },

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
            notes: [],

            modalAction: 'view',
            modalNote: null,
            showModal: false,
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

            closeModal() {
                this.modalNote = null;
                this.showModal = false;
            },

            destroy(note) {
                var url = '/notes/' + note.id;

                axios.delete(url).then(r => {
                    this.getNotes(this.currentPage);
                });
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

            openModal(action, note) {
                this.modalNote = note;
                this.modalAction = action;
                this.showModal = true;
            },

            showActionBar(note) {
                this.actionBarId = note.id;
            }
        }
    }
</script>

<style lang="scss">
    .chronicle-content {
        border-bottom: solid thin black;
        border-top: solid thin black;
    }

    .chronicle-header-btns {
        float: right;
        padding: 5px;
    }

    .chronicle-header-btns {
        cursor: pointer;

        &:focus {
            outline: none;
        }
    }

    .chronicle-header-title {
        font-size: large;
        padding: 5px;

        &.alt-title {
            font-size: small;
            padding: 10px 5px;
            color: lighten(black, 70);
        }
    }

    .chronicle-display {
        font-family: Helvetica, sans-serif;
    }

    .chronicle-note-description {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .chronicle-note-time {
        color: lighten(black, 60);
        font-size: small;
    }

    .chronicle-note-user {
        color: black;
        font-size: medium;
        font-weight: bold;
    }

    .chronicle-note-wrapper {
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

    .chronicle-btn {
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

    .chronicle-action-bar {
        border: solid thin lighten(black, 60);
        border-radius: 5px;
        position: absolute;
        top: 3px;
        right: 5px;

        .chronicle-btn {
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

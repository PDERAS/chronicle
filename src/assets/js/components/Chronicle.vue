<template>
    <div id="chronicle" class="chronicle">
        <template v-if="section">
            <template v-if="showDisplay">
                <div class="chronicle-display">
                    <div class="chronicle-header">
                        <div class="chronicle-header-btns">
                            <button class="chronicle-btn header" @click="getNotes(currentPage)"  >
                                <i class="far fas fa-sync" v-if="useFontAwesome" />
                                <div class="x-small" v-else>Refresh</div>
                            </button>
                            <button class="chronicle-btn header" :disabled="currentPage == firstPage" @click="getNotes(firstPage)">
                                <i class="far fas fa-arrow-left" v-if="useFontAwesome" />
                                <div class="x-small" v-else>First</div>
                            </button>
                            <button class="chronicle-btn header" :disabled="!previousPage" @click="getNotes(previousPage)">
                                <i class="far fas fa-angle-left" v-if="useFontAwesome" />
                                <div class="x-small" v-else>Previous</div>
                            </button>
                            <button class="chronicle-btn header" :disabled="!nextPage" @click="getNotes(nextPage)">
                                <i class="far fas fa-angle-right" v-if="useFontAwesome" />
                                <div class="x-small" v-else>Next</div>
                            </button>
                            <button class="chronicle-btn header" :disabled="currentPage == lastPage" @click="getNotes(lastPage)">
                                <i class="far fas fa-arrow-right" v-if="useFontAwesome" />
                                <div class="x-small" v-else>Last</div>
                            </button>
                        </div>
                        <div class="chronicle-header-title" :class="{ 'alt-title': !showTitle }">
                            <template v-if="showTitle">
                                {{ section.title }}
                            </template>
                            <template v-else-if="showPages">
                                Showing {{ startEntry }} to {{ endEntry }} of {{ totalEntries }}
                            </template>
                            <template v-else>
                                &nbsp;
                            </template>
                        </div>
                    </div>

                    <div class="chronicle-content" v-if="notes.length > 0">
                        <chronicle-note v-for="note in notes"
                                        :key="note.id"
                                        :note="note"
                                        :ref-slug="refSlug"
                                        :section="section"
                                        :use-font-awesome="useFontAwesome"
                                        :user="user"
                                        @open-modal="openModal" />
                    </div>
                    <div class="chronicle-content error" v-else>--- No notes could be found ---</div>
                </div>
            </template>

            <template v-if="showInput && user">
                <div class="chronicle-footer">
                    <button class="chronicle-btn block" @click="openModal('add')">Add Note</button>
                </div>
            </template>

            <chronicle-modal :action="modalAction"
                             :note="modalNote"
                             :ref-slug="refSlug"
                             :section="section"
                             @get-notes="getNotes(currentPage)"
                             @close-modal="closeModal"
                             v-if="showModal" />
        </template>
    </div>
</template>

<script>
    import ChronicleModal from './Modal';
    import ChronicleNote from './Note';

    export default {
        name: 'Chronicle',

        components: {
            ChronicleModal,
            ChronicleNote
        },

        props: {
            loadFontAwesome: {
                type: Boolean,
                default: true
            },

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
            },

            showPages: {
                type: Boolean,
                default: true
            },

            showTitle: {
                type: Boolean,
                default: true
            },

            useFontAwesome: {
                type: Boolean,
                default: true
            },

            user: {
                validator: val => (val === null || typeof val === 'object') && !Array.isArray(val),
                default: _ => new Object()
            }
        },

        data: () => ({
            section: null,

            fontAwesomeUrl: 'https://use.fontawesome.com/releases/v5.0.8/js/all.js',

            currentPage: null,
            firstPage: null,
            lastPage: null,
            nextPage: null,
            previousPage: null,

            startEntry: 0,
            endEntry: 0,
            totalEntries: 0,

            notes: [],

            modalAction: 'view',
            modalNote: null,
            showModal: false,
        }),

        created() {
            if (this.loadFontAwesome && this.useFontAwesome && !this.isFontAwesomeLoaded()) {
                this.initFontAwesome();
            }
            axios.get('/sections/' + this.tag).then(r => {
                this.section = r.data.section;

                this.getNotes(1);
            });
        },

        methods: {
            closeModal() {
                this.modalNote = null;
                this.showModal = false;
            },

            initFontAwesome() {
                let fontAwesomeScript = document.createElement('script');
                fontAwesomeScript.setAttribute('src', this.fontAwesomeUrl);
                fontAwesomeScript.setAttribute('defer', '');
                document.head.appendChild(fontAwesomeScript);
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

                    this.startEntry = r.data.notes.from ? r.data.notes.from : 0;
                    this.endEntry = r.data.notes.to ? r.data.notes.to : 0;
                    this.totalEntries = r.data.notes.total ? r.data.notes.total : 0;

                    this.notes = r.data.notes.data;
                });
            },

            openModal(action, note = null) {
                this.modalAction = action;
                this.modalNote = note;
                this.showModal = true;
            },
        }
    }
</script>

<style lang="scss">
    .chronicle-content {
        border-bottom: solid thin black;
        border-top: solid thin black;

        &.error {
            padding: 10px 5px;
            text-align: center;
            color: #4e4e4e;
        }
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

    .chronicle {
        font-family: Helvetica, sans-serif;
    }

    .chronicle-footer {
        margin: 5px auto;
        text-align: right;
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

        &.block {
            background: #3f3f3f;
            padding: 5px 10px;
            color: white;
            font-size: 14px;

            -webkit-appearance: none;
            -webkit-border-radius:5px;
            -moz-border-radius: 5px;
            border-radius: 5px;

            &:hover {
                background: #4e4e4e;
                cursor: pointer;
                color: white;

                -webkit-box-shadow: 1px 1px 5px #999999;
                -moz-box-shadow: 1px 1px 5px #999999;
                box-shadow: 1px 1px 5px #999999;
            }
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

    .x-small {
        font-size: x-small;
    }
</style>

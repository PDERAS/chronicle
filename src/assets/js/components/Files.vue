<template>
    <div id="chronicle" class="chronicle">
        <div class="chronicle-display">
            <div class="chronicle-header">
                <div class="chronicle-header-btns">
                    <button class="chronicle-btn header" @click="getFiles(currentPage)"  >
                        <i class="far fas fa-sync" v-if="useFontAwesome" />
                        <div class="x-small" v-else>Refresh</div>
                    </button>
                    <button class="chronicle-btn header" :disabled="currentPage == firstPage" @click="getNotes(firstPage)">
                        <i class="far fas fa-arrow-left" v-if="useFontAwesome" />
                        <div class="x-small" v-else>First</div>
                    </button>
                    <button class="chronicle-btn header" :disabled="!previousPage" @click="getFiles(previousPage)">
                        <i class="far fas fa-angle-left" v-if="useFontAwesome" />
                        <div class="x-small" v-else>Previous</div>
                    </button>
                    <button class="chronicle-btn header" :disabled="!nextPage" @click="getFiles(nextPage)">
                        <i class="far fas fa-angle-right" v-if="useFontAwesome" />
                        <div class="x-small" v-else>Next</div>
                    </button>
                    <button class="chronicle-btn header" :disabled="currentPage == lastPage" @click="getFiles(lastPage)">
                        <i class="far fas fa-arrow-right" v-if="useFontAwesome" />
                        <div class="x-small" v-else>Last</div>
                    </button>
                </div>
                <div class="chronicle-header-title" :class="{ 'alt-title': showPages }">
                    <template v-if="showPages">
                        Showing {{ startEntry }} to {{ endEntry }} of {{ totalEntries }}
                    </template>
                    <template v-else>
                        &nbsp;
                    </template>
                </div>
            </div>

            <div class="chronicle-content" v-if="files.length > 0">
                <chronicle-file v-for="file in files"
                                :key="file.id"
                                :note="note"
                                :file="file"
                                :section="section"
                                :use-font-awesome="useFontAwesome"
                                :user="user" />
            </div>
            <div class="chronicle-content error" v-else>--- No files could be found ---</div>
        </div>
    </div>
</template>

<script>
    import ChronicleFile from './File';

    export default {
        name: 'Chronicle-Files',

        components: {
            ChronicleFile
        },

        props: {
            note: {
                type: Object,
                required: true
            },

            section: {
                type: Object,
                required: true
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
            currentPage: null,
            firstPage: null,
            lastPage: null,
            nextPage: null,
            previousPage: null,

            startEntry: 0,
            endEntry: 0,
            totalEntries: 0,

            files: [],
        }),

        created() {
            this.getFiles(1);
        },

        methods: {
            getFiles(page) {
                var url = '/notes/' + this.note.id + '/files';
                var config = {
                    params: {
                        page: page
                    }
                };
                axios.get(url, config).then(r => {
                    this.firstPage = 1;
                    this.lastPage = r.data.files.last_page;
                    this.currentPage = r.data.files.current_page;

                    this.nextPage = this.currentPage < this.lastPage ? this.currentPage + 1: null;
                    this.previousPage = this.currentPage > this.firstPage ? this.currentPage - 1 : null;

                    this.startEntry = r.data.files.from ? r.data.files.from : 0;
                    this.endEntry = r.data.files.to ? r.data.files.to : 0;
                    this.totalEntries = r.data.files.total ? r.data.files.total : 0;

                    this.files = r.data.files.data;
                });
            }
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

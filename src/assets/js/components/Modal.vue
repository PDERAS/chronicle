<template>
    <div class="chronicle-modal">
        <div class="chronicle-modal-mask" @click.self="emitCloseModal">
            <div class="chronicle-modal-wrapper">

                <!-- Add Note !-->
                <template v-if="action == 'add'">
                    <div class="chronicle-modal-header">
                        Add Note
                        <span class="chronicle-modal-close" @click="emitCloseModal">&times;</span>
                    </div>
                    <div class="chronicle-modal-content">
                        <chronicle-input v-model="description" />
                    </div>
                    <div class="chronicle-modal-footer">
                        <button class="chronicle-btn block" @click="store">
                            Save and Close
                        </button>
                    </div>
                </template>

                <!-- Edit Note !-->
                <template v-else-if="action == 'edit'">
                    <div class="chronicle-modal-header">
                        Edit Note
                        <span class="chronicle-modal-close" @click="emitCloseModal">&times;</span>
                    </div>
                    <div class="chronicle-modal-content">
                        <chronicle-input v-model="description" />
                    </div>
                    <div class="chronicle-modal-footer">
                        <button class="chronicle-btn block" @click="update">
                            Save and Close
                        </button>
                    </div>
                </template>

                <!-- View Note !-->
                <template v-else-if="action == 'view'">
                    <div class="chronicle-modal-header">
                        Note Information
                        <span class="chronicle-modal-close" @click="emitCloseModal">&times;</span>
                    </div>
                    <div class="chronicle-modal-content">{{ note.description }}</div>
                    <div class="chronicle-modal-footer">
                        <button class="chronicle-btn block" @click="emitCloseModal">
                            Close
                        </button>
                    </div>
                </template>

                <!-- Delete notes !-->
                <template v-else-if="action == 'delete'">
                    <div class="chronicle-modal-header">
                        Confirmation
                        <span class="chronicle-modal-close" @click="emitCloseModal">&times;</span>
                    </div>
                    <div class="chronicle-modal-content">Are you sure you want to delete this note?</div>
                    <div class="chronicle-modal-footer">
                        <button class="chronicle-btn block" @click="destroy(note)">
                            Confirm and Close
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import ChronicleInput from './Input';

    export default {
        name: 'chronicle-modal',

        components: {
            ChronicleInput
        },

        props: {
            action: {
                type: String,
                required: true
            },

            note: {
                type: Object,
                default: () => {}
            },

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
            description: null,
        }),

        created() {
            if (this.note) {
                this.description = this.note.description;
            }
        },

        methods: {
            destroy(note) {
                var url = '/notes/' + note.id;

                axios.delete(url).then(r => {
                    this.$emit('get-notes');
                    this.emitCloseModal();
                });
            },

            emitCloseModal() {
                this.$emit('close-modal');
            },

            store() {
                var params = {
                    description: this.description,
                    section_tag: this.section.tag,
                    section_ref: this.refSlug
                }
                axios.post('/notes', params).then(() => {
                    this.description = null;
                    this.$emit('get-notes', this.section.tag);
                    this.emitCloseModal();
                });
            },

            update() {
                var params = {
                    description: this.description,
                    section_tag: this.section.tag,
                    section_ref: this.refSlug
                }
                axios.patch('/notes/' + this.note.id, params).then(() => {
                    this.description = null;
                    this.$emit('get-notes', this.section.tag);
                    this.emitCloseModal();
                });
            }
        }
    }
</script>

<style lang="scss">
    /* REF: https://www.w3schools.com/howto/howto_css_modals.asp */
    /* Modal Mask */
    .chronicle-modal-wrapper {
        background-color: transparent;
        margin: 5% auto;
        width: 75%;
        -webkit-animation-name: animatetop;
        -webkit-animation-duration: 0.4s;
        animation-name: animatetop;
        animation-duration: 0.4s;
    }

    /* Modal Mask */
    .chronicle-modal-mask {
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0,0,0);
        background-color: rgba(0,0,0,0.4);
    }

    /* Modal Header */
    .chronicle-modal-header {
        padding: 5px 15px;
        background-color: #3f3f3f;
        color: white;
        font-size: 20px;

        -webkit-border-radius: 5px 5px 0px 0px;
        -moz-border-radius: 5px 5px 0px 0px;
        border-radius: 5px 5px 0px 0px;
    }

    /* Modal Body */
    .chronicle-modal-body {
        padding: 2px 16px;
    }

    /* Modal Footer */
    .chronicle-modal-footer {
        text-align: right;
        padding: 10px 15px;
        background-color: #ffffff;
        color: white;
        color: #3f3f3f;

        -webkit-border-radius: 0px 0px 5px 5px;
        -moz-border-radius: 0px 0px 5px 5px;
        border-radius: 0px 0px 5px 5px;
    }

    /* Modal Content */
    .chronicle-modal-content {
        position: relative;
        background-color: #fefefe;
        margin: auto;
        padding: 15px;
        padding-bottom: 0;
    }

    /* The Close Button */
    .chronicle-modal-close {
        color: #fefefe;
        float: right;
        font-size: 20px;
        font-weight: bold;
    }

    .chronicle-modal-close:hover,
    .chronicle-modal-close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    /* Add Animation */
    @keyframes animatetop {
        0% {
            margin-top: -5%;
            opacity: 0
        }
        100% {
            margin-top: 5%;
            opacity: 1
        }
    }
</style>

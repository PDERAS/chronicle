<template>
    <div class="chronicle-modal">
        <div class="chronicle-modal-mask" @click.self="emitCloseModal">
                <div class="chronicle-modal-wrapper">
                    <div class="chronicle-modal-header">
                        <template v-if="action == 'delete'">Confirmation</template>
                        <template v-if="action == 'view'">Note Information</template>
                        <span class="chronicle-modal-close" @click="emitCloseModal">&times;</span>
                    </div>

                    <div class="chronicle-modal-content">
                        <template v-if="action == 'delete'">Are you sure you want to delete this note?</template>
                        <template v-if="action == 'view'">{{ note.description }}</template>
                    </div>

                    <div class="chronicle-modal-footer">
                        <button class="chronicle-modal-btn" @click="destroy(note)" v-if="action == 'delete'">
                            Confirm
                        </button>
                        <button class="chronicle-modal-btn" @click="emitCloseModal">
                            Close
                        </button>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'chronicle-modal',

        props: {
            action: {
                type: String,
                required: true
            },

            note: {
                type: Object,
                required: true
            },

            refSlug: {
                type: String,
                required: true
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
        border-top: solid 1px #3f3f3f;
        padding: 5px 15px;
        background-color: #ffffff;
        color: white;
        color: #3f3f3f;

        -webkit-border-radius: 0px 0px 5px 5px;
        -moz-border-radius: 0px 0px 5px 5px;
        border-radius: 0px 0px 5px 5px;
    }

    /* Modal Button */
    .chronicle-modal-btn {
        background: #3f3f3f;
        padding: 5px 10px;
        color: white;
        font-size: 14px;
        cursor: pointer;

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

    /* Modal Content */
    .chronicle-modal-content {
        position: relative;
        background-color: #fefefe;
        margin: auto;
        padding: 15px;
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

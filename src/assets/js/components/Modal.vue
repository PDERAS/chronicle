<template>
    <div class="secretary-modal">
        <div class="secretary-modal-mask" @click.self="emitCloseModal">
                <div class="secretary-modal-wrapper">
                    <div class="secretary-modal-header">
                        Title
                        <span class="secretary-modal-close" @click="emitCloseModal">&times;</span>
                    </div>

                    <div class="secretary-modal-content">
                        Content
                    </div>

                    <div class="secretary-modal-footer">
                        Footer
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'secretary-modal',

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
            emitCloseModal() {
                this.$emit('close-modal');
            }
        }
    }
</script>

<style lang="scss">
    /* REF: https://www.w3schools.com/howto/howto_css_modals.asp */
    /* Modal Mask */
    .secretary-modal-wrapper {
        background-color: #fefefe;
        margin: 10% auto;
        border: 1px solid #888;
        width: 75%;
    }

    /* Modal Mask */
    .secretary-modal-mask {
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
    .secretary-modal-header {
        padding: 5px 15px;
        background-color: #5cb85c;
        color: white;
        font-size: 20px;
    }

    /* Modal Body */
    .secretary-modal-body {
        padding: 2px 16px;
    }

    /* Modal Footer */
    .secretary-modal-footer {
        padding: 5px 15px;
        background-color: #5cb85c;
        color: white;
    }

    /* Modal Content */
    .secretary-modal-content {
        position: relative;
        background-color: #fefefe;
        margin: auto;
        padding: 15px;
        animation-name: animatetop;
        animation-duration: 0.4s;
    }

    /* The Close Button */
    .secretary-modal-close {
        color: #fefefe;
        float: right;
        font-size: 20px;
        font-weight: bold;
    }

    .secretary-modal-close:hover,
    .secretary-modal-close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>

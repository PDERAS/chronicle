<template>
    <div class="chronicle-action-bar">
        <button class="chronicle-btn action" @click="openModal('view')">
            <i class="far fas fa-info" v-if="useFontAwesome" />
            <div class="x-small" v-else>Info</div>
        </button>
        <button class="chronicle-btn action" v-if="section.is_comments_allowed && user">
            <i class="far fas fa-comments" v-if="useFontAwesome" />
            <div class="x-small" v-else>Comments</div>
        </button>
        <button class="chronicle-btn action" v-if="section.is_attachments_allowed">
            <i class="far fas fa-file" v-if="useFontAwesome" />
            <div class="x-small" v-else>Files</div>
        </button>
        <template v-if="user">
            <button class="chronicle-btn action" @click="openModal('edit')" v-if="section.is_editing_allowed && note.user_id == user.id">
                <i class="far fas fa-edit" v-if="useFontAwesome" />
                <div class="x-small" v-else>Edit</div>
            </button>
            <button class="chronicle-btn action" @click="openModal('delete')" v-if="section.is_deleting_allowed && note.user_id == user.id">
                <i class="far fas fa-trash" v-if="useFontAwesome" />
                <div class="x-small" v-else>Delete</div>
            </button>
        </template>
    </div>
</template>

<script>
    export default {
        name: 'chronicle-action-bar',

        props: {
            note: {
                type: Object,
                required: true
            },

            section: {
                type: Object,
                required: true
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

        methods: {
            openModal(action) {
                this.$emit('open-modal', action);
            }
        }
    }
</script>

<style lang="scss">
    .chronicle-action-bar {
        border: solid thin lighten(black, 60);
        border-radius: 5px;
        background: white;
        position: absolute;
        top: -5px;
        right: 5px;

        -webkit-box-shadow: 1px 1px 5px #999999;
        -moz-box-shadow: 1px 1px 5px #999999;
        box-shadow: 1px 1px 5px #999999;

        .chronicle-btn {
            border-right: solid thin lighten(black, 60);
            min-width: 30px;
            font-size: 14px;
            padding: 0 5px;
            text-align: center;
            width: auto;

            &:last-child {
                border-right: none;
            }
        }
    }
</style>

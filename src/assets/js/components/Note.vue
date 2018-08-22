<template>
    <div class="chronicle-note-wrapper" @mouseover="openActionBar" @mouseleave="hideActionBar">
        <div class="chronicle-note-header">
            <span class="chronicle-note-user">{{ note.user.name }}</span>
            <span class="chronicle-note-time">{{ formatDate(createDate(note.created_at)) }}</span>
        </div>
        <div class="chronicle-note-content">
            <div class="chronicle-note-description">{{ note.description }}</div>
        </div>

        <chronicle-action-bar type="notes"
                              :note="note"
                              :section="section"
                              :use-font-awesome="useFontAwesome"
                              :user="user"
                              @open-modal="openModal"
                              v-if="showActionBar" />
    </div>
</template>

<script>
    import ChronicleActionBar from './ActionBar';

    export default {
        name: 'chronicle-note',

        components: {
            ChronicleActionBar
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

            useFontAwesome: {
                type: Boolean,
                default: true
            },

            user: {
                validator: (val) =>  { return val === null || typeof val === 'object' },
                default: () => new Object()
            }
        },

        data: () => ({
            showActionBar: false
        }),

        methods: {
            createDate(timestamp) {
                if (!timestamp.match(/^\d{4}-[01]{1}\d{1}-[0-3]{1}\d{1}T[0-2]{1}\d{1}:[0-6]{1}\d{1}:[0-6]{1}\d{1}Z$/)) {
                    throw new Error("Format must be: YYYY-mm-dd hh-mm-ss");
                }

                return new Date(timestamp);
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

            hideActionBar(id) {
                this.showActionBar = false;
            },

            openModal(action) {
                this.$emit('open-modal', action, this.note);
            },

            openActionBar() {
                this.showActionBar = true;
            }
        }
    }
</script>

<style lang="scss">
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
</style>

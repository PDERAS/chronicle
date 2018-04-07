<template>
    <div class="chronicle-note-wrapper" @mouseover="openActionBar" @mouseleave="hideActionBar">
        <div class="chronicle-note-header">
            <span class="chronicle-note-user">{{ note.user.name }}</span>
            <span class="chronicle-note-time">{{ formatDate(createDate(note.created_at)) }}</span>
        </div>
        <div class="chronicle-note-content">
            <div class="chronicle-note-description">{{ note.description }}</div>
        </div>

        <chronicle-action-bar :note="note" :section="section" :use-font-awesome="useFontAwesome" :user="user" @open-modal="openModal" v-if="showActionBar" />
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

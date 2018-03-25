<template>
    <div class="chronicle-input">
    </div>
</template>

<script>
    export default {
        name: 'chronicle-input',

        props: {
            refSlug: {
                type: String,
                required: true
            },

            section: {
                type: Object,
                required: true
            }
        }
    }
</script>

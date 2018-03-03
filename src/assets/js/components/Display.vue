<template>
    <div class="secretary-display">
    </div>
</template>

<script>
    export default {
        name: 'secretary-display',

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

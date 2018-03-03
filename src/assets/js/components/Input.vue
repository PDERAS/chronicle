<template>
    <div class="secretary-input">
    </div>
</template>

<script>
    export default {
        name: 'secretary-input',

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

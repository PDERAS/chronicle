    <template>
    <div class="chronicle-input">
        <textarea v-model="value"></textarea>

        <button class="chronicle-btn save" @click="save">
            <i class="fas fa-trash" v-if="useFontAwesome" />
            <div class="x-small" v-else>Save</div>
        </button>
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
        },

        data: () => ({
            value: null,
        }),

        methods: {
            save() {
                var params = {
                    description: this.value,
                    section_tag: this.section.tag,
                    section_ref: this.refSlug
                }
                axios.post('/notes', params).then(() => {
                    this.value = null;
                    this.$emit('refresh-section', this.section.tag);
                });
            }
        }
    }
</script>

<style lang="scss">
    .chronicle-input {
        margin: 10px 0;

        textarea {
            width: 100%;
            padding: 0;
            resize: none;
            min-height: 50px;
        }
    }
</style>

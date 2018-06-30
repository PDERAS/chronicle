<template>
    <div :class="dragStyle"
         class='chronicle-uploader d-flex justify-content-center align-items-center'
         @drop.prevent="dropHandler"
         @dragover.prevent="dragOverHandler"
         @dragexit.prevent="dragendHandler"
         @dragleave.prevent="dragendHandler"
         @dragend.prevent="dragendHandler">
        <p v-if="value.length == 0">
            <span class="no-underline"><i class="far fa-upload"></i></span>&shy;
            <span class="no-underline"> Drop or <span class="underline">choose</span> files to upload</span>&shy;
        </p>
        <input class="input-file"
               type="file"
               :accept="limitTypes.length > 0 ? limitTypes : false"
               @change="fileDialog"
               multiple >

        <!-- Image Previews !-->
        <div id="uploader-preview" ref="uploader-preview" />
    </div>
</template>

<script>
    export default {
        name: 'chronicle-uploader',

        props: {
            limitTypes: {
                type: String|Boolean,
                default: () => [
                   'application/pdf',
                   'text/plain',
                   'text/csv',
                   'image/bmp',
                   'image/jpeg',
                   'image/jpg',
                   'image/png'
                ].join(', ')
            },

            validFileTypes: {
                type: Array,
                default: () => [
                    'image/bmp',
                    'image/jpeg',
                    'image/jpg',
                    'image/png'
                ]
            },

            value: {
                type: Array,
                required: true
            }
        },

        data: () => ({
            drag: false,
        }),

        computed: {
            dragStyle() {
                return this.drag ? 'dragging' : '';
            }
        },

        watch: {
            value() {
                this.updatePreview();
            }
        },

        methods: {
            addFile(files, file) {
                if (this.alreadyExists(file)) {
                    alert(
                        'It looks like you have already uploaded this file. If this is incorrect, please rename the file and try again'
                    );
                    return files;
                } else {
                    return [...files, file];
                }
            },

            alreadyExists(file) {
                for (const f of this.value) {
                    if (f.name === file.name) {
                        return true;
                    }
                }
            },

            cleanRemovedFiles() {
                var preview = this.$refs['uploader-preview'];

                var newChildrenIds = [];
                for (var i = 0; i < this.value.length; i++) {
                    newChildrenIds.push(this.parseId(this.value[i].name));
                }

                for (var i = 0; i < preview.children.length; i++) {
                    var child = preview.children[i]
                    if (newChildrenIds.indexOf(child.id) == -1) {
                        var elem = document.getElementById(this.parseId(child.id));
                        elem.parentNode.removeChild(elem);
                    }
                }
            },

            createDefaultNode() {
                var div = document.createElement('div');
                var i = document.createElement('i');

                div.classList.add('default-preview');
                i.classList.add('far', 'fas', 'fa-file-image');

                div.appendChild(i);

                return div;
            },

            createImageNode(src) {
                var img = document.createElement('img');

                img.src = src;
                img.classList.add('image-preview');

                return img;
            },

            createNameNode(name) {
                var nameNode = document.createElement('div');

                nameNode.innerHTML = name;
                nameNode.classList.add('preview-name');

                return nameNode;
            },

            createPreviewWrapperNode(file) {
                var previewWrapper = document.createElement('div');

                previewWrapper.id = this.parseId(file.name);
                previewWrapper.classList.add('preview-wrapper');

                return previewWrapper;
            },

            createRemoveBtnNode(file) {
                var removeBtn = document.createElement('button');

                removeBtn.classList.add('preview-remove-btn');
                removeBtn.addEventListener('click', () => {
                    this.removeFile(file)
                });

                return removeBtn;
            },

            dragOverHandler(e) {
                this.drag = true;
            },

            dragendHandler(e) {
                this.drag = false;
            },

            dropHandler(ev) {
                this.drag = false;
                if (ev.dataTransfer.items) {
                    let files = [];
                    for (const item of ev.dataTransfer.items) {
                        // If dropped items aren't files, reject them
                        if (item.kind === 'file') {
                            var file = item.getAsFile();
                            files = this.addFile(files, file);
                        }
                    }
                    this.$emit('input', [...this.value, ...files]);
                } else {
                    let files = [];
                    for (const item of ev.dataTransfer.files) {
                        var file = item.name;
                        files = this.addFile(files, file);
                    }
                    this.$emit('input', [...this.value, ...files]);
                }
            },

            fileDialog(e) {
                let files = [];
                for (const file of e.target.files) {
                    if (this.alreadyExists(file)) {
                        alert(
                            'It looks like you have already uploaded this file. If this is incorrect, please rename the file and try again'
                        );
                    } else {
                        files.push(file);
                    }
                }
                this.$emit('input', [...this.value, ...files]);
            },

            parseId(string) {
                return string.replace(/[\W_]+/g, "_");
            },

            removeFile(file) {
                var idx = this.value.findIndex(f => {
                    return f.name == file.name;
                });

                this.value.splice(idx, 1);
                this.$emit('input', this.value);
            },

            updatePreview() {
                var preview = this.$refs['uploader-preview'];

                this.cleanRemovedFiles();

                this.value.forEach((f, idx) => {
                    if (!document.getElementById(this.parseId(f.name))) {
                        var reader = new FileReader();
                        reader.onload = e => {
                            var previewWrapper = this.createPreviewWrapperNode(f);
                            var removeBtn = this.createRemoveBtnNode(f);
                            var nameNode = this.createNameNode(f.name);

                            if (this.validFileTypes.indexOf(f.type) > -1) {
                                var previewNode = this.createImageNode(e.target.result);
                            } else {
                                var previewNode = this.createDefaultNode();
                            }

                            previewWrapper.appendChild(previewNode);
                            previewWrapper.appendChild(removeBtn);
                            previewWrapper.appendChild(nameNode);
                            preview.appendChild(previewWrapper);
                        };
                        reader.readAsDataURL(f);
                    }
                });
            }
        }
    }
</script>

<style lang="scss">
    .chronicle-uploader {
        width: 100%;
        min-height: 100px;
        border: 1px solid #999;
        position: relative;
        height: auto;

        p {
            width: 100%;
        }

        &, p, span {
            font-size: 0.9rem;
            text-align: center;
        }

        .underline {
            border-bottom: 2px solid;
        }
    }

    .input-file {
        position: absolute;
        left: 0;
        top: 0;
        background: #eee;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .dragging {
        background: rgba(0, 0, 0, 0.5);
        p, span {
            color: #fff;
            transform: scale(1.1);
            transition: 0.4s;
        }
    }

    .preview-wrapper {
        position: relative;
        display: inline-block;
        vertical-align: middle;
        height: 100px;
        width: 100px;
        pointer-events: none;
        margin: 5px;
        border: 1px dashed black;
    }

    .default-preview {
        display: table;
        width: 100%;
        height: 100%;

        i {
            display: table-cell;
            vertical-align: middle;
            font-size: 24px;
            color: black;
        }
    }

    .image-preview {
        position: absolute;
        margin: auto;
        max-height: 100%;
        max-width: 100%;
        padding: 5px;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
    }

    .preview-remove-btn {
        pointer-events: auto;
        position: absolute;
        right: 0;
        top: 0;
        border: none;
        outline: none;
        cursor: pointer;
        z-index: 99;
        margin: 3px;

        &:hover {
            background: #DDD;
        }

        &:before {
            content: 'x';
        }
    }

    .preview-name {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        overflow: hidden;
        padding: 5px;
        background: rgba(255, 255, 255, 0.6);
        text-align: left;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
</style>

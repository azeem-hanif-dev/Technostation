<template>
    <div>
        <span>Upload Documents:</span>
        <div class="row" v-for="(document_log, i) in document_logs" :key="i">
            <div class="col-md-3">
                <input class="form-control"
                       v-model="document_log.doc_type"
                       type="text"
                       placeholder="Document types"
                />
            </div>
            <div class="col-md-2">
                <input class="form-control"
                       v-model="document_log.expiry"
                       type="date"
                />
            </div>
            <div class="col-md-4">
                <input class="btn" accept=".pdf,.csv, .xls, .xlsx, .docx, text/csv, application/pdf, application/csv,text/comma-separated-values, application/csv, application/excel,
                        application/vnd.msexcel, text/anytext, application/vnd. ms-excel,
                        application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                       type="file" name="document" @change="fileUpload">
            </div>
            <div class="col-md-3 mt-2">
                <a
                    v-if=" document_logs.length !== i + 1 || document_logs.length === i + 1"
                    href="#"
                    :class="document_logs.length == 1 ? `disabled` : ''"
                    class="text-danger"
                    @click.prevent="removeDocument(document_log)">
                    <i class="text-danger fa fa-minus-circle"></i>
                </a>
                <a
                    v-if="document_logs.length === i + 1"
                    href="#"
                    class="text-success"
                    @click.prevent="addDocument">
                    <i class="fa fa-plus-circle bg-plus"></i>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    beforeMount() {
        this.document_logs.push(Object.assign({}, this.document_log));
    },
    data() {
        return {
            document_logs: [
            ],
            document_log: {
                doc_type: "",
                expiry: "",
                file: "",
            },
            key_value: 0,
        };
    },
    methods: {
        fileUpload(event) {
             this.document_logs.push(Object.assign({}, this.document_log));

            const document_log = this.document_logs[this.key_value];
            document_log.file = event.target.files[0];

            this.key_value = this.key_value+1;

            this.$emit('updateDocumentLogs', this.document_logs);
        },
        removeDocument(row) {
            const index = this.document_logs.indexOf(row);
            if (index !== -1) {
                this.document_logs.splice(index, 1);
                // Emit an event with the updated data
                this.$emit('updateDocumentLogs', this.document_logs);
            }
        },
        addDocument() {
            this.document_logs.push({ doc_type: '', expiry: '', file: null });
            this.$emit('updateDocumentLogs', this.document_logs);
        }
    }
};
</script>

<style scoped>
/* Add any component-specific styles here */
</style>

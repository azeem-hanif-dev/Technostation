<template>
    <div>
        <h2>Update Agency</h2>
        <form @submit.prevent="updateAgency">
            <div class="row">
                <div class="col-lg-6 form-group">
                    <span>{{ translations.name }}:*</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="name"
                        type="text"
                        placeholder="Enter name"
                    /><br />

                    <span>Email:</span><br />
                    <input
                        class="form-control"
                        v-model="email"
                        type="email"
                        placeholder="Enter email"
                    /><br />

                    <span>{{ translations.contact_person }}:</span><br />
                    <input
                        class="form-control"
                        v-model="contact"
                        type="text"
                        placeholder="Enter contact person"
                    /><br />
                    <span>{{ translations.phone }}:</span><br />
                    <input
                        class="form-control"
                        v-model="phone"
                        type="text"
                        placeholder="Enter phone number"
                    />
                    <br />
                    <span>{{ translations.agency_url }}:</span><br />
                    <input
                        class="form-control"
                        v-model="agency_url"
                        type="text"
                        placeholder="Enter url"
                    />
                </div>

                <div class="col-lg-6 form-group">
                    <span>{{ translations.address }}:</span><br />
                    <input
                        class="form-control"
                        v-model="address"
                        type="text"
                        placeholder="Enter address"
                    /><br />
                    <span>{{ translations.city }}:</span><br />
                    <input
                        class="form-control"
                        v-model="city"
                        type="text"
                        placeholder="Enter city"
                    /><br />
                    <span>{{ translations.country }}:</span><br />
                    <input
                        class="form-control"
                        v-model="country"
                        type="text"
                        placeholder="Enter country"
                    /><br />
                    <span>Postcode:</span><br />
                    <input
                        class="form-control"
                        v-model="postcode"
                        type="text"
                        placeholder="Enter postcode"
                    /><br />
                </div>
            </div>
            <div class="col-md-12">
                <span>Notes:</span><br />
                <textarea
                    class="form-control"
                    type="text"
                    placeholder="notes"
                    v-model="notes"
                ></textarea>
                <br />
            </div>
            <span>Documents:</span>
            <div
                class="row"
                v-for="(document_update_log, i) in document_update_logs"
                :key="i"
            >
                <div class="col-md-3">
                    <input
                        class="form-control"
                        v-model="document_update_log.doc_type"
                        type="text"
                        placeholder="Document types"
                    />
                </div>
                <div class="col-md-2">
                    <input
                        class="form-control"
                        v-model="document_update_log.expiry"
                        type="date"
                    />
                </div>
                <div class="col-md-3">
                    <a :href="document_update_log.path" target="_blank"
                        >@{{ document_update_log.file }}</a
                    >
                </div>
                <div class="col-md-1">
                    <a :href="document_update_log.path" download
                        ><i class="fas fa-download"></i
                    ></a>
                </div>
                <div class="col-md-3 mt-2">
                    <a
                        v-if="
                            document_update_logs.length !== i + 1 ||
                                document_update_logs.length === i + 1
                        "
                        href="#"
                        :class="
                            document_update_logs.length == 1 ? `disabled` : ''
                        "
                        class="text-danger"
                        @click.prevent="removeUpdateDoc(document_update_log)"
                    >
                        <i class="text-danger fa fa-minus-circle"></i>
                    </a>
                </div>
            </div>

            <div
                class="row"
                v-for="(document_log, i) in document_logs"
                :key="i"
            >
                <div class="col-md-3">
                       <AutoSuggestInput
                        v-model="document_log.doc_type"
                        placeholder="Document type"
                    />
                    <!-- <input
                        class="form-control"
                        v-model="document_log.doc_type"
                        type="text"
                        placeholder="Document types"
                    /> -->
                </div>
                <div class="col-md-2">
                    <input
                        class="form-control"
                        v-model="document_log.expiry"
                        type="date"
                    />
                </div>
                <div class="col-md-4">
                    <input
                        class="btn"
                        accept=".pdf,.csv, .xls, .xlsx, .docx, text/csv, application/pdf, application/csv,text/comma-separated-values, application/csv, application/excel,
                        application/vnd.msexcel, text/anytext, application/vnd. ms-excel,
                        application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                        type="file"
                        name="document"
                        @change="fileUpload"
                    />
                </div>
                <div class="col-md-3 mt-2">
                    <a
                        v-if="
                            document_logs.length !== i + 1 ||
                                document_logs.length === i + 1
                        "
                        href="#"
                        :class="document_logs.length == 1 ? `disabled` : ''"
                        class="text-danger"
                        @click.prevent="removeDocument(document_log)"
                    >
                        <i class="text-danger fa fa-minus-circle"></i>
                    </a>
                    <a
                        v-if="document_logs.length === i + 1"
                        href="#"
                        class="text-success"
                        @click.prevent="addDocument"
                    >
                        <i class="fa fa-plus-circle bg-plus"></i>
                    </a>
                </div>
            </div>
            <div class="row">
                <div style="margin: 10px; text-align: end" class="col-lg-12">
                    <a
                        :href="base_url + 'employment-agencies'"
                        class="mt-0 btn btn-danger"
                        style="width: 100px;"
                    >
                        {{ translations.cancel }}
                    </a>

                    <input
                        style="margin-right: 50px;
                           width: 80px;
                           margin-left: 10px;"
                        type="submit"
                        class="submit mt-0 btn btn-primary"
                        value="Update"
                    />
                </div>
            </div>
        </form>
    </div>
</template>
<script>
import axios from "axios";
import Swal from "sweetalert2";
export default {
    data() {
        return {
            name: "",
            email: "",
            contact: "",
            phone: "",
            address: "",
            city: "",
            country: "",
            agency_url: "",
            notes: "",
            postcode: "",
            removedDocumentIds: [],
            document_update_logs: [],
            document_update_log: {
                doc_type: "",
                expiry: "",
                file: "",
                path: "",
                id: ""
            },
            document_log: {
                doc_type: "",
                expiry: "",
                file: ""
            },
            key_value: 0,
            document_logs: [],
            base_url: APP_URL
        };
    },
    props: ["agency", "translations"],
    beforeMount() {
        this.document_logs.push(Object.assign({}, this.document_log));
    },
    mounted() {
        console.log("documents:", this.agency.documents);
        for (let i = 0; i < this.agency.documents.length; i++) {
            this.document_update_logs.push(
                Object.assign({}, this.document_update_log)
            );
            const document_update_log = this.document_update_logs[i];
            document_update_log.doc_type = this.agency.documents[i].type;
            document_update_log.expiry = this.agency.documents[i].expiry_date;
            document_update_log.file = this.agency.documents[i].file;
            document_update_log.path = this.agency.documents[i].path;
            document_update_log.id = this.agency.documents[i].id;
        }

        this.name = this.agency.name || "";
        this.email = this.agency.email || "";
        this.contact = this.agency.contact_person || "";
        this.phone = this.agency.phone || "";
        this.address = this.agency.address || "";
        this.city = this.agency.city || "";
        this.country = this.agency.country || "";
        this.agency_url = this.agency.url || "";
        this.notes = this.agency.notes || "";
        this.postcode = this.agency.zipcode || "";
    },
    methods: {
        removeUpdateDoc(document_update_log) {
            if (this.document_update_logs.length >= 1) {
                // Extract doc_type before removing
                const removedDocId = document_update_log.id;

                // Filter out the document_update_log from the array
                this.document_update_logs = this.document_update_logs.filter(
                    log => log !== document_update_log
                );

                // Store the removed doc_type in the array
                this.removedDocumentIds.push(removedDocId);
            }
        },

        addDocument() {
            this.document_logs.push(Object.assign({}, this.document_log));
        },
        removeDocument(row) {
            if (this.document_logs.length > 1) {
                this.document_logs = this.document_logs.filter(
                    document_log => document_log !== row
                );
            }
        },
        fileUpload(event) {
            this.document_logs.push(Object.assign({}, this.document_log));

            const document_log = this.document_logs[this.key_value];
            document_log.file = event.target.files[0];

            console.log("FILEEEEEEEEEEEEE" + event.target);

            this.key_value = this.key_value + 1;
        }, //fileUpload
        updateAgency() {
            let formData = new FormData();
            formData.append("name", this.name);
            formData.append("email", this.email);
            formData.append("contact", this.contact);
            formData.append("phone", this.phone);
            formData.append("address", this.address);
            formData.append("city", this.city);
            formData.append("country", this.country);
            formData.append("agency_url", this.agency_url);
            formData.append("notes", this.notes);
            formData.append("postcode", this.postcode);
            this.removedDocumentIds.forEach(id => {
                formData.append("removedDocumentIds[]", id);
            });
            this.document_logs.forEach((log, index) => {
                formData.append(
                    `document_logs[${index}][doc_type]`,
                    log.doc_type
                );
                formData.append(`document_logs[${index}][expiry]`, log.expiry);
                formData.append(`document_logs[${index}][file]`, log.file);
            });

            this.document_update_logs.forEach((log, index) => {
                formData.append(
                    `document_update_logs[${index}][doc_type]`,
                    log.doc_type
                );
                formData.append(
                    `document_update_logs[${index}][expiry]`,
                    log.expiry
                );
                formData.append(`document_update_logs[${index}][id]`, log.id);
            });

            axios
                .post(
                    APP_URL +
                        "employment-agencies/" +
                        this.agency.id +
                        "?_method=PUT",
                    formData,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data"
                        }
                    }
                )
                .then(response => {
                    Swal.fire(
                        "Good job!",
                        (this.message = response.data.message),
                        "success"
                    );

                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                    let url = this.base_url + "employment-agencies";
                    setTimeout(function() {
                        window.location.href = url;
                    }, 1500);

                    this.$emit("Agency updated", response.data);
                    // this.name = '';
                    // this.email = '';
                    // this.contact = '';
                    // this.phone = '';
                    // this.address = '';
                    // this.city = '';
                    // this.country = '';
                    // this.agency_url = '';
                });
        },
        submitForm: function() {
            this.formSubmitted = true;
        }
    }
};
</script>
<style>
form {
    padding: 10px;
}

input {
    padding: 4px 8px;
    margin: 4px;
    width: 500px;
}

span {
    font-size: 18px;
    margin: 4px;
    font-weight: 500;
}

h2 {
    text-align: center;
}

.submit {
    font-size: 15px;
    color: #fff;
    background: #2445ff;
    padding: 6px 12px;
    border: none;
    margin-top: 8px;
    cursor: pointer;
    border-radius: 5px;
}
</style>

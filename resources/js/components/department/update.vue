<template>
    <div>
        <h2>{{ translations.update }}</h2>
        <form @submit.prevent="updateDepartment">
            <div style="margin: 10px;" class="row">
                <div class="col-md-4 form-group">
                    <span>{{ translations.customer }}:*</span><br />
                    <input
                        class="form-control"
                        disabled
                        v-model="customer"
                        type="text"
                        placeholder="Enter name"
                    /><br />

                    <span>{{ translations.department_name }}:*</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="name"
                        type="text"
                        placeholder="Enter name"
                    /><br />

                    <span>{{ translations.address }}:*</span><br />
                    <input
                        class="form-control"
                        v-model="address"
                        type="text"
                        placeholder="Enter address"
                    /><br />

                    <span>Post code:*</span><br />
                    <input
                        class="form-control"
                        v-model="post_code"
                        type="text"
                        placeholder="Enter post code"
                    /><br />
                </div>

                <div class="col-md-4 form-group">
                    <span>{{ translations.city }}:*</span><br />
                    <input
                        class="form-control"
                        v-model="city"
                        type="text"
                        placeholder="Enter city"
                    /><br />
                    <span>{{ translations.mail_box }}:*</span><br />
                    <input
                        class="form-control"
                        v-model="mailbox"
                        type="text"
                        placeholder="Enter mail box"
                    /><br />
                    <span>{{ translations.po_box }}:*</span><br />
                    <input
                        class="form-control"
                        v-model="postal_code"
                        type="text"
                        placeholder="Enter PO box"
                    /><br />
                    <span>{{ translations.po_box_city }}:*</span><br />
                    <input
                        class="form-control"
                        v-model="po_box_city"
                        type="text"
                        placeholder="Enter po box city"
                    /><br />
                </div>

                <div class="col-md-4 form-group">
                    <span>{{ translations.phone }}:*</span><br />
                    <input
                        class="form-control"
                        v-model="phone"
                        type="text"
                        placeholder="Enter phone"
                    /><br />
                    <span>Fax:*</span><br />
                    <input
                        class="form-control"
                        v-model="fax"
                        type="text"
                        placeholder="Enter fax"
                    /><br />
                    <span>Email:*</span><br />
                    <input
                        class="form-control"
                        v-model="email"
                        type="email"
                        placeholder="Enter email"
                    /><br />
                    <span>Notes:</span><br />
                    <textarea
                        class="form-control"
                        v-model="notes"
                        type="text"
                    /><br />
                </div>
            </div>
            {{ document_update_log.type }}
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
                <!-- <div class="col-md-3">
                    <a :href="document_update_log.path" target="_blank"
                        >@{{ document_update_log.file }}</a
                    >
                </div> -->
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
                        :href="base_url + 'departments'"
                        class="mt-0 btn btn-danger"
                        style="width: 100px;"
                    >
                        {{ translations.cancel }}
                    </a>

                    <button
                        style="margin-right: 50px;
                           width: 80px;
                           margin-left: 10px;"
                        type="submit"
                        class="submit mt-0 btn btn-primary"
                    >
                        {{ translations.update }}
                    </button>
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
            address: "",
            post_code: "",
            mailbox: "",
            postal_code: "",
            po_box_city: "",
            email: "",
            fax: "",
            phone: "",
            city: "",
            customer: "",
            notes: "",
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
    beforeMount() {
        this.document_logs.push(Object.assign({}, this.document_log));
    },
    props: ["department", "translations"],
    mounted() {
        console.log("Update component mounted");
        axios
            .get(`${APP_URL}/departments/${this.department}`)

            .then(response => {
                const department = response.data;
                // console.log("kashii:", department.documents);

                this.customer = department.customer.name;
                this.name = department.name;
                this.email = department.email;
                this.phone = department.phone;
                this.address = department.address;
                this.city = department.city;
                this.post_code = department.postcode;
                this.mailbox = department.mailbox;
                this.postal_code = department.postal_code;
                this.po_box_city = department.po_box_city;
                this.notes = department.notes;
                this.fax = department.fax;
              this.file=department.documents.file;

                 console.log("documentss", department.documents);
                 for (let i = 0; i < this.department.documents.length; i++) {
                     console.log(this.department.documents);
                     this.document_update_logs.push(
                         Object.assign({}, this.document_update_log)
                     );

                     const document_update_log = this.document_update_logs[i];
                     document_update_log.doc_type = this.department.documents[
                         i
                     ].type;
                     document_update_log.expiry = this.department.documents[
                         i
                     ].expiry_date;
                     document_update_log.file = this.department.documents[
                         i
                     ].file;
                     document_update_log.path = this.department.documents[
                         i
                     ].path;
                    document_update_log.id = this.department.documents[i].id;
               }
                console.log("kashi", document_update_log.file)

                // for (let i = 0; i < this.department.documents.length; i++) {
                //     this.document_update_logs.push(
                //         Object.assign({}, this.document_update_log)
                //     );
                //     const document_update_log = this.document_update_logs[i];
                //     document_update_log.doc_type = this.department.documents[
                //         i
                //     ].type;
                //     document_update_log.expiry = this.department.documents[
                //         i
                //     ].expiry_date;
                //     document_update_log.file = this.department.documents[
                //         i
                //     ].file;
                //     document_update_log.path = this.department.documents[
                //         i
                //     ].path;
                //     document_update_log.id = this.department.documents[i].id;
                // }
            });
    },
    methods: {
        removeUpdateDoc(document_update_log) {
            if (this.document_update_logs.length >= 1) {
                const removedDocId = document_update_log.id;
                this.document_update_logs = this.document_update_logs.filter(
                    log => log !== document_update_log
                );
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
        updateDepartment() {
            axios
                .put(APP_URL + "departments/" + this.department, {
                    name: this.name,
                    address: this.address,
                    post_code: this.post_code,
                    mailbox: this.mailbox,
                    postal_code: this.postal_code,
                    po_box_city: this.po_box_city,
                    email: this.email,
                    fax: this.fax,
                    phone: this.phone,
                    city: this.city,
                    notes: this.notes
                })
                .then(response => {
                     Swal.fire({
                        title: "Success",
                        text: response.data.message,
                        icon: "success"
                    });
                    // Swal.fire(
                    //     "Good job!",
                    //     "Department Updated Successfully!",
                    //     "success"
                    // );
                    let url = this.base_url + "departments";
                    setTimeout(function() {
                        window.location.href = url;
                    }, 1500);

                    this.$emit("Department updated", response.data);
                });
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

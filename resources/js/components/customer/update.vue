<template>
    <div class="container mt-4">
        <h2 class="text-center mb-4">{{ translations.update }}</h2>

        <form @submit.prevent="updateCustomer">
            <!-- FORM FIELDS -->
            <div class="row mb-3">
                <div class="col-lg-6 col-md-12 mb-3">
                    <label class="form-label">{{ translations.name }}:*</label>
                    <input
                        type="text"
                        class="form-control"
                        v-model="name"
                        placeholder="Enter name"
                    />

                    <label class="form-label mt-3"
                        >{{ translations.email }}:*</label
                    >
                    <input
                        type="email"
                        class="form-control"
                        v-model="email"
                        placeholder="Enter email"
                    />

                    <label class="form-label mt-3"
                        >{{ translations.contact }}:</label
                    >
                    <input
                        type="text"
                        class="form-control"
                        v-model="contact"
                        placeholder="Enter contact person"
                    />

                    <label class="form-label mt-3"
                        >{{ translations.phone }}:</label
                    >
                    <input
                        type="text"
                        class="form-control"
                        v-model="phone"
                        placeholder="Enter phone number"
                    />
                </div>

                <div class="col-lg-6 col-md-12 mb-3">
                    <label class="form-label"
                        >{{ translations.address }}:*</label
                    >
                    <input
                        type="text"
                        class="form-control"
                        v-model="address"
                        placeholder="Enter address"
                    />

                    <label class="form-label mt-3"
                        >{{ translations.city }}:</label
                    >
                    <input
                        type="text"
                        class="form-control"
                        v-model="city"
                        placeholder="Enter city"
                    />

                    <label class="form-label mt-3"
                        >{{ translations.country }}:</label
                    >
                    <input
                        type="text"
                        class="form-control"
                        v-model="country"
                        placeholder="Enter country"
                    />

                    <label class="form-label mt-3"
                        >{{ translations.password }}:</label
                    >
                    <input
                        type="password"
                        class="form-control"
                        v-model="password"
                        placeholder="Enter password (if changing)"
                    />
                </div>
            </div>

            <!-- NOTES -->
            <div class="row mb-4">
                <div class="col-lg-6 col-md-12">
                    <label class="form-label">Notes:</label>
                    <textarea
                        class="form-control"
                        v-model="notes"
                        rows="4"
                        placeholder="Enter any notes"
                    ></textarea>
                </div>
            </div>

            <!-- DOCUMENTS -->
            <div class="mb-4">
                <label class="form-label">Documents:</label>
                <div
                    class="row mb-2"
                    v-for="(doc, i) in document_logs"
                    :key="'doc-' + i"
                >
                    <div class="col-md-3 mb-2">
                        <AutoSuggestInput
                            v-model="doc.doc_type"
                            placeholder="Document type"
                        />
                    </div>
                    <div class="col-md-2 mb-2">
                        <input
                            type="date"
                            class="form-control"
                            v-model="doc.expiry"
                        />
                    </div>
                    <div class="col-md-4 mb-2">
                        <input
                            type="file"
                            class="form-control"
                            @change="fileUpload($event, i)"
                        />
                    </div>
                    <div class="col-md-3 mb-2">
                        <a
                            href="#"
                            class="text-danger me-2"
                            @click.prevent="removeDocument(doc)"
                            :class="document_logs.length == 1 ? 'disabled' : ''"
                        >
                            <i class="fa fa-minus-circle"></i>
                        </a>
                        <a
                            v-if="document_logs.length === i + 1"
                            href="#"
                            class="text-success"
                            @click.prevent="addDocument"
                        >
                            <i class="fa fa-plus-circle"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- BUTTONS -->
            <div class="d-flex justify-content-end mb-4">
                <a
                    :href="base_url + 'customers'"
                    class="btn btn-danger me-3"
                    style="width:100px;"
                >
                    {{ translations.cancel }}
                </a>
                <button
                      style="margin-right: 50px;
                           width: 80px;
                           margin-left: 10px;"
                    type="submit"
                    class="btn btn-primary"
                   
                >
                    {{ translations.submit }}
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import Swal from "sweetalert2";

export default {
    props: ["customer", "translations"],
    data() {
        return {
            name: this.customer.name || "",
            email: this.customer.user.email || "",
            contact: this.customer.user.contact_person1 || "",
            phone: this.customer.user.phone || "",
            address: this.customer.user.address || "",
            city: this.customer.user.city || "",
            country: this.customer.user.country || "",
            password: "",
            notes: this.customer.notes || "",

            document_logs: [{ doc_type: "", expiry: "", file: "" }],
            base_url: APP_URL
        };
    },
    methods: {
        addDocument() {
            this.document_logs.push({ doc_type: "", expiry: "", file: "" });
        },
        removeDocument(doc) {
            if (this.document_logs.length > 1) {
                this.document_logs = this.document_logs.filter(d => d !== doc);
            }
        },
        fileUpload(event, index) {
            this.document_logs[index].file = event.target.files[0];
        },
        updateCustomer() {
            if (
                !this.name.trim() ||
                !this.email.trim() ||
                !this.address.trim()
            ) {
                return Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Please fill all * required fields.",
                    confirmButtonText: "OK"
                });
            }

            // Prepare FormData
            const formData = new FormData();
            formData.append("name", this.name);
            formData.append("email", this.email);
            formData.append("contact", this.contact);
            formData.append("phone", this.phone);
            formData.append("address", this.address);
            formData.append("city", this.city);
            formData.append("country", this.country);
            formData.append("password", this.password);
            formData.append("notes", this.notes);

            this.document_logs.forEach((doc, index) => {
                formData.append(
                    `document_logs[${index}][doc_type]`,
                    doc.doc_type
                );
                formData.append(`document_logs[${index}][expiry]`, doc.expiry);
                formData.append(`document_logs[${index}][file]`, doc.file);
            });

            axios
                .post(
                    `${APP_URL}customers/${this.customer.id}?_method=PUT`,
                    formData
                )
                .then(res => {
                    Swal.fire("Success", res.data.message, "success");
                    setTimeout(
                        () =>
                            (window.location.href =
                                this.base_url + "customers"),
                        1200
                    );
                })
                .catch(err => {
                    Swal.fire("Error", "Failed to update customer", "error");
                });
        }
    }
};
</script>

<style scoped>
h2 {
    text-align: center;
    font-weight: 600;
}
label {
    font-weight: 500;
    font-size: 16px;
}
textarea.form-control {
    resize: vertical;
}
</style>

<template>
    <div>
        <h2>{{ translations.add_new }}</h2>
        <form @submit.prevent="createDepartment">
            <div style="margin: 10px;" class="row">
                <div class="col-md-4 form-group">
                    <span>{{ translations.customer }}:*</span><br />
                    <!--div>

                        <input @click="toggleDropdown" class="form-control" type="text" v-model="selectedItem.name" />

                        <div :class="{'dropdown-content': true, 'show': dropdownVisible}" id="myDropdown" >

                            <input type="text" placeholder="Search.." v-model="searchQuery" @keyup="filterFunction" />

                            <div style="max-height: 415px;overflow: scroll;">

                                <a v-for="item in filteredItems" :key="item.id" @click="selectItem(item)" href="javascript:void(0)">
                                    {{ item.name }} (ID: {{ item.id }})
                                </a>
                            </div>
                        </div>
                    </div-->
                    <div>
                        <v-select
                            v-model="selectedCustomer"
                            :options="customers"
                            label="name"
                            :reduce="customer => customer.id"
                            placeholder="Select a customer"
                        ></v-select>
                    </div>

                    <br />

                    <span>{{ translations.department_name }}:*</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="name"
                        type="text"
                        placeholder="Enter name"
                    /><br />

                    <span>{{ translations.address }}:</span><br />
                    <input
                        class="form-control"
                        v-model="address"
                        type="text"
                        placeholder="Enter address"
                    /><br />

                    <span>Post code:</span><br />
                    <input
                        class="form-control"
                        v-model="post_code"
                        type="text"
                        placeholder="Enter post code"
                    /><br />
                </div>

                <div class="col-md-4 form-group">
                    <span>{{ translations.city }}:</span><br />
                    <input
                        class="form-control"
                        v-model="city"
                        type="text"
                        placeholder="Enter city"
                    /><br />
                    <span>{{ translations.mail_box }}:</span><br />
                    <input
                        class="form-control"
                        v-model="mail_box"
                        type="text"
                        placeholder="Enter mail box"
                    /><br />
                    <span>{{ translations.po_box }}:</span><br />
                    <input
                        class="form-control"
                        v-model="po_box"
                        type="text"
                        placeholder="Enter PO box"
                    /><br />
                    <span>{{ translations.po_box_city }}:</span><br />
                    <input
                        class="form-control"
                        v-model="po_box_city"
                        type="text"
                        placeholder="Enter po box city"
                    /><br />
                </div>

                <div class="col-md-4 form-group">
                    <span>{{ translations.phone }}:</span><br />
                    <input
                        class="form-control"
                        v-model="phone"
                        type="number"
                        placeholder="Enter phone"
                    /><br />
                    <span>Fax:</span><br />
                    <input
                        class="form-control"
                        v-model="fax"
                        type="text"
                        placeholder="Enter fax"
                    /><br />
                    <span>Email:</span><br />
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
                    />
                    <br />
                </div>
            </div>
            <!-- documents upload input -->
            <span>Upload Documents:</span>
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
                        accept=".pdf,.csv,.xls,.xlsx,.docx,text/csv,application/pdf,application/csv,text/comma-separated-values,application/csv,application/excel,
                               application/vnd.msexcel,text/anytext,application/vnd.ms-excel,
                               application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                        type="file"
                        @change="fileUpload($event, i)"
                    />
                </div>
                <div class="col-md-3 mt-2">
                    <a
                        v-if="document_logs.length > 1"
                        href="#"
                        class="text-danger"
                        @click.prevent="removeDocument(i)"
                    >
                        <i class="fa fa-minus-circle"></i>
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
                        {{ translations.submit }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
import Toastify from "toastify-js";
import Swal from "sweetalert2";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
    components: {
        vSelect
    },
    props: ["customers", "translations"],
    data() {
        return {
            dropdownVisible: false,
            searchQuery: "",
            selectedCustomer: "",
            message: "",
            name: "",
            address: "",
            post_code: "",
            mail_box: "",
            po_box: "",
            po_box_city: "",
            email: "",
            fax: "",
            phone: "",
            city: "",
            country: "",
            notes: "",
            selected: null,
            selectedItem: {
                id: null,
                name: ""
            },
            document_log: {
                doc_type: "",
                expiry: "",
                file: ""
            },
            document_logs: [],
            key_value: 0,
            base_url: APP_URL
        };
    },
    mounted() {
        console.log("Mounted");
        this.document_logs.push(Object.assign({}, this.document_log));
    },
    computed: {
        filteredItems() {
            return this.items.filter(item => {
                const query = this.searchQuery.toLowerCase();
                return (
                    item.name.toLowerCase().includes(query) ||
                    item.id.toString().includes(query)
                );
            });
        }
    },
    methods: {
        selectItem(item) {
            this.selectedItem = { ...item };
            this.dropdownVisible = false;
        },
        toggleDropdown() {
            this.dropdownVisible = !this.dropdownVisible;
        },
        addDocument() {
            this.document_logs.push({
                doc_type: "",
                expiry: "",
                file: null
            });
        },
        removeDocument(index) {
            if (this.document_logs.length > 1) {
                this.document_logs.splice(index, 1);
            }
        },
        fileUpload(event) {
            this.document_logs.push(Object.assign({}, this.document_log));
            const document_log = this.document_logs[this.key_value];
            document_log.file = event.target.files[0];

            console.log("Uploaded File: ", event.target.files[0]);

            this.key_value += 1;
        },
        createDepartment() {
            let formData = new FormData();
            formData.append("name", this.name);
            formData.append("address", this.address);
            formData.append("post_code", this.post_code);
            formData.append("mail_box", this.mail_box);
            formData.append("po_box", this.po_box);
            formData.append("po_box_city", this.po_box_city);
            formData.append("email", this.email);
            formData.append("fax", this.fax);
            formData.append("phone", this.phone);
            formData.append("city", this.city);
            formData.append("country", this.country);
            formData.append("selected", this.selectedCustomer);
            formData.append("notes", this.notes);

            this.document_logs.forEach((log, index) => {
                formData.append(
                    `document_logs[${index}][doc_type]`,
                    log.doc_type
                );
                formData.append(`document_logs[${index}][expiry]`, log.expiry);
                formData.append(`document_logs[${index}][file]`, log.file);
            });

            axios
                .post(APP_URL + "/departments", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    }
                })
                .then(response => {
                    this.message = response.data.message;
                    this.showToast(this.message);
                    this.$emit("Department created", response.data);

                    // Reset fields
                    this.name = "";
                    this.address = "";
                    this.post_code = "";
                    this.mail_box = "";
                    this.po_box = "";
                    this.po_box_city = "";
                    this.email = "";
                    this.fax = "";
                    this.phone = "";
                    this.city = "";
                    this.country = "";
                    this.selected = null;
                    this.notes = "";
                    this.document_logs = [Object.assign({}, this.document_log)];
                    this.key_value = 0;

                    Swal.fire(
                        "Good job!",
                        (this.message = response.data.message),
                        "success"
                    );

                    let url = this.base_url + "departments";
                    setTimeout(function() {
                        window.location.href = url;
                    }, 1500);
                })
                .catch(err => {
                    console.log("Error:", err.message);
                    Swal.fire(err.message, "Invalid input", "error");
                });
        },
        showToast(message) {
            Toastify({
                text: message,
                duration: 3000,
                newWindow: true,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                stopOnFocus: true
            }).showToast();
        }
    }
};
</script>

<!-- <script>
import Toastify from 'toastify-js';
import Swal from "sweetalert2";
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

export default {
    components: {
        vSelect,
    },
    props: ['customers','translations'],
    data() {
        console.log(this.selected);
        return {
            dropdownVisible: false,
            searchQuery: '',
            // items: this.customers.map(customer => ({
            //    id: customer.id,
            //    name: customer.name
            // })),
            selectedCustomer:"",
            message: "",
            name: "",
            address: "",
            post_code: "",
            mail_box: "",
            po_box: "",
            po_box_city: "",
            email: "",
            fax: "",
            phone: "",
            city: "",
            country: "",
            notes: "",
            selected: null,
            selectedItem: {
                id: null,
                name: ''
            },
            base_url:APP_URL,
        };
    },
    mounted() {
        console.log("Mounted");
    },
    computed: {
        filteredItems() {
            return this.items.filter(item => {
                const query = this.searchQuery.toLowerCase();
                return item.name.toLowerCase().includes(query) || item.id.toString().includes(query);
            });
        }
    },
    methods: {
        selectItem(item) {
            this.selectedItem = { ...item };
            this.dropdownVisible = false;
        },
        toggleDropdown() {
            this.dropdownVisible = !this.dropdownVisible;
        },
        filterFunction() {
            // This method can be left empty because the filtering is handled by the computed property `filteredItems`
        },
        createDepartment() {
            console.log(this.selected);
            axios.post(APP_URL + 'departments', {
                name: this.name,
                address: this.address,
                post_code: this.post_code,
                mail_box: this.mail_box,
                po_box: this.po_box,
                po_box_city: this.po_box_city,
                email: this.email,
                fax: this.fax,
                phone: this.phone,
                city: this.city,
                country: this.country,
                selected: this.selectedCustomer,
                notes: this.notes,
            }).then((response) => {
                this.message = response.data.message
                this.showToast(this.message);
                this.$emit('Department created', response.data);
                this.name = "";
                this.address = "";
                this.post_code = "";
                this.mail_box = "";
                this.po_box = "";
                this.po_box_city = "";
                this.email = "";
                this.fax = "";
                this.phone = "";
                this.city = "";
                this.country = "";
                this.selected = null;
                this.notes = "";
                Swal.fire(
                    'Good job!',
                    'Department created Successfully!',
                    'success'
                )
                let url = this.base_url + 'departments';
                setTimeout(function() {
                    window.location.href = url;
                }, 1500);
            });
        },

        showToast(message) {
            Toastify({
                text: message,
                duration: 3000,
                newWindow: true,
                close: true,
                gravity: 'top',
                position: 'right',
                backgroundColor: 'linear-gradient(to right, #00b09b, #96c93d)',
                stopOnFocus: true,
            }).showToast();
        }
    },
};
</script> -->
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
.dropdown {
    position: relative;
    display: inline-block;
}
.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f1f1f1;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
}
.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}
.dropdown-content a:hover {
    background-color: #ddd;
}
.show {
    display: block;
}
</style>

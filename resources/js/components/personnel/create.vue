<template>
    <div>
        <br />
        <h2>{{ translations.add_new }}</h2>
        <div style="margin: 20px;">
            <h4>PERSONAL DATA</h4>
            <div class="row">
                <div class="col-md-6 form-group">
                    <span>Salutation:*</span><br />
                    <select class="form-control" v-model="salutation">
                        <option :value="1">Mr</option>
                        <option :value="2">Ms</option>
                    </select>
                    <br />
                    <span>Gender:*</span><br />
                    <select class="form-control" v-model="gender">
                        <option :value="1">Male</option>
                        <option :value="2">Female</option>
                        <option :value="3">Other</option>
                    </select>
                    <br />

                    <span>{{ translations.initials }}:</span><br />
                    <input
                        class="form-control"
                        v-model="initials"
                        type="text"
                        placeholder="Enter Initials"
                    /><br />

                    <span>{{ translations.first_name }}:*</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="first_name"
                        type="text"
                        placeholder="Enter First name"
                    /><br />

                    <span>{{ translations.last_name }}:*</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="last_name"
                        type="text"
                        placeholder="Enter Last name"
                    /><br />

                    <span>{{ translations.dob }}:*</span><br />
                    <input
                        class="form-control"
                        v-model="dob"
                        type="date"
                        placeholder="Enter Date of birth"
                    />
                </div>

                <div class="col-md-6 form-group">
                    <span>{{ translations.date_service }}:</span><br />
                    <input
                        class="form-control"
                        v-model="date_service"
                        type="date"
                        placeholder="Enter Date service"
                        style="margin-top: -1px;"
                    /><br />
                    <span>{{ translations.id_type }}:*</span><br />
                    <select
                        class="form-control"
                        v-model="id_type"
                        style="margin-top: -2.3px;"
                    >
                        <option :value="1">Passport</option>
                        <option :value="2">ID Card</option>
                        <option :value="3">Anders</option>
                    </select>
                    <br />
                    <span>{{ translations.id_number }}:*</span><br />
                    <input
                        class="form-control"
                        v-model="id_number"
                        type="text"
                        placeholder="Enter Id Number"
                        style="margin-top: 1.5px;"
                    /><br />
                    <span>{{ translations.expiry_date }}:*</span><br />
                    <input
                        class="form-control"
                        v-model="expiration_date"
                        type="date"
                        placeholder="Enter Expiration date"
                        style="margin-top: 5px;"
                    /><br />

                    <span>{{ translations.nationality }}*</span><br />
                    <v-select
                        v-model="nationality"
                        :options="nationalities"
                        label="name"
                        :reduce="national => national.name"
                        placeholder="Select a nationality"
                        style="margin-top: 5px;"
                    ></v-select>
                    <br/>
                    <span>Staff type:</span><br />
                    <select
                        class="form-control mt-2"
                        v-model="staff_type_id"
                       
                    >
                        <option
                            v-for="staff_type in staff_types"
                            :value="staff_type.id"
                            >{{ staff_type.name }}</option
                        >
                    </select>
                    <br />
                </div>
            </div>
        </div>

        <div style="margin: 20px;">
            <div class="row">
                <div class="col-md-6 form-group">
                    <span>{{ translations.mobile }}:</span><br />
                    <input
                        class="form-control"
                        v-model="mobile"
                        @input="formatMobile('mobile')"
                        type="text"
                        placeholder="Enter Mobile"
                    /><br />

                    <span>{{ translations.mobile }}2:</span><br />
                    <input
                        class="form-control"
                        v-model="mobile2"
                        @input="formatMobile('mobile2')"
                        type="text"
                        placeholder="Enter Mobile 2"
                    /><br />

                    <span>{{ translations.mobile }}3:</span><br />
                    <input
                        class="form-control"
                        v-model="mobile3"
                        @input="formatMobile('mobile3')"
                        type="text"
                        placeholder="Enter Mobile 3"
                    /><br />

                    <span>{{ translations.email }}:*</span><br />
                    <input
                        class="form-control"
                        v-model="email"
                        type="email"
                        placeholder="Enter Email"
                    /><br />

                    <span>{{ translations.password }}:*</span><br />
                    <input
                        class="form-control"
                        v-model="password"
                        type="password"
                        placeholder="Enter Password"
                    /><br />
                </div>

                <div class="col-md-6 form-group">
                    <span>{{ translations.telephone }}:</span><br />
                    <input
                        class="form-control"
                        v-model="telephone"
                        type="number"
                        placeholder="Enter telephone"
                    /><br />

                    <span>{{ translations.address }}:*</span><br />
                    <input
                        class="form-control"
                        v-model="address"
                        type="text"
                        placeholder="Enter address"
                    /><br />
                    <span>{{ translations.post_code }}:*</span><br />
                    <input
                        class="form-control"
                        v-model="postcode"
                        type="text"
                        placeholder="Enter Postcode"
                    /><br />
                    <span>{{ translations.city }}:*</span><br />
                    <input
                        class="form-control"
                        v-model="city"
                        type="text"
                        placeholder="Enter City"
                    /><br />
                </div>
            </div>
        </div>

        <div style="margin: 20px;">
            <div class="row">
                <div class="col-md-6 form-group">
                    <span>{{ translations.emp_agency }}:*</span><br />
                    <v-select
                        v-model="employment_agency"
                        :options="agencies"
                        label="name"
                        :reduce="agency => agency.id"
                        placeholder="Select an agency"
                    ></v-select>
                    <br />

                    <span>{{ translations.emp_agency_note }}:</span><br />
                    <textarea
                        class="form-control"
                        v-model="employment_agency_note"
                        type="text"
                        placeholder="Employment agency note:"
                    /><br />

                    <span>{{ translations.rate }}:*</span><br />
                    <input
                        class="form-control"
                        v-model="rate_per_hour"
                        type="number"
                        placeholder="Rate p/h"
                    /><br />

                    <span>{{ translations.cost }}:*</span><br />
                    <input
                        class="form-control"
                        v-model="cost_per_hour"
                        type="number"
                        placeholder="Enter Costs p/h"
                    /><br />
                    <span>{{ translations.ssn }}:*</span><br />
                    <input
                        class="form-control"
                        v-model="social_security_number"
                        type="text"
                        placeholder="Enter Social Security Number"
                    />
                    <br />
                    <span>Photo:</span><br />
                    <input type="file" @change="handleFileChange" />
                </div>

                <div class="col-md-6 form-group mb-4">
                    <span>{{ translations.active }}:*</span>
                    <input
                        style="width: 35px;"
                        class="form-control"
                        v-model="active"
                        type="checkbox"
                    /><br />
                    <span>{{ translations.vca }}:</span><br />
                    <input
                        style="width: 35px;"
                        class="form-control"
                        v-model="vca_certificate"
                        type="checkbox"
                    /><br />

                    <div v-if="vca_certificate">
                        <span>{{ translations.vca_expiry }}*:</span><br />
                        <input
                            class="form-control"
                            v-model="vca_expiry"
                            type="date"
                            placeholder="VCA expiry date"
                            required
                        /><br />
                        <span>{{ translations.vca_number }}*:</span><br />
                        <input
                            class="form-control"
                            v-model="vca_number"
                            type="text"
                            placeholder="VCA Number"
                            required
                        /><br />
                    </div>
                    <span>{{ translations.own_car }}:</span><br />
                    <input
                        style="width: 35px;"
                        class="form-control"
                        v-model="own_car"
                        type="checkbox"
                    /><br />

                    <span>{{ translations.suitable_for }}:</span><br />
                    <div class="ahmad">
                        <multi-select
                            label="Select Options"
                            v-model="selectedOptions"
                            :options="e_functions"
                        ></multi-select>
                    </div>
                    <br />
                </div>
            </div>
            <span>Upload Documents:</span>
            <div
                class="row"
                v-for="(personnel_log, i) in personnel_logs"
                :key="i"
            >
                <div class="col-md-3">
                    <AutoSuggestInput
                        v-model="personnel_log.doc_type"
                        placeholder="Document type"
                    />
                </div>
                <div class="col-md-2">
                    <input
                        class="form-control"
                        v-model="personnel_log.expiry"
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
                            personnel_logs.length !== i + 1 ||
                                personnel_logs.length === i + 1
                        "
                        href="#"
                        :class="personnel_logs.length == 1 ? `disabled` : ''"
                        class="text-danger"
                        @click.prevent="removePersonnel(personnel_log)"
                    >
                        <i class="text-danger fa fa-minus-circle"></i>
                    </a>
                    <a
                        v-if="personnel_logs.length === i + 1"
                        href="#"
                        class="text-success"
                        @click.prevent="addPersonnel"
                    >
                        <i class="fa fa-plus-circle bg-plus"></i>
                    </a>
                </div>
            </div>

            <div class="row">
                <div style="margin: 10px; text-align: end" class="col-lg-12">
                    <a
                        style="margin-top: 10px;
                           width: 80px; "
                        class="btn btn-primary"
                        @click="pageDecrement"
                        >{{ common.back }}</a
                    >
                    <button
                        style="margin-right: 50px;
                           width: 80px;
                           margin-left: 10px;"
                        type="submit"
                        @click="createPersonnel"
                        :disabled="disabled"
                        class="submit btn btn-primary"
                    >
                        {{ translations.submit }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Toastify from "toastify-js";
import Swal from "sweetalert2";
import MultiSelect from "./MultiSelect.vue";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
    beforeMount() {
        this.personnel_logs.push(Object.assign({}, this.personnel_log));
    },
    components: {
        MultiSelect,
        vSelect
    },
    props: [
        "agencies",
        "nationalities",
        "e_functions",
        "translations",
        "staff_types",
        "common"
    ],
    data() {
        return {
            selectedFile: null,
            imageUrl: null,
            selectedOptions: [],
            currentPage: 1,
            disabled: false,
            message: [],
            initials: "",
            first_name: "",
            last_name: "",
            salutation: "",
            dob: "",
            social_security_number: "",
            date_service: "",
            staff_type_id: 1,
            id_type: "",
            id_number: "",
            expiration_date: "",
            nationality: null,
            mobile: "",
            mobile2: "",
            mobile3: "",
            email: "",
            password: "",
            telephone: "",
            address: "",
            postcode: "",
            city: "",
            employment_agency: "",
            employment_agency_note: "",
            rate_per_hour: "",
            cost_per_hour: "",
            dates: "",
            vca_certificate: 0,
            vca_number: "",
            vca_expiry: "",
            own_car: 0,
            active: 1,
            function_id: "",
            gender: "",
            personnel_log: {
                doc_type: "",
                expiry: "",
                file: ""
            },
            personnel_logs: [],
            errors: [],
            key_value: 0,
            base_url: APP_URL
        };
    },
    mounted() {
        console.log("Component Mounted");
    },
    methods: {
        pageIncrement() {
            this.currentPage++;
        },
        pageDecrement() {
            this.currentPage--;
        },
        addPersonnel() {
            this.personnel_logs.push(Object.assign({}, this.personnel_log));
        },
        removePersonnel(row) {
            if (this.personnel_logs.length > 1) {
                this.personnel_logs = this.personnel_logs.filter(
                    personnel_log => personnel_log !== row
                );
            }
        },
        handleFileChange(event) {
            this.imageUrl = event.target.files[0];
        },
        fileUpload(event) {
            this.personnel_logs.push(Object.assign({}, this.personnel_log));
            const personnel_log = this.personnel_logs[this.key_value];
            personnel_log.file = event.target.files[0];
            this.key_value += 1;
        },
        createPersonnel() {
            this.disabled = true;
            let formData = new FormData();

            formData.append("initials", this.initials);
            formData.append("first_name", this.first_name);
            formData.append("last_name", this.last_name);
            formData.append("dob", this.dob);
            formData.append(
                "social_security_number",
                this.social_security_number
            );
            formData.append("date_service", this.date_service);
            formData.append("id_type", this.id_type);
            formData.append("id_number", this.id_number);
            formData.append("expiration_date", this.expiration_date);
            formData.append("nationality", this.nationality);
            formData.append("mobile", this.mobile);
            formData.append("mobile2", this.mobile2);
            formData.append("mobile3", this.mobile3);
            formData.append("email", this.email);
            formData.append("password", this.password);
            formData.append("telephone", this.telephone);
            formData.append("address", this.address);
            formData.append("postcode", this.postcode);
            formData.append("city", this.city);
            formData.append("employment_agency", this.employment_agency);
            formData.append("staff_type_id", this.staff_type_id);
            formData.append(
                "employment_agency_note",
                this.employment_agency_note
            );
            formData.append("rate_per_hour", this.rate_per_hour);
            formData.append("cost_per_hour", this.cost_per_hour);
            formData.append("dates", this.dates);
            formData.append("vca_certificate", this.vca_certificate);
            formData.append("vca_number", this.vca_number);
            formData.append("vca_expiry", this.vca_expiry);
            formData.append("own_car", this.own_car);
            formData.append("active", this.active);
            formData.append("function_id", this.function_id);
            formData.append("gender", this.gender);
            formData.append("salutation", this.salutation);
            formData.append("picture", this.imageUrl);

            this.selectedOptions.forEach(id => {
                formData.append("selectedOptions[]", id);
            });

            this.personnel_logs.forEach((log, index) => {
                formData.append(
                    `personnel_logs[${index}][doc_type]`,
                    log.doc_type
                );
                formData.append(`personnel_logs[${index}][expiry]`, log.expiry);
                formData.append(`personnel_logs[${index}][file]`, log.file);
            });

            axios
                .post(APP_URL + "personnels", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    }
                })
                .then(response => {
                    this.disabled = false;
                    this.message = response.data.message;

                    Swal.fire(
                        "Good job!",
                        (this.message = response.data.message),
                        "success"
                    ).then(result => {
                        if (
                            result.isConfirmed ||
                            result.dismiss === Swal.DismissReason.close
                        ) {
                            window.location.href = `${APP_URL}/personnels`;
                        }
                    });
                })
                .catch(error => {
                    this.disabled = false;

                    if (error.response && error.response.status === 422) {
                        const errors = error.response.data.metadata.message;

                        if (errors.first_name && errors.first_name.length > 0) {
                            this.message = errors.first_name[0];
                        } else if (
                            errors.last_name &&
                            errors.last_name.length > 0
                        ) {
                            this.message = errors.last_name[0];
                        } else if (errors.email && errors.email.length > 0) {
                            this.message = errors.email[0];
                        } else {
                            this.message = "Invalid input";
                        }
                    } else if (
                        error.response &&
                        error.response.data.metadata.message
                    ) {
                        this.message = error.response.data.metadata.message;
                    } else {
                        this.message = "Unknown error occurred";
                    }

                    Swal.fire(this.message, "", "error");
                });
        },

        formatMobile(field) {
            let value = this[field].replace(/\D/g, "");
            if (value.length > 4) {
                this[field] = `${value.slice(0, 4)}-${value.slice(4, 11)}`;
            } else {
                this[field] = value;
            }
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

.ahmad {
    height: 200px;
    width: 300px;
    overflow-y: scroll;
}


.form-group input.form-control,
.form-group select.form-control,
.form-group textarea.form-control,
.form-group .v-select {
    margin-top: 10px !important;
    margin-bottom: 10px !important;
}
</style>

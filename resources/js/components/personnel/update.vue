<template>
    <div>
        <div class="row mt-3 ml-2 mr-2 mb-2">
            <h2 class="col">{{ translations.update_staff }}</h2>
            <a
                :href="base_url + 'personnel-work-history/' + this.personnel.id"
                class="col-1 mt-0 btn btn-xs btn-success float-right"
                style="width: 100px;"
                >Work History</a
            >
        </div>
        <div style="margin: 20px;">
            <div class="row">
                <div class="col-md-6 form-group">
                    <span>Salutation:</span><br />
                    <select
                        class="form-control"
                        v-model="salutation"
                        style="margin-left: 5px;"
                    >
                        <option :value="1">Mr</option>
                        <option :value="2">Ms</option> </select
                    ><br />

                    <span class="mt-0">{{ translations.initials }}:</span><br />
                    <input
                        class="mt-0 form-control"
                        v-model="initials"
                        type="text"
                        placeholder="Enter Initials"
                    /><br />

                    <span>{{ translations.first_name }}:*</span><br />
                    <input
                        class="mt-0 form-control"
                        required
                        v-model="first_name"
                        type="text"
                        placeholder="Enter First name"
                    /><br />

                    <span>{{ translations.last_name }}:*</span><br />
                    <input
                        class="mt-0 form-control"
                        required
                        v-model="last_name"
                        type="text"
                        placeholder="Enter Last name"
                    /><br />

                    <span>{{ translations.dob }}:*</span><br />
                    <input
                        class="mt-0 form-control"
                        required
                        v-model="dob"
                        type="date"
                        placeholder="Enter Date of birth"
                    /><br />
                </div>

                <div class="col-md-6 form-group">
                    <span>{{ translations.date_service }}:</span><br />
                    <input
                        class="form-control"
                        v-model="date_service"
                        type="date"
                        placeholder="Enter Date service"
                        style="margin-top: -0.8px;"
                    /><br />
                    <span>{{ translations.id_type }}:*</span><br />
                    <select
                        class="form-control"
                        v-model="id_type"
                        style="margin-top: -2.5px;"
                    >
                        <option :value="1">Passport</option>
                        <option :value="2">ID Card</option>
                        <option :value="3">Anders</option>
                    </select>
                    <br />
                    <span>{{ translations.id_number }}:*</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="id_number"
                        type="text"
                        placeholder="Enter Id Number"
                    /><br />
                    <span>{{ translations.expiry_date }}:*</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="expiration_date"
                        type="date"
                        placeholder="Enter Expiration date"
                        style="margin-top: -0.5px;"
                    /><br />
                    <span>{{ translations.nationality }}:*</span><br />
                    <!--select class="form-control" v-model="nationality">
                            <option v-for="national in nationalities" :value="national.name">{{ national.name }}</option>
                        </select-->

                    <v-select
                        v-model="nationality"
                        :options="nationalities"
                        label="name"
                        :reduce="national => national.name"
                        placeholder="Select a nationality"
                    ></v-select>
                </div>
            </div>
        </div>

        <div style="margin: 20px;">
            <div class="row">
                <div class="col-md-6 form-group">
                    <span>{{ translations.mobile }}:*</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="mobile"
                        type="text"
                        placeholder="Enter mobile"
                    /><br />

                    <span>{{ translations.mobile }}2:</span><br />
                    <input
                        class="form-control"
                        v-model="mobile2"
                        type="number"
                        placeholder="Enter Mobile 2"
                    /><br />

                    <span>{{ translations.mobile }}3:</span><br />
                    <input
                        class="form-control"
                        v-model="mobile3"
                        type="number"
                        placeholder="Enter Mobile 3"
                    /><br />

                    <span>{{ translations.email }}:*</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="email"
                        type="email"
                        placeholder="Enter email address"
                    /><br />
                    <span>{{ translations.reset_password }}</span
                    ><br />
                    <input
                        class="form-control"
                        required
                        v-model="password"
                        type="text"
                        placeholder="Reset Password"
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
                        required
                        v-model="address"
                        type="text"
                        placeholder="Enter address"
                    /><br />
                    <span>{{ translations.post_code }}:*</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="postcode"
                        type="text"
                        placeholder="Enter Postcode"
                    /><br />
                    <span>{{ translations.city }}:*</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="city"
                        type="text"
                        placeholder="Enter City"
                    /><br />
                    <span>Staff type:</span><br />
                    <select
                        class="form-control"
                        v-model="staff_type_id"
                        style="margin-top: 4px;"
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
                    <span>{{ translations.emp_agency }}:*</span><br />
                    <!--select class="form-control" v-model="employment_agency">
                            <option v-for="agency in agencies" :value="agency.id">{{ agency.name }}</option>
                        </select-->

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

                    <span>{{ translations.rate }}*</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="rate_per_hour"
                        type="number"
                        placeholder="Rate p/h"
                    /><br />

                    <span>{{ translations.cost }}*</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="cost_per_hour"
                        type="number"
                        placeholder="Enter Costs p/h"
                    /><br />
                    <span>{{ translations.ssn }}:*</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="social_security_number"
                        type="text"
                        placeholder="Enter Social security number"
                    />
                    <!-- <span>Photo:</span><br>
                        <input type="file" @change="handleFileChange" /> -->

                    <div>
                        <!-- Show the old image before updating -->
                        <div v-if="oldImage && !selectedImage">
                            <!-- <label style="display: block;">Previous Image:</label> -->
                            <img
                                :src="oldImage"
                                alt="Previous Image"
                                width="100px"
                                height="110px"
                                class="rounded shadow m-5"
                            />
                        </div>
                        <label>Photo:</label><br />
                        <input type="file" @change="handleFileChange" />

                        <!-- Show the new image if selected -->
                        <div v-if="selectedImage">
                            <img
                                :src="selectedImage"
                                alt="New Image"
                                width="30"
                            />
                        </div>
                    </div>
                </div>

                <div class="col-md-6 form-group">
                    <span>{{ translations.active }}:*</span><br />
                    <input
                        style="width: 35px;"
                        class="form-control"
                        required
                        v-model="active"
                        type="checkbox"
                    />
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
                    <div
                        class="ml-3 row"
                        v-for="(option, i) in personnel_function_names"
                    >
                        {{ option }}
                    </div>
                    <div class="ahmad">
                        <multi-select
                            v-model="selectedOptions"
                            :options="e_functions"
                        ></multi-select>
                    </div>
                </div>
            </div>
            <span>Documents:</span>
            <div
                class="row"
                v-for="(personnel_update_log, i) in personnel_update_logs"
                :key="i"
            >
                <div class="col-md-3">
                    <AutoSuggestInput
                        v-model="personnel_update_log.doc_type"
                        placeholder="Document type"
                    />
                </div>
                <!-- <div class="col-md-3">
                    <input
                        class="form-control"
                        v-model="personnel_update_log.doc_type"
                        type="text"
                        placeholder="Document types"
                    />
                </div> -->
                <div class="col-md-2">
                    <input
                        class="form-control"
                        v-model="personnel_update_log.expiry"
                        type="date"
                    />
                </div>
                <div class="col-md-3">
                    <a :href="personnel_update_log.path" target="_blank"
                        >@{{ personnel_update_log.file }}</a
                    >
                </div>
                <div class="col-md-1">
                    <a :href="personnel_update_log.path" download
                        ><i class="fas fa-download"></i
                    ></a>
                </div>
                <div class="col-md-3 mt-2">
                    <a
                        v-if="
                            personnel_update_logs.length !== i + 1 ||
                                personnel_update_logs.length === i + 1
                        "
                        href="#"
                        :class="
                            personnel_update_logs.length == 1 ? `disabled` : ''
                        "
                        class="text-danger"
                        @click.prevent="removeUpdateDoc(personnel_update_log)"
                    >
                        <i class="text-danger fa fa-minus-circle"></i>
                    </a>
                </div>
            </div>

            <div
                class="row"
                v-for="(personnel_log, i) in personnel_logs"
                :key="i"
            >
                <div class="col-md-3">
                    <AutoSuggestInput
                        v-model="personnel_update_log.doc_type"
                        placeholder="Document type"
                    />
                    <!-- <input
                        class="form-control"
                        v-model="personnel_log.doc_type"
                        type="text"
                        placeholder="Document types"
                    /> -->
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
                <div style="text-align: end" class="col-lg-12">
                    <a
                        style="margin-top: 10px;
                           width: 80px; "
                        class="btn btn-primary"
                        @click="pageDecrement"
                        >{{ common.back }}</a
                    >
                    <button
                        style="margin-right: 10px;
                           width: 80px;
                           margin-left: 10px;"
                        type="submit"
                        class="submit btn btn-primary"
                        @click.prevent="updatePersonnel"
                    >
                        {{ translations.update }}
                    </button>

                    <button
                        style="margin-right: 10px; width: 155px;"
                        type="submit"
                        class="submit btn btn-primary"
                        @click.prevent="updateAndClose"
                    >
                        {{ translations.update_and_close }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";
import Swal from "sweetalert2";
import MultiSelect from "./MultiSelect.vue";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
    props: [
        "personnel",
        "personnel_functions",
        "personnel_function_names",
        "common",
        "nationalities",
        "translations",
        "agencies",
        "staff_types",
        "e_functions"
    ],
    components: {
        MultiSelect,
        vSelect
    },
    data() {
        return {
            oldImage: "",
            selectedImage: null,
            selectedOptions: this.personnel_functions,
            message: "",
            imageUrl: null,
            initials: "",
            first_name: "",
            last_name: "",
            dob: "",
            social_security_number: "",
            date_service: "",
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
            salutation: "",
            employment_agency: "",
            employment_agency_note: "",
            rate_per_hour: "",
            cost_per_hour: "",
            dates: "",
            vca_certificate: false,
            vca_number: "",
            vca_expiry: "",
            own_car: false,
            active: false,
            function_id: "",
            staff_type_id: "",
            gender: null,
            removedDocumentIds: [],
            personnel_update_logs: [],
            personnel_update_log: {
                doc_type: "",
                expiry: "",
                file: "",
                path: "",
                id: ""
            },
            personnel_log: {
                doc_type: "",
                expiry: "",
                file: ""
            },
            key_value: 0,
            personnel_logs: [],
            base_url: APP_URL
        };
    },
    beforeMount() {
        this.personnel_logs.push(Object.assign({}, this.personnel_log));
    },
    mounted() {
        this.oldImage = this.personnel.picture
            ? `${APP_URL}/storage/personnel_pictures/${this.personnel.picture}`
            : null;
        console.log("documents:", this.personnel.documents);
        for (let i = 0; i < this.personnel.documents.length; i++) {
            this.personnel_update_logs.push(
                Object.assign({}, this.personnel_update_log)
            );
            const personnel_update_log = this.personnel_update_logs[i];
            personnel_update_log.doc_type = this.personnel.documents[i].type;
            personnel_update_log.expiry = this.personnel.documents[
                i
            ].expiry_date;
            personnel_update_log.file = this.personnel.documents[i].file;
            personnel_update_log.path = this.personnel.documents[i].path;
            personnel_update_log.id = this.personnel.documents[i].id;
        }

        if (this.personnel.vca_certificate == 1) {
            this.vca_certificate = true;
            this.vca_number = this.personnel.vca_number || "";
            this.vca_expiry = this.personnel.vca_expiry || "";
        }

        if (this.personnel.own_car == 1) {
            this.own_car = true;
        }

        if (this.personnel.active == 1) {
            this.active = true;
        }

        this.salutation = this.personnel.salutation || "";
        this.initials = this.personnel.initials || "";
        this.first_name = this.personnel.first_name || "";
        this.last_name = this.personnel.last_name || "";
        this.dob = this.personnel.dob || "";
        this.social_security_number =
            this.personnel.social_security_number || "";
        this.date_service = this.personnel.date_service || "";
        this.id_type = this.personnel.id_type || "";
        this.id_number = this.personnel.id_number || "";
        this.expiration_date = this.personnel.id_expiry || "";
        this.nationality = this.personnel.nationality || "";
        this.mobile = this.personnel.mobile || "";
        this.mobile2 = this.personnel.mobile2 || "";
        this.mobile3 = this.personnel.mobile3 || "";
        this.email = this.personnel.email || "";
        this.telephone = this.personnel.telephone || "";
        this.address = this.personnel.address || "";
        this.postcode = this.personnel.postcode || "";
        this.city = this.personnel.city || "";
        this.employment_agency = this.personnel.employ_agency_id || "";
        this.staff_type_id = this.personnel.staff_type.id || "";
        this.employment_agency_note =
            this.personnel.employment_agency_note || "";
        this.rate_per_hour = this.personnel.rate_per_hour || "";
        this.cost_per_hour = this.personnel.cost_per_hour || "";
        this.dates = this.personnel.this.personnel_dates || "";
        this.gender = this.personnel.gender || "";
    },
    methods: {
        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.selectedImage = URL.createObjectURL(file);
                file && file.type.startsWith("image/");
            }
        },
        pageIncrement() {
            this.currentPage++;
        },
        pageDecrement() {
            this.currentPage--;
        },
        handleFileChange(event) {
            this.imageUrl = event.target.files[0];
        },
        removeUpdateDoc(personnel_update_log) {
            if (this.personnel_update_logs.length >= 1) {
                // Extract doc_type before removing
                const removedDocId = personnel_update_log.id;

                // Filter out the personnel_update_log from the array
                this.personnel_update_logs = this.personnel_update_logs.filter(
                    log => log !== personnel_update_log
                );

                // Store the removed doc_type in the array
                this.removedDocumentIds.push(removedDocId);
            }
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
        fileUpload(event) {
            this.personnel_logs.push(Object.assign({}, this.personnel_log));

            const personnel_log = this.personnel_logs[this.key_value];
            personnel_log.file = event.target.files[0];

            console.log("FILEEEEEEEEEEEEE" + event.target);

            this.key_value = this.key_value + 1;
        }, //fileUpload
        updatePersonnel() {
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
            formData.append("vca_certificate", this.vca_certificate ? 1 : 0);
            formData.append(
                "vca_number",
                this.vca_certificate ? this.vca_number : ""
            );
            formData.append(
                "vca_expiry",
                this.vca_certificate ? this.vca_expiry : ""
            );
            formData.append("own_car", this.own_car ? 1 : 0);
            formData.append("active", this.active ? 1 : 0);
            formData.append("function_id", this.function_id);
            formData.append("gender", this.gender);
            formData.append("salutation", this.salutation);
            formData.append("picture", this.imageUrl);
            this.selectedOptions.forEach(id => {
                formData.append("selectedOptions[]", id);
            });
            this.removedDocumentIds.forEach(id => {
                formData.append("removedDocumentIds[]", id);
            });
            this.personnel_logs.forEach((log, index) => {
                formData.append(
                    `personnel_logs[${index}][doc_type]`,
                    log.doc_type
                );
                formData.append(`personnel_logs[${index}][expiry]`, log.expiry);
                formData.append(`personnel_logs[${index}][file]`, log.file);
            });

            this.personnel_update_logs.forEach((log, index) => {
                formData.append(
                    `personnel_update_logs[${index}][doc_type]`,
                    log.doc_type || ""
                );
                formData.append(
                    `personnel_update_logs[${index}][expiry]`,
                    log.expiry || ""
                );
                formData.append(`personnel_update_logs[${index}][id]`, log.id);
            });

            axios
                .post(
                    APP_URL +
                        "personnels/" +
                        this.personnel.id +
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
                    ).then(result => {
                        if (
                            result.isConfirmed ||
                            result.dismiss === Swal.DismissReason.close
                        ) {
                            window.location.href = `${APP_URL}/personnels`;
                        }
                    });

                    this.$emit("Personnel updated", response.data);
                })
                .catch(error => {
                    this.disabled = false;

                    if (error.response.status === 422) {
                        this.message =
                            error.response.data.metadata.message.employment_agency[0];
                    } else {
                        this.message = error.response.data.metadata.message;
                    }
                    console.log(error.response);
                    Swal.fire("Invalid Input", this.message, "error");
                });
        },
        updateAndClose() {
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
            formData.append("vca_certificate", this.vca_certificate ? 1 : 0);
            formData.append(
                "vca_number",
                this.vca_certificate ? this.vca_number : ""
            );
            formData.append(
                "vca_expiry",
                this.vca_certificate ? this.vca_expiry : ""
            );
            formData.append("own_car", this.own_car ? 1 : 0);
            formData.append("active", this.active ? 1 : 0);
            formData.append("function_id", this.function_id);
            formData.append("gender", this.gender);
            formData.append("salutation", this.salutation);
            formData.append("picture", this.imageUrl);

            // multi select values
            this.selectedOptions.forEach(id => {
                formData.append("selectedOptions[]", id);
            });

            // removed docs
            this.removedDocumentIds.forEach(id => {
                formData.append("removedDocumentIds[]", id);
            });

            // new docs
            this.personnel_logs.forEach((log, index) => {
                formData.append(
                    `personnel_logs[${index}][doc_type]`,
                    log.doc_type
                );
                formData.append(`personnel_logs[${index}][expiry]`, log.expiry);
                formData.append(`personnel_logs[${index}][file]`, log.file);
            });

            // update docs
            this.personnel_update_logs.forEach((log, index) => {
                formData.append(
                    `personnel_update_logs[${index}][doc_type]`,
                    log.doc_type || ""
                );
                formData.append(
                    `personnel_update_logs[${index}][expiry]`,
                    log.expiry || ""
                );
                formData.append(`personnel_update_logs[${index}][id]`, log.id);
            });

            // ⬇️ ye sirf is button ke liye extra field hai
            formData.append("send_credentials", true);

            axios
                .post(
                    APP_URL +
                        "personnels/" +
                        this.personnel.id +
                        "?_method=PUT",
                    formData,
                    { headers: { "Content-Type": "multipart/form-data" } }
                )
                .then(response => {
                    Swal.fire(
                        "Success!",
                        response.data.message,
                        "success"
                    ).then(() => {
                        window.location.href = `${APP_URL}/personnels`;
                    });
                })
                .catch(error => {
                    Swal.fire("Invalid Input", "Something went wrong", "error");
                });
        }
    }
};
</script>
<style>
.add_more {
    font-size: 15px;
    color: #fff;
    background: #28a745;
    padding: 6px 12px;
    border: none;
    margin-top: 8px;
    cursor: pointer;
    border-radius: 5px;
}
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
</style>

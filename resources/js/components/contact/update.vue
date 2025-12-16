<template>
    <div>
        <h2>Contact</h2>

        <div style="margin: 5px;" class="row">
            <!-- Column 1 -->
            <div class="col-md-4 form-group">
                <!-- <span>Salutation:*</span><br>
                <select class="form-control" v-model="salutation">
                    <option :value="1">Mr</option>
                    <option :value="2">Ms</option>
                </select><br> -->

                <!-- <span>{{ translations.initials }}:*</span><br>
                <input class="form-control"
                       required
                       v-model="initials"
                       type="text"
                       placeholder="Enter Initials"
                /><br> -->

                <div class="mb-3">
                    <span>{{ translations.first_name }}:*</span>
                    <input
                        class="form-control"
                        required
                        v-model="first_name"
                        type="text"
                        placeholder="Enter First name"
                    />
                </div>

                <div class="mt-4">
                    <span>{{ translations.last_name }}:*</span>
                    <input
                        class="form-control"
                        required
                        v-model="last_name"
                        type="text"
                        placeholder="Enter last name"
                    />
                </div>

                <div class="mt-3">
                    <span>{{ translations.e_function }}:*</span>
                    <input
                        class="form-control"
                        required
                        v-model="function_text"
                        type="text"
                        placeholder="Enter Function"
                    />
                </div>
            </div>

            <!-- Column 2 -->
            <div class="col-md-4 mt-1">
                <div class="mb-3">
                    <label>{{ translations.department }}*</label>
                    <v-select
                        v-model="departmentsSelected"
                        :options="departments"
                        label="name"
                        :reduce="dept => dept.id"
                        multiple
                        :close-on-select="false"
                        placeholder="Select Departments"
                    ></v-select>
                </div>

                <!-- <span>{{ translations.e_function }}:*</span><br>
                <input class="form-control"
                       required
                       v-model="function_text"
                       type="text"
                       placeholder="Enter Function"
                /><br> -->

                <div class="mb-3 pt-2">
                    <span>{{ translations.date }}:</span>
                    <input
                        class="form-control"
                        required
                        v-model="dates"
                        type="date"
                        placeholder="Enter Dates"
                    />
                </div>

                <div class="mb-3">
                    <span>Notes:</span>
                    <textarea
                        style="width: 100%;"
                        class="form-control"
                        v-model="notes"
                        type="text"
                        placeholder="Enter notes"
                    ></textarea>
                </div>

                <!-- <span>{{ translations.active }}:*</span><br />
                <input
                    style="width: 35px;"
                    class="form-control"
                    required
                    v-model="active"
                    type="checkbox"
                /><br /> -->
            </div>

            <!-- Column 3 -->
            <div class="col-md-4 form-group">
                <div class="mb-3">
                    <span>{{ translations.mobile }}:*</span>
                    <div class="input-group">
                        <!-- <div class="input-group-prepend">
                            <select
                                v-model="selectedCountry"
                                class="country-select"
                                @change="updateCountryCode"
                            >
                                <option value="NL">🇳🇱 06</option>
                                <option value="PK">🇵🇰 92</option>
                            </select>
                        </div> -->
                        <input
                            class="form-control mobile-input"
                            required
                            v-model="mobile"
                            type="tel"
                            placeholder="Enter Mobile"
                            @input="validateMobile"
                        />
                    </div>
                </div>

                <div class="mb-3">
                    <span>Email:*</span>
                    <input
                        class="form-control"
                        required
                        v-model="email"
                        type="email"
                        placeholder="Enter email"
                    />
                </div>

                <div class="mb-3">
                    <span>{{ translations.password }}:*</span>
                    <input
                        class="form-control"
                        required
                        v-model="password"
                        type="password"
                    />
                </div>

                <!-- <div class="form-check">
                    <input
                        style="width: 35px;"
                        class="form-check-input"
                        required
                        v-model="active"
                        type="checkbox"
                        id="activeCheck"
                    />
                    <label class="form-check-label" for="activeCheck">
                        {{ translations.active }}
                    </label>
                </div> -->
                <div class="form-check mt-3">
                    <span>{{ translations.active }}:</span><br />
                    <input
                        style="width: 35px;"
                        class="form-control"
                        v-model="active"
                        type="checkbox"
                    />
                </div>

                <!-- <span>{{ translations.department }}*</span><br>
                <select  class="form-control" ref="selectElement" id="selectElement">
                    <option v-for="department in departments" :value="department.id">{{ department.name }}</option>
                </select> -->
            </div>
        </div>

        <div class="row">
            <div style="margin: 10px; text-align: end" class="col-lg-12">
                <a
                    :href="base_url + 'contacts'"
                    class="submit btn btn-danger"
                    value="Cancel"
                    style="width: 100px;"
                >
                    {{ translations.cancel }}
                </a>

                <input
                    style="margin-right: 50px; width: 80px; margin-left: 10px;"
                    type="submit"
                    class="submit btn btn-primary"
                    value="Update"
                    @click.prevent="updateContact"
                />
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import Swal from "sweetalert2";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import $ from "jquery";
export default {
    components: {
        vSelect
    },
    props: ["contact", "translations", "departments"],
    data() {
        return {
            departmentsSelected: [],
            salutation: "",
            initials: "",
            first_name: "",
            last_name: "",
            function_text: "",
            dates: "",
            notes: "",
            telephone: "",
            private_phone: "",
            mobile: "",
            selectedCountry: "NL",
            countryCode: "06",
            mobile1: "",
            fax: "",
            email: "",
            password: "",
            active: 0,
            base_url: APP_URL
        };
    },
    computed: {
        // formattedMobile() {
        //     if (this.mobile) {
        //         return `${this.countryCode}-${this.mobile.replace(/\D/g, "")}`;
        //     }
        //     return "";
        // }
    },
    watch: {
        department(newVal) {
            // Update the Select2 component when department value changes
            $(this.$refs.selectElement)
                .val(newVal)
                .trigger("change");
        },
        selectedCountry(newVal) {
            this.updateCountryCode();
        }
    },
    mounted() {
        console.log("Update component mounted");
        const contact = this.contact;
        this.salutation = contact.salutation;
        this.initials = contact.initials;
        this.first_name = contact.first_name;
        this.last_name = contact.last_name;
        this.function_text = contact.function_text;
        this.dates = contact.dates;
        this.telephone = contact.telephone;
        this.private_phone = contact.private_phone;
        this.mobile = contact.mobile;
        this.mobile1 = contact.mobile1;
        this.fax = contact.fax;
        this.email = contact.email;
        this.notes = contact.notes;
        this.active = contact.active;
        this.departmentsSelected = contact.departments.map(d => d.id);
    },

    methods: {
        // updateCountryCode() {
        //     this.countryCode = this.selectedCountry === "PK" ? "92" : "06";
        //     //
        //     // // Remove existing country code before updating
        //     this.mobile = this.mobile.replace(/^(\+92|06)/, "").replace(/\D/g, "");
        //     //
        //     // // Ensure mobile number length matches country format
        //     this.validateMobile();

        // },
        handleSelectChange(event) {
            this.selectedDepartmentId = event.target.value;
            console.log("Selected Department ID:", this.selectedDepartmentId);
        },
        updateContact() {
            axios
                .put(APP_URL + "contacts/" + this.contact.id, {
                    initials: this.initials || "",
                    first_name: this.first_name || "",
                    last_name: this.last_name || "",
                    function_text: this.function_text || "",
                    dates: this.dates || "",
                    telephone: this.telephone || "",
                    private_phone: this.private_phone || "",
                    mobile: this.mobile || "",
                    mobile1: this.mobile1 || "",
                    fax: this.fax || "",
                    email: this.email || "",
                    notes: this.notes || "",
                    password: this.password || "",
                    active: this.active || "",
                    departments: this.departmentsSelected
                })
                .then(response => {
                    Swal.fire(
                        "Good job!",
                        (this.message = response.data.message),
                        "success"
                    );
                    let url = this.base_url + "contacts";
                    setTimeout(function() {
                        window.location.href = url;
                    }, 1500);

                    this.$emit("Contact updated", response.data);
                    this.salutation = "";
                    this.initials = "";
                    this.first_name = "";
                    this.last_name = "";
                    this.function_text = "";
                    this.dates = "";
                    this.telephone = "";
                    this.private_phone = "";
                    this.mobile = "";
                    this.notes = "";
                    this.mobile1 = "";
                    this.fax = "";
                    this.email = "";
                    this.password = "";
                    this.active = "";
                })
                .catch(error => {
                    console.error("Error updating contact:", error);
                    let errorMessage =
                        "An error occurred while updating the contact. Please try again.";

                    if (
                        error.response &&
                        error.response.data &&
                        error.response.data.error
                    ) {
                        errorMessage = error.response.data.error;
                    }

                    Swal.fire("Error", errorMessage, "error");
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
.select2-selection {
    height: 40px !important;
    position: relative !important;
    bottom: 30px !important;
}
.select2-dropdown {
    position: relative !important;
    bottom: 60px !important;
}

.mobile-input {
    flex: 1;
    border: none;
    padding: 7px;
    font-size: 16px;
}
.input-group {
    display: flex;
    align-items: center;
    border: 1px solid #ccc;
    border-radius: 5px;
    overflow: hidden;
}
/* .country-select {
    border: none;
    padding: 8px;
    font-size: 16px;
    background: #f8f9fa;
} */
</style>

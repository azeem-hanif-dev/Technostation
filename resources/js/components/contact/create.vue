<template>
    <div>
        <h2>{{ translations.add_new }} Contact</h2>

        <form @submit.prevent="createContact">
            <div class="row">
                <!-- ───── Column 1 ───── -->
                <div class="col-md-4">
                    <!-- Salutation (Commented) -->
                    <!--
                    <div class="mb-3">
                        <label>Salutation:*</label>
                        <select class="form-control" v-model="salutation">
                            <option :value="1">Mr</option>
                            <option :value="2">Ms</option>
                        </select>
                    </div>
                    -->

                    <!-- Initials (Commented) -->
                    <!--
                    <div class="mb-3">
                        <label>{{ translations.initials }}:*</label>
                        <input class="form-control" v-model="initials" type="text" placeholder="Enter Initials" />
                    </div>
                    -->

                    <div class="mb-3">
                        <label>{{ translations.first_name }}*</label>
                        <input
                            class="form-control"
                            v-model="first_name"
                            type="text"
                            placeholder="Enter First name"
                        />
                    </div>

                    <div class="mb-3">
                        <label>{{ translations.last_name }}*</label>
                        <input
                            class="form-control"
                            v-model="last_name"
                            type="text"
                            placeholder="Enter Last name"
                        />
                    </div>

                    <div class="mb-3">
                        <label>{{ translations.e_function }}</label>
                        <input
                            class="form-control"
                            v-model="function_text"
                            type="text"
                            placeholder="Enter Function"
                        />
                    </div>
                </div>

                <!-- ───── Column 2 ───── -->
                <div class="col-md-4 mt-1">
                    <div class="mb-3">
                        <label>{{ translations.department }}*</label>
                        <v-select
                            v-model="departmentsSelected"
                            :options="departments"
                            label="name"
                            :reduce="department => department.id"
                            multiple
                            :close-on-select="false"
                            placeholder="Select Departments"
                        >
                        </v-select>
                    </div>

                    <div class="mb-3 mt-1">
                        <label>{{ translations.date }}</label>
                        <input
                            class="form-control"
                            v-model="dates"
                            type="date"
                        />
                    </div>
                    <div class="">
                        <label>Notes</label>
                        <textarea
                            class="form-control"
                            v-model="notes"
                            rows="3"
                            placeholder="Enter notes"
                        ></textarea>
                    </div>
                </div>

                <!-- ───── Column 3 ───── -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label>{{ translations.mobile }}*</label>
                        <input
                            class="form-control"
                            v-model="mobile"
                            type="tel"
                            placeholder="Enter Mobile"
                        />
                    </div>

                    <div class="mb-3">
                        <label>Email*</label>
                        <input
                            class="form-control"
                            v-model="email"
                            type="email"
                            placeholder="Enter Email"
                        />
                    </div>

                    <div class="mb-3">
                        <label>{{ translations.password }}*</label>
                        <input
                            class="form-control"
                            v-model="password"
                            type="password"
                        />
                    </div>

                    <div class="form-check mt-3">
                        <!-- <input class="form-check-input" v-model="active" type="checkbox" id="activeCheck" />
                        <label class="form-check-label" for="activeCheck">
                            {{ translations.active }}
                        </label> -->
                        <span>{{ translations.active }}:</span><br />
                        <input
                            style="width: 35px;"
                            class="form-control"
                            v-model="active"
                            type="checkbox"
                        />
                    </div>
                </div>
            </div>

            <!-- ───── Buttons Row ───── -->
            <div class="row mt-4">
                <div class="col-12 d-flex justify-content-end">
                    <a
                        :href="base_url + 'contacts'"
                        class="mr-3 btn btn-danger me-2"
                        style="width: 100px;"
                    >
                        {{ translations.cancel }}
                    </a>

                    <button
                        type="submit"
                        class=" mr-2 btn btn-primary"
                        style="width: 100px;"
                    >
                        {{ translations.submit }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
// import Toastify from "toastify-js";
import Swal from "sweetalert2";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
export default {
    components: {
        vSelect
    },
    props: ["departments", "translations"],
    data() {
        return {
            message: "",
            // salutation: "",
            // initials: "",
            first_name: "",
            last_name: "",
            //department: null,
            //departments: [], // all departments from API
            departmentsSelected: [],
            function_text: "",
            dates: "",
            telephone: "",
            // private_phone: "",
            mobile: "",
            // mobile1: "",
            // fax: "",
            email: "",
            password: "",
            notes: "",
            active: 0,
            base_url: APP_URL,
            errors: []
        };
    },
    mounted() {
        console.log("Mounted");
        console.log(this.departments);
    },
    methods: {
        createContact() {
            this.errors = [];
            axios
                .post(APP_URL + "contacts", {
                    // salutation: this.salutation,
                    // initials: this.initials,
                    first_name: this.first_name,
                    last_name: this.last_name,
                    function_text: this.function_text,
                    dates: this.dates,
                    telephone: this.telephone,
                    private_phone: this.private_phone,
                    mobile: this.mobile,

                    // fax: this.fax,
                    email: this.email,
                    password: this.password,
                    active: this.active,
                    notes: this.notes,
                    departments: this.departmentsSelected || []
                })
                .then(response => {
                    this.message = response.data.message;

                    this.$emit("Contact created", response.data);
                    // this.salutation = "";
                    // this.initials = "";
                    this.first_name = "";
                    this.last_name = "";
                    this.function_text = "";
                    this.dates = "";
                    this.telephone = "";
                    this.private_phone = "";
                    this.mobile = "";
                    this.fax = "";
                    this.email = "";
                    this.password = "";
                    this.active = "";
                    this.notes = "";
                    this.department = null;
                    Swal.fire(
                        "Good job!",
                        (this.message = response.data.message),
                        "success"
                    );
                    let url = this.base_url + "contacts";
                    setTimeout(function() {
                        window.location.href = url;
                    }, 1500);
                })
                .catch(error => {
                    if (error.response && error.response.data.errors) {
                        const allErrors = error.response.data.errors;
                        const messages = Object.values(allErrors)
                            .flat()
                            .join("\n");
                        Swal.fire("Validation Error", messages, "error");
                    } else if (error.response && error.response.data.error) {
                        Swal.fire("Error", error.response.data.error, "error");
                    } else {
                        Swal.fire("Bad job!", this.errors, "error");
                    }
                });
            // .catch(error => {
            //     if (
            //         error.response &&
            //         error.response.data.metadata.message
            //     ) {
            //         this.errors = error.response.data.metadata.message;
            //     }

            //     console.log("THIS", this.errors);
            //     Swal.fire("Bad job!", this.errors, "error");
            // });
        }

        // showToast(message) {
        //     Toastify({
        //         text: message,
        //         duration: 3000,
        //         newWindow: true,
        //         close: true,
        //         gravity: "top",
        //         position: "right",
        //         backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
        //         stopOnFocus: true
        //     }).showToast();
        // }
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
.input-group {
    display: flex;
    align-items: center;
    border: 1px solid #ccc;
    border-radius: 5px;
    overflow: hidden;
}

.country-select {
    border: none;
    padding: 8px;
    font-size: 16px;
    background: #f8f9fa;
}

.mobile-input {
    flex: 1;
    border: none;
    padding: 7px;
    font-size: 16px;
}

.country-select:focus,
.mobile-input:focus {
    outline: none;
}
</style>

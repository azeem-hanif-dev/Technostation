<template>
    <div>
        <h4>{{ translations.create_supplier }}</h4>
        <form @submit.prevent="createSupplier">
            <div style="margin: 10px;" v-if="currentPage === 1">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <span>{{ translations.company_name }}:*</span><br />
                        <input
                            class="form-control"
                            v-model="company_name"
                            type="text"
                            placeholder="Company Name"
                            required
                        /><br />
                        <span>Code:*</span><br />
                        <input
                            class="form-control"
                            required
                            v-model="code"
                            type="text"
                            placeholder="Enter Code"
                        /><br />
                        <span>{{ common.telephone }}:</span><br />
                        <input
                            class="form-control"
                            v-model="telephone"
                            type="text"
                            placeholder="Enter Telephone"
                        /><br />
                        <span>{{ common.mobile }}:*</span><br />
                        <input
                            class="form-control"
                            required
                            v-model="mobile"
                            type="text"
                            placeholder="Enter Mobile"
                        /><br />
                        <span>{{ common.city }}:</span><br />
                        <input
                            class="form-control"
                            required
                            v-model="city"
                            type="text"
                            placeholder="Enter City"
                        /><br />
                    </div>
                    <div class="col-md-6 form-group">
                        <span>{{ common.email }}:*</span><br />
                        <input
                            class="form-control"
                            required
                            v-model="email"
                            type="email"
                            placeholder="abc@gmail.com"
                        /><br />
                        <span>{{ common.password }}:*</span><br />
                        <input
                            class="form-control"
                            required
                            v-model="password"
                            type="text"
                            placeholder="Enter Password"
                        /><br />
                        <span>{{ common.address }}:*</span><br />
                        <input
                            class="form-control"
                            v-model="address"
                            type="text"
                            placeholder="Enter Address"
                        /><br />
                        <span>{{ common.post_code }}:*</span><br />
                        <input
                            class="form-control"
                            required
                            v-model="post_code"
                            type="text"
                            placeholder="Enter Post Code"
                        /><br />
                        <span>Notes:</span><br />
                        <textarea
                            class="form-control"
                            v-model="notes"
                            type="text"
                        />
                    </div>
                </div>
                <div class="row">
                    <div
                        style="margin: 10px; text-align: end"
                        class="col-lg-12"
                    >
                        <a
                            :href="base_url + 'container-supplier'"
                            class="btn btn-danger mt-0"
                            style="width: 100px;"
                        >
                            {{ common.cancel }}
                        </a>
                        <button
                            style="margin-right: 50px;
               width: 80px;
               margin-left: 10px;"
                            class="btn btn-primary mt-0"
                            type="submit"
                        >
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
import Toastify from "toastify-js";
import Swal from "sweetalert2";
import axios from "axios";

export default {
    props: ["translations", "common"],
    data() {
        return {
            next: this.common.next,
            currentPage: 1,
            message: "",
            company_name: "",
            code: "",
            email: "",
            password: "",
            address: "",
            post_code: "",
            notes: "",
            city: "",
            telephone: "",
            mobile: "",
            base_url: APP_URL
        };
    },
    mounted() {
        console.log("Mounted");
    },
    methods: {
        createSupplier() {
            console.log(this.company_name);
            axios
                .post(APP_URL + "container-supplier", {
                    company_name: this.company_name,
                    code: this.code,
                    email: this.email,
                    password: this.password,
                    address: this.address,
                    post_code: this.post_code,
                    notes: this.notes,
                    city: this.city,
                    telephone: this.telephone,
                    mobile: this.mobile
                })
                .then(response => {
                    this.message = response.data.message;
                    console.log(this.message);
                    this.$emit("Container Supplier created", response.data);

                    Swal.fire({
                        title: "Success",
                        text: response.data.message,
                        icon: "success"
                    });
                    let url = this.base_url + "container-supplier";
                    setTimeout(function() {
                        window.location.href = url;
                    }, 1000);
                })
                .catch(error => {
                    if (
                        error.response &&
                        error.response.data &&
                        error.response.data.message
                    ) {
                        this.errors = error.response.data.message;
                    } else {
                        this.errors = "Unexpected error occurred.";
                    }

                    Swal.fire("Error!", this.errors, "error");
                });
        },
        submitForm: function() {
            this.formSubmitted = true;
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

span {
    font-size: 18px;
    margin: 4px;
    font-weight: 500;
}

h4 {
    margin: 15px;
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

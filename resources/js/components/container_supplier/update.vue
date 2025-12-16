<template>
    <div>
        <h4>
            {{
                translations.update_supplier
                    ? translations.update_supplier
                    : "Update Supplier"
            }}
        </h4>
        <form @submit.prevent="updateContainerSupplier">
            <div style="margin: 10px;">
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
                            v-model="code"
                            type="text"
                            placeholder="Enter Code"
                            required
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
                            v-model="mobile"
                            type="text"
                            placeholder="Enter Mobile"
                            required
                        /><br />

                        <span>{{ common.city }}:</span><br />
                        <input
                            class="form-control"
                            v-model="city"
                            type="text"
                            placeholder="Enter City"
                            required
                        /><br />
                    </div>

                    <div class="col-md-6 form-group">
                        <span>{{ common.email }}:*</span><br />
                        <input
                            class="form-control"
                            v-model="email"
                            type="email"
                            placeholder="abc@gmail.com"
                            required
                        /><br />

                        <span>Password:*</span><br />
                        <input
                            class="form-control"
                            v-model="password"
                            type="password"
                            placeholder="Enter password"
                            required
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
                            v-model="post_code"
                            type="text"
                            placeholder="Enter Post Code"
                            required
                        /><br />
                          <span>Notes:</span>
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
                            style="margin-right: 50px; width: 80px; margin-left: 10px;"
                            class="btn btn-primary mt-0"
                            type="submit"
                        >
                            {{ common.update }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import axios from "axios";
import Swal from "sweetalert2";

export default {
    props: ["container_supplier", "translations", "common"],
    data() {
        return {
            company_name: "",
            code: "",
            email: "",
            address: "",
            post_code: "",
               notes: "",
            city: "",
            telephone: "",
            password: "",
            mobile: "",
            base_url: APP_URL
        };
    },
    mounted() {
        const data = this.container_supplier;
        this.company_name = data.company_name || "";
        this.code = data.code || "";
        this.email = data.email || "";
        this.address = data.address || "";
        this.post_code = data.post_code || "";
            this.notes = data.notes || "";
        this.city = data.city || "";
        this.telephone = data.telephone || "";
        this.mobile = data.mobile || "";
    },
    methods: {
        updateContainerSupplier() {
            axios
                .put(
                    `${APP_URL}container-supplier/${this.container_supplier.id}`,
                    {
                        company_name: this.company_name,
                        code: this.code,
                        email: this.email,
                        address: this.address,
                        post_code: this.post_code,
                        notes: this.notes,
                        city: this.city,
                        telephone: this.telephone,
                        password: this.password,
                        mobile: this.mobile
                    }
                )
                .then(response => {
                    Swal.fire({
                        title: "Success",
                        text: response.data.message,
                        icon: "success"
                    });

                    setTimeout(() => {
                        window.location.href =
                            this.base_url + "container-supplier";
                    }, 1500);
                });
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
</style>

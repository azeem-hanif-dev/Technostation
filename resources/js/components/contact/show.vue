<template>
    <div class="row">
        <div class="card col-md-11">
            <div class="card-header row">
                <div class="col-md-6">
                    <h4>Contact</h4>
                </div>
                <div class="col-md-6">
                    <a
                        :href="base_url + 'contacts'"
                        class="btn btn-success btn-xs float-right"
                    >
                        {{ translations.back }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <!-- <p v-if="salutation == 1"><strong>Salutation:</strong> Mr</p>
                        <p v-if="salutation == 2"><strong>Salutation:</strong> Ms</p>
                        <p><strong>{{ translations.initials }}:</strong> {{initials}}</p> -->
                        <p>
                            <strong>{{ translations.first_name }}:</strong>
                            {{ first_name }}
                        </p>
                        <p>
                            <strong>{{ translations.last_name }}:</strong>
                            {{ last_name }}
                        </p>
                        <p><strong>Email:</strong> {{ email }}</p>
                        <p v-if="active == 1">
                            <strong>{{ translations.active }}:</strong> true
                        </p>
                        <p v-if="active == 0">
                            <strong>{{ translations.active }}:</strong> false
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p>
                            <strong>{{ translations.department }}:</strong>
                            {{ department }}
                        </p>
                        <p>
                            <strong>{{ translations.e_function }}:</strong>
                            {{ function_text }}
                        </p>
                        <p>
                            <strong>{{ translations.date }}:</strong>
                            {{ dates }}
                        </p>
                        <!-- <p><strong>{{ translations.phone }}:</strong> {{telephone}}</p> -->
                    </div>
                    <div class="col-md-4">
                        <!-- <p><strong>{{ translations.private_phone }}:</strong> {{private_phone}}</p> -->
                        <p>
                            <strong>{{ translations.mobile }}:</strong>
                            {{ mobile }}
                        </p>
                        <!-- <p><strong>{{ translations.mobile }}1:</strong> {{mobile1}}</p> -->
                        <!-- <p><strong>Fax:</strong> {{fax}}</p> -->
                        <p><strong>Notes:</strong> {{ notes }}</p>
                    </div>
                    <!-- <div class="col-md-4">
                        <p><strong>Email:</strong> {{email}}</p>
                        <p v-if="active==1"><strong>{{ translations.active }}:</strong> true</p>
                        <p v-if="active==0"><strong>{{ translations.active }}:</strong> false</p>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";
export default {
    props: ["contact", "translations"],
    data() {
        return {
            // salutation: "",
            // initials: "",
            first_name: "",
            last_name: "",
            department: null,
            function_text: "",
            dates: "",
            notes: "",
            // telephone: "",
            // private_phone: "",
            mobile: "",
            // mobile1: "",
            // fax: "",
            email: "",
            password: "",
            active: 0,
            base_url: APP_URL
        };
    },
    mounted() {
        const contact = this.contact;
        this.salutation = contact.salutation;
        this.initials = contact.initials;
        this.first_name = contact.first_name;
        this.last_name = contact.last_name;
        this.function_text = contact.function_text;
        this.dates = contact.dates;
        this.telephone = contact.telephone;
        this.private_phone = contact.private_phone;
        this.notes = contact.notes;
        this.mobile = contact.mobile;
        this.mobile1 = contact.mobile1;
        this.fax = contact.fax;
        this.email = contact.email;
        this.password = contact.password;
        this.active = contact.active;
        //this.department = contact.department.name;
        if (contact.departments && contact.departments.length) {
            this.department = contact.departments.map(d => d.name).join(", ");
        } else {
            this.department = "";
        }
    }
};
</script>
<style>
span {
    font-size: 18px;
    margin: 4px;
    font-weight: 500;
}

.card {
    padding-left: 15px;
    padding-right: 15px;
    padding-bottom: 15px;
    margin-top: 15px;
    margin-left: 30px;
    border-radius: 5px;
}
</style>

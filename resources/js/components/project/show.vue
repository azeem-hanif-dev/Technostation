<template>
    <div class="row">
        <div class="card col-md-11">
            <div class="card-header row">
                <div class="col-md-6">
                    <h4>Project</h4>
                </div>
                <div class="col-md-6">
                    <a
                        :href="base_url + 'staffing_projects'"
                        class="btn btn-success btn-xs float-right"
                    >
                        {{ translations.back }}
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>{{ translations.name }}:</strong> {{ name }}</p>
                        <p><strong>{{ translations.customer }}:</strong> {{ customer }}</p>
                        <p><strong>Performer:</strong> {{ performer }}</p>
                        <p><strong>{{ translations.start_date }}:</strong> {{ start_date || "Not Provided" }}</p>
                        <p><strong>{{ translations.end_date }}:</strong> {{ end_date || "Not Provided" }}</p>
                        <p><strong>{{ translations.description }}:</strong> {{ description || "Not Provided" }}</p>
                        <p><strong>{{ translations.fixed_price }}:</strong> {{ fixed_price || "Not Provided" }}</p>
                        <p><strong>{{ translations.ecu_project }}:</strong> {{ edu_project_no || "Not Provided" }}</p>
                    </div>

                    <div class="col-md-4">
                        <p v-if="project_manager == 1"><strong>{{ translations.project_manager }}:</strong> Jacqueline</p>
                        <p v-if="project_manager == 2"><strong>{{ translations.project_manager }}:</strong> Shakeel</p>

                        <p><strong>{{ translations.client_project_no }}:</strong> {{ client_project_no || "Not Provided" }}</p>
                        <p><strong>{{ translations.address }}:</strong> {{ address || "Not Provided" }}</p>
                        <p><strong>Postcode:</strong> {{ post_code || "Not Provided" }}</p>

                        <p v-if="weekly_statement == 1"><strong>{{ translations.weekly }}:</strong> 3 months</p>
                        <p v-if="weekly_statement == 2"><strong>{{ translations.weekly }}:</strong> 6 months</p>
                        <p v-if="weekly_statement == 3"><strong>{{ translations.weekly }}:</strong> 9 months</p>

                        <p><strong>{{ translations.price_agreement }}:</strong> {{ price_agreement || "Not Provided" }}</p>
                    </div>

                    <div class="col-md-4">
                        <p><strong>{{ translations.number_of_times }}:</strong> {{ no_of_times_per_week || "Not Provided" }}</p>

                        <p v-if="unit == 1"><strong>{{ translations.unit }}:</strong> Price per shack</p>
                        <p v-if="unit == 2"><strong>{{ translations.unit }}:</strong> Price per project</p>

                        <p><strong>{{ translations.number_of_chain }}:</strong> {{ no_of_chain || "Not Provided" }}</p>
                        <p><strong>{{ translations.price }}:</strong> {{ price || "Not Provided" }}</p>
                        <p><strong>{{ translations.purchase_price }}:</strong> {{ purchase_price || "Not Provided" }}</p>
                        <p><strong>{{ translations.approval }}:</strong> {{ approval || "Not Provided" }}</p>
                        <p><strong>{{ translations.notes }}:</strong> {{ notes || "Not Provided" }}</p>
                        <p><strong>More notes:</strong> {{ more_notes || "Not Provided" }}</p>
                    </div>

                    <div class="col-md-4" id="map"></div>
                </div>
            </div>

            <!-- Documents Section -->
            <div class="card-body mt-4" v-if="documents.length">
                <h5>{{ translations.documents || "Documents" }}</h5>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ translations.file_name || "File Name" }}</th>
                            <th>{{ translations.expiry || "Expiry Date" }}</th>
                            <th>{{ translations.download || "Download" }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="doc in documents" :key="doc.id">
                            <td>{{ doc.type || "Null" }}</td>
                            <td>{{ doc.expiry_date || "Null" }}</td>
                            <td>
                                <a
                                    :href="base_url + '/storage/document_docs/' + doc.file"
                                    class="btn btn-primary btn-sm"
                                    download
                                >
                                    <i class="fas fa-download"></i>
                                    {{ translations.download || "Download" }}
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-body mt-4" v-else>
                <p>{{ translations.no_documents || "No documents available." }}</p>
            </div>

            <!-- Communication Table (Slider) -->
            <div class="card-body">
                <div class="row">
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                </tr>
                            </thead>

                            <tbody>
                                <!-- Show only 1 row or all rows -->
                                <tr
                                    v-for="(contact, i) in visibleContacts"
                                    :key="i"
                                >
                                    <td>{{ contact.first_name }} {{ contact.last_name }}</td>
                                    <td>{{ contact.email }}</td>
                                    <td>{{ contact.mobile }}</td>
                                </tr>

                                <!-- Slider Arrow -->
                                <tr v-if="contacts.length > 1">
                                    <td colspan="3" class="arrow-row"
                                        @click="showAllContacts = !showAllContacts">
                                        <span class="arrow-icon">
                                            <i :class="showAllContacts ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
                                            {{ showAllContacts ? 'Show Less' : 'Show More' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import axios from "axios";
import { Loader } from "@googlemaps/js-api-loader";

export default {
    props: ["project", "translations"],

    data() {
        return {
            lat: "",
            long: "",
            name: "",
            customer: "",
            department: "",
            departments: [],
            contacts: [],
            performer: "",
            active: 0,
            start_date: "",
            end_date: "",
            project_manager: "",
            description: "",
            fixed_price: "",
            edu_project_no: "",
            client_project_no: "",
            address: "",
            post_code: "",
            city: "",
            weekly_statement: "",
            price_agreement: "",
            no_of_times_per_week: "",
            unit: "",
            no_of_chain: "",
            price: "",
            purchase_price: "",
            approval: "",
            notes: "",
            more_notes: "",
            base_url: APP_URL,
            link: "",
            documents: [],

            showAllContacts: false
        };
    },

    computed: {
        visibleContacts() {
            return this.showAllContacts
                ? this.contacts
                : this.contacts.slice(0, 1);
        }
    },

    mounted() {
        axios.get(`${APP_URL}/staffing_projects/${this.project}`)
            .then(response => {
                const project = response.data;

                this.name = project.name;
                this.customer = project.customer.name;
                this.department = project.department.name;
                this.departmentId = project.department.id;
                this.performer = project.project_performer.first_name;
                this.active = project.active;
                this.start_date = project.start_date;
                this.end_date = project.end_date;
                this.project_manager = project.project_manager;
                this.description = project.description;
                this.fixed_price = project.fixed_price;
                this.edu_project_no = project.edu_project_no;
                this.client_project_no = project.client_project_no;
                this.address = project.address;
                this.post_code = project.post_code;
                this.city = project.city;
                this.weekly_statement = project.weekly_statement;
                this.price_agreement = project.price_agreement;
                this.no_of_times_per_week = project.no_of_times_per_week;
                this.unit = project.unit;
                this.no_of_chain = project.no_of_chain;
                this.price = project.price;
                this.purchase_price = project.purchase_price;
                this.approval = project.approval;
                this.notes = project.notes;
                this.more_notes = project.more_notes;
                this.lat = project.lat;
                this.long = project.long;

                this.getContacts();
                this.documents = project.documents || [];
            });

        const loader = new Loader({
            apiKey: "AIzaSyC35SHRVQ0JebXbbRKgx85RTjZXDsDQH70",
            version: "weekly"
        });

        loader.load().then(() => {
            const map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: parseFloat(this.lat), lng: parseFloat(this.long) },
                zoom: 8,
                draggable: false
            });
        });
    },

    methods: {
        getContacts() {
            axios.get(APP_URL + `departments/${this.departmentId}/contacts`)
                .then(response => {
                    this.contacts = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
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

/* Arrow slider styles */
.arrow-row {
    text-align: center;
    background: #f7f7f7;
    cursor: pointer;
}

.arrow-row:hover {
    background: #e9ecef;
}

.arrow-icon {
    font-size: 16px;
    font-weight: bold;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
</style>

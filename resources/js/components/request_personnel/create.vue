<template xmlns="http://www.w3.org/1999/html">
    <div>
        <h2>{{ translations.request_staff }}</h2>
        <form @submit.prevent="createRequestPersonnel">
            <div style="margin: 10px;" v-if="currentPage === 1">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <span>{{ translations.project_name }}:*</span><br />
                        <!--                    <searchable-dropdown :staff-list="projects" @item-selected="onItemSelected"></searchable-dropdown>-->
                        <!--select class="form-control" v-model="project" @change="getProjectData">
                        <option v-for="project in projects" :value="project.id">{{ project.name }}</option>
                    </select-->

                        <v-select
                            v-model="project"
                            :options="projects"
                            label="name"
                            :reduce="project => project.id"
                            @input="handleProjectChange"
                            placeholder="Select a project"
                        ></v-select>

                        <br />

                        <span>{{ common.department }}:</span><br />
                        <input
                            class="form-control"
                            v-model="department"
                            type="text"
                            disabled
                        /><br />

                        <span>{{ translations.project_address }}:</span><br />
                        <input
                            class="form-control"
                            required
                            v-model="project_address"
                            type="text"
                            disabled
                        /><br />

                        <span>{{ translations.zipcode }}:</span><br />
                        <input
                            class="form-control"
                            required
                            v-model="zipcode"
                            type="text"
                            disabled
                        /><br />

                        <span>Performer:*</span><br />
                        <input
                            class="form-control"
                            required
                            v-model="performer"
                            type="text"
                            disabled
                        /><br />

                        <span>Reports To:*</span><br />
                        <v-select
                            v-model="temp_performer"
                            :options="formattedContacts"
                            label="fullName"
                            :reduce="contact => contact.id"
                            placeholder="Select a performer"
                        ></v-select>
                    </div>

                    <div class="col-md-6 form-group">
                        <span>{{ common.mobile }}:*</span><br />
                        <input
                            class="form-control"
                            required
                            v-model="mobile"
                            type="text"
                            disabled
                        /><br />
                        <span>{{ translations.adopted_by }}:*</span><br />
                        <input
                            class="form-control"
                            required
                            v-model="adopted_by"
                            type="text"
                        /><br />

                        <span>{{ translations.application_date_time }}:*</span
                        ><br />
                        <input
                            class="form-control"
                            required
                            v-model="application_date_time"
                            type="datetime-local"
                        /><br />

                        <span>{{ common.action }}:*</span>
                        <input
                            type="checkbox"
                            id="complete"
                            name="complete"
                            value="1"
                            v-model="complete"
                        /><br />
                    </div>
                </div>
                <div class="row">
                    <div
                        style="margin: 10px; text-align: end"
                        class="col-lg-12"
                    >
                        <button
                            type="button"
                            class="mt-0 btn btn-danger"
                            style="width: 100px;"
                            @click="goBackToIndexPage"
                        >
                            {{ common.cancel }}
                        </button>
                        <button
                            style="margin-right: 50px;
                           width: 80px;
                           margin-left: 10px;"
                            type="button"
                            class="btn mt-0 btn-primary"
                            @click="pageIncrement"
                        >
                            {{ common.next }}
                        </button>
                    </div>
                </div>
            </div>

            <div style="margin: 10px;" v-if="currentPage === 2">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <span>{{ translations.starting_date_time }}:*</span
                        ><br />
                        <input
                            class="form-control"
                            required
                            v-model="starting_date_time"
                            type="datetime-local"
                        /><br />

                        <span>{{ translations.no_of_person }}:</span><br />
                        <input
                            class="form-control"
                            v-model="no_of_people"
                            type="text"
                        /><br />

                        <span>{{ translations.how_many_days }}:</span><br />
                        <input
                            class="form-control"
                            v-model="how_many_days"
                            type="text"
                        /><br />
                    </div>

                    <div class="col-md-6 form-group">
                        <!--span>{{ translations.report_to }}:*</span><br>
                    <input class="form-control"
                           required
                           v-model="report_to"
                           type="text"
                           disabled
                    /><br-->

                        <!--span>{{ common.mobile }}:</span><br>
                    <input class="form-control"
                           v-model="r_mobile"
                           type="text"
                    /><br-->

                        <span>{{ translations.activities }}:*</span><br />
                        <v-select
                            v-model="activities"
                            :options="e_functions"
                            label="name"
                            :reduce="activity => activity.id"
                            multiple
                            :close-on-select="false"
                        />

                        <!-- <span>{{ translations.activities }}:*</span><br>
                    <v-select class="form-control" v-model="activity" multiple>
                        <option v-for="activity in e_functions" :value="activity.id">{{ activity.name }}</option>
                    </v-select> -->

                        <br />
                    </div>
                    <div class="col-md-12">
                        <span>{{ translations.requirements }}:</span><br />
                        <textarea
                            class="form-control"
                            v-model="requirements"
                            type="text"
                        /><br />

                        <span>{{ translations.project_notes }}:</span><br />
                        <textarea
                            class="form-control"
                            v-model="notes"
                            type="text"
                        /><br />

                        <span>{{ common.comments }}:</span><br />
                        <textarea
                            class="form-control"
                            v-model="comments"
                            type="text"
                        /><br />
                    </div>
                </div>

                <div class="row">
                    <div
                        style="margin: 10px; text-align: end"
                        class="col-lg-12"
                    >
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
                            class="submit btn btn-primary"
                            @click="submittingValueTrue"
                        >
                            {{ common.submit }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
import SearchableDropdown from "./SearchableDropdown.vue";
import Swal from "sweetalert2";
import axios from "axios";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
    components: {
        SearchableDropdown,
        vSelect
    },
    props: [
        "projects",
        "e_functions",
        "common",
        "translations",
        "current_date_time"
    ],
    data() {
        return {
            contacts: [],
            currentPage: 1,
            message: "",
            submit: false,
            is_cancel: false,
            project: null,
            department: "",
            department_id: 1,
            project_address: "",
            zipcode: "",
            performer: "",
            temp_performer: "",
            order_by: "",
            mobile: "",
            //r_mobile: "",
            adopted_by: "",
            application_date_time: "",
            complete: "",
            starting_date_time: this.current_date_time,
            no_of_people: "",
            how_many_days: "",
            report_to: "",
            // activity: null,
            activities: [],
            // activity: "",
            requirements: "",
            notes: "",
            comments: "",
            base_url: APP_URL
        };
    },
    mounted() {
        console.log("Mounted");

        // Get the current date
        const today = new Date();

        // Set the time to 07:00 AM
        today.setHours(7, 0, 0, 0);

        const localDateTime =
            today.toLocaleDateString("en-CA") +
            "T" +
            today.toTimeString().slice(0, 5);

        // Format the date to match the datetime-local input format
        this.application_date_time = localDateTime;

        // if (this.activity) {
        //     this.activities = this.activity.split(",").map(Number);
        // }
    },
    computed: {
        formattedContacts() {
            return this.contacts.map(contact => ({
                ...contact,
                fullName: `${contact.first_name} ${contact.last_name}`,
                mobile: contact.mobile
            }));
        }
    },
    watch: {
        temp_performer(newVal) {
            this.fetchMobileNumber(newVal);
        }
    },
    methods: {
        onItemSelected(item) {
            this.project = item;
        },
        pageIncrement() {
            this.currentPage++;
        },
        pageDecrement() {
            this.currentPage--;
            if (this.currentPage === 1) {
                this.is_cancel = true;
            }
        },
        goBackToIndexPage() {
            if (this.is_cancel) {
                let url = this.base_url + "request_personnels";
                setTimeout(function() {
                    window.location.href = url;
                }, 0);
            }
        },
        submittingValueTrue() {
            this.submit = true;
        },
        fetchMobileNumber(performerId) {
            const selectedContact = this.formattedContacts.find(
                contact => contact.id === performerId
            );
            if (selectedContact) {
                this.mobile = selectedContact.mobile;
            }
        },
        getContacts() {
            axios
                .get(APP_URL + `departments/${this.department_id}/contacts`)
                .then(response => {
                    this.contacts = response.data;

                    //console.log(this.contacts);
                    //this.performer = '';
                })
                .catch(error => {
                    console.log(error);
                });
        },
        getProjectData() {
            axios
                .get(`${APP_URL}/staffing_projects/${this.project}`)
                .then(response => {
                    const project = response.data;

                    console.log(project);
                    this.department = project.department.name;
                    this.department_id = project.department.id;
                    //this.mobile = project.department.phone
                    this.mobile = project.project_performer.mobile;
                    //this.performer = project.project_performer.first_name
                    this.performer =
                        project.project_performer.first_name +
                        " " +
                        project.project_performer.last_name;
                    this.temp_performer = project.performer;
                    this.order_by = project.performer;
                    this.report_to = project.performer;
                    this.project_address = project.address;
                    this.zipcode = project.post_code + " " + project.city;

                    // Now that the department_id is updated, call getContacts
                    this.getContacts();
                });
        },
        handleProjectChange() {
            this.getProjectData();
            //this.getContacts();  // Call this right after getProjectData
        },
        createRequestPersonnel() {
            console.log(this.request);
            if (this.submit === true) {
                axios
                    .post(APP_URL + "request_personnels", {
                        project: this.project,
                        department: this.department,
                        project_address: this.project_address,
                        zipcode: this.zipcode,
                        performer: this.performer,
                        temp_performer: this.temp_performer,
                        order_by: this.order_by,
                        mobile: this.mobile,
                        complete: this.complete,
                        //r_mobile: this.r_mobile,
                        adopted_by: this.adopted_by,
                        application_date_time: this.application_date_time,
                        starting_date_time: this.starting_date_time,
                        no_of_people: this.no_of_people,
                        how_many_days: this.how_many_days,
                        report_to: this.report_to,
                        // activity: this.activity,
                        activities: this.activities,
                        requirements: this.requirements,
                        notes: this.notes,
                        comments: this.comments
                    })
                    .then(response => {
                        this.message = response.data.message;
                        console.log(this.message);
                        this.$emit("Request Personnel created", response.data);

                        this.project = null;
                        this.department = "";
                        this.project_address = "";
                        this.zipcode = "";
                        this.performer = "";
                        this.temp_performer = "";
                        this.order_by = "";
                        this.r_mobile = "";
                        this.adopted_by = "";
                        this.no_of_people = "";
                        this.how_many_days = "";
                        this.report_to = "";
                        this.complete = "";
                        this.activity = null;
                        this.requirements = "";
                        this.notes = "";
                        this.comments = "";

                        this.currentPage = 1;
                        this.submit = false;

                        Swal.fire(
                            "Good job!",
                            (this.message = response.data.message),
                            "success"
                        );
                        let url = this.base_url + "request_personnels";
                        setTimeout(function() {
                            window.location.href = url;
                        }, 2000);
                    })
                    .catch(error => {
                        console.log("Error: " + error.message);
                        let errors = null;

                        if (error.response && error.response.status === 422) {
                            errors = error.response.data.metadata.message;
                            this.message = "Invalid input";
                        } else if (
                            error.response &&
                            error.response.data.metadata &&
                            error.response.data.metadata.message
                        ) {
                            this.message = error.response.data.metadata.message;
                        } else {
                            this.message = "Unknown error occurred";
                        }

                        Swal.fire(this.message, "", "error");
                    });
            }
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
</style>

<template>
    <div>
        <div style="margin: 10px;" v-if="currentPage === 1">
            <div class="row">
                <div class="col-md-6 form-group">
                    <span>{{ translations.project_name }}:*</span><br />
                    <input
                        class="form-control"
                        v-model="project"
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
                </div>

                <div class="col-md-6 form-group">
                    <span>{{ common.mobile }}:*</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="mobile"
                        type="text"
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
                <div style="margin: 10px; text-align: end" class="col-lg-12">
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
                    <span>{{ translations.starting_date_time }}:*</span><br />
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
                    <span>{{ translations.report_to }}:*</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="report_to"
                        type="text"
                        disabled
                    /><br />

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

                    <!-- <span>{{ translations.activities }}:*</span><br />
                    <select class="form-control" v-model="activity">
                        <option
                            v-for="activity in e_functions"
                            :value="activity.id"
                            >{{ activity.name }}</option
                        >
                    </select> -->
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
                <div style="margin: 10px; text-align: end" class="col-lg-12">
                    <a
                        style="margin-top: 10px;
                           width: 80px; "
                        class="btn btn-primary"
                        @click="pageDecrement"
                        >{{ common.back }}</a
                    >
                    <input
                        style="margin-right: 50px;
                           width: 80px;
                           margin-left: 10px;"
                        type="submit"
                        class="submit btn btn-primary"
                        value="Update"
                        @click.prevent="updateRequestPersonnel"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import SearchableDropdown from "./SearchableDropdown.vue";
import axios from "axios";
import Swal from "sweetalert2";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
    components: {
        SearchableDropdown,
        vSelect
    },
    data() {
        return {
            currentPage: 1,
            message: "",
            is_cancel: false,
            project: null,
            department: "",
            project_address: "",
            zipcode: "",
            performer: "",
            order_by: "",
            mobile: "",
            //  r_mobile: "",
            adopted_by: "",
            application_date_time: "",
            complete: "",
            starting_date_time: "",
            no_of_people: "",
            how_many_days: "",
            report_to: "",
            // activity: null,
            activities: [],
            requirements: "",
            notes: "",
            comments: "",
            base_url: APP_URL
        };
    },
    props: ["request_personnel", "e_functions", "common", "translations"],
    mounted() {
        console.log("Update component mounted");
        axios
            .get(`${APP_URL}/request_personnels/${this.request_personnel}`)
            .then(response => {
                const req_personnel = response.data;
                this.project = req_personnel.project.name;
                //this.performer = req_personnel.project.project_performer.first_name || '';
                this.performer =
                    (req_personnel.project.project_performer.first_name || "") +
                    " " +
                    (req_personnel.project.project_performer.last_name || "");
                this.order_by =
                    req_personnel.project.project_performer.first_name || "";
                this.report_to =
                    req_personnel.project.project_performer.first_name || "";
                this.project_address = req_personnel.project.address || "";
                this.zipcode =
                    req_personnel.project.post_code +
                    " " +
                    req_personnel.project.city;
                this.mobile = req_personnel.mobile || "";
                this.adopted_by = req_personnel.adopted_by || "";
                this.application_date_time =
                    req_personnel.application_date_time || "";
                this.complete = req_personnel.complete;
                this.starting_date_time =
                    req_personnel.starting_date_time || "";
                this.no_of_people = req_personnel.no_of_people || "";
                this.how_many_days = req_personnel.days || "";
                // this.activity = req_personnel.function_id || "";
                this.activities = req_personnel.function_ids;
                this.requirements = req_personnel.requirements || "";
                this.notes = req_personnel.notes || "";
                this.comments = req_personnel.comments || "";
            });
    },
    methods: {
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
        updateRequestPersonnel() {
            axios
                .put(APP_URL + "request_personnels/" + this.request_personnel, {
                    mobile: this.mobile,
                    adopted_by: this.adopted_by,
                    application_date_time: this.application_date_time,
                    complete: this.complete,
                    starting_date_time: this.starting_date_time,
                    no_of_people: this.no_of_people,
                    how_many_days: this.how_many_days,
                    // activity: this.activity,
                    activities: this.activities,
                    requirements: this.requirements,
                    notes: this.notes,
                    comments: this.comments
                })
                .then(response => {
                    Swal.fire(
                        "Good job!",
                        (this.message = response.data.message),
                        "success"
                    );
                    let url = this.base_url + "request_personnels";
                    setTimeout(function() {
                        window.location.href = url;
                    }, 1500);

                    this.$emit("Project updated", response.data);
                })
                .catch(err => {
            Swal.fire("Bad job!", err.response.data.message, "error");
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
    padding: 4px;
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
</style>

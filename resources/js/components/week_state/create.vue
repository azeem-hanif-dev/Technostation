<template>
    <div>
        <h4>{{ translations.create_week }}</h4>
        <form @submit.prevent="createWeekState">
            <div style="margin: 10px;">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <span>{{ translations.week_no }}:</span><br />
                                <input
                                    class="form-control"
                                    v-model="week_no"
                                    type="number"
                                /><br />
                            </div>
                            <div class="col-md-3">
                                <span>{{ translations.approved }}:</span><br />
                                <input
                                    style="width: 35px;"
                                    class="form-control"
                                    v-model="approved"
                                    type="checkbox"
                                /><br />
                            </div>
                            <div class="col-md-3">
                                <span>{{ translations.worksheet }}:</span><br />
                                <input
                                    style="width: 35px;"
                                    class="form-control"
                                    v-model="via_worksheet"
                                    type="checkbox"
                                /><br />
                            </div>
                        </div>
                        <span>{{ translations.project_name }}:*</span><br />
                        <!-- <select class="form-control" v-model="project" @change="getProjectData">
                        <option v-for="project in projects" :value="project.id">{{ project.name }}</option>
                    </select> -->
                        <v-select
                            :options="projects"
                            label="name"
                            :reduce="project => project.id"
                            v-model="project"
                            @input="getProjectData"
                            placeholder="Select a project"
                        />
                        <br />

                        <span>{{ translations.delay_date }}:</span><br />
                        <input
                            class="form-control"
                            v-model="delay_date"
                            type="date"
                        /><br />

                        <span>{{ translations.received_date }}:</span><br />
                        <input
                            class="form-control"
                            v-model="received_date"
                            type="date"
                        /><br />
                        <!-- <span>{{ translations.invoice_date }}:</span><br>
                    <input class="form-control"
                           required
                           v-model="invoice_date"
                           type="date"
                    /> -->

                        <span>{{ translations.comments }}:</span><br />
                        <textarea
                            class="form-control"
                            v-model="comments"
                            type="text"
                        />
                        <span>{{ translations.notes }}:</span><br />
                        <textarea
                            class="form-control"
                            v-model="notes"
                            type="text"
                            disabled
                        /><br />
                    </div>
                    <div class="col-md-6 form-group">
                        <span>{{ translations.customer }}:</span><br />
                        <input
                            class="form-control"
                            v-model="customer"
                            type="text"
                            disabled
                        /><br />
                        <span>{{ translations.department }}:</span><br />
                        <input
                            class="form-control"
                            v-model="department"
                            type="text"
                            disabled
                        /><br />

                        <span>Performer:</span><br />
                        <input
                            class="form-control"
                            v-model="performer"
                            type="email"
                            disabled
                        /><br />

                        <span>{{ translations.dates }}:</span><br />
                        <input
                            class="form-control"
                            v-model="dates"
                            type="text"
                            disabled
                        /><br />
                        <span>{{ translations.approval }}:</span><br />
                        <input
                            class="form-control"
                            v-model="approval"
                            type="text"
                            disabled
                        /><br />
                        <span>{{ translations.internal }}:*</span><br />
                        <textarea
                            class="form-control"
                            v-model="internal_notes"
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
                            :href="base_url + 'week-state'"
                            class="mt-0 btn btn-danger"
                            style="width: 100px;"
                        >
                            {{ translations.cancel }}
                        </a>

                        <button
                            style="margin-right: 50px;
                           width: 80px;
                           margin-left: 10px;"
                            type="submit"
                            class="submit mt-0 btn btn-primary"
                            value="Add"
                        >
                            {{ translations.add }}
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
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
export default {
    components: {
        vSelect
    },
    props: ["projects", "week_no", "translations"],
    data() {
        return {
            message: "",
            week_no: "",
            project: null,
            delay_date: "",
            received_date: "",
            invoice_date: "",
            invoice_no: "",
            department: "",
            customer: "",
            notes: "",
            dates: "",
            performer: "",
            approval: "",
            approved: "",
            via_worksheet: "",
            comments: "",
            internal_notes: "",
            base_url: APP_URL
        };
    },
    mounted() {
        console.log("Mounted");
    },
    methods: {
        getProjectData() {
            axios
                .get(`${APP_URL}/staffing_projects/${this.project}`)
                .then(response => {
                    // console.log('dataaa', response.data);
                    const project = response.data;
                    this.department = project.department.name;
                    this.customer = project.customer.name;
                    this.notes = project.notes;
                    this.performer = project.performer;
                    this.dates = project.dates;
                    this.approval = project.approval;
                });
        },
        createWeekState() {
            if (!this.project) {
                Swal.fire({
                    icon: "warning",
                    title: "Required!",
                    text: "Please select a project"
                });
                return;
            }

            axios
                .post(APP_URL + "week-state", {
                    project: this.project,
                    week_no: this.week_no,
                    delay_date: this.delay_date,
                    received_date: this.received_date,
                    invoice_date: this.invoice_date,
                    notes: this.notes,
                    approval: this.approval,
                    approved: this.approved,
                    via_worksheet: this.via_worksheet,
                    comments: this.comments,
                    internal_notes: this.internal_notes
                })
                .then(response => {
                    if (response.data.status) {
                        Swal.fire("Success!", response.data.message, "success");

                        let url = this.base_url + "week-state";
                        setTimeout(function() {
                            window.location.href = url;
                        }, 1500);
                    } else {
                        Swal.fire("Error!", response.data.message, "error");
                    }
                });
        },

        // createWeekState() {
        //     console.log(this.request);
        //     axios
        //         .post(APP_URL + "week-state", {
        //             project: this.project,
        //             week_no: this.week_no,
        //             // invoice_no: this.invoice_no,
        //             delay_date: this.delay_date,
        //             received_date: this.received_date,
        //             invoice_date: this.invoice_date,
        //             approved: this.approved,
        //             via_worksheet: this.via_worksheet,
        //             comments: this.comments,
        //             internal_notes: this.internal_notes
        //         })
        //         .then(response => {
        //             console.log(response);

        //             // Swal.fire(
        //             //     this.message,
        //             //     'Week State created Successfully!',
        //             //     'success'
        //             // )

        //             // let url = this.base_url + 'week-state';
        //             // setTimeout(function () {
        //             //     window.location.href = url;
        //             // }, 1500);
        //             if (response.data.status) {
        //                 Swal.fire("Success!", response.data.message, "success");

        //                 let url = this.base_url + "week-state";
        //                 setTimeout(function() {
        //                     window.location.href = url;
        //                 }, 1500);
        //             } else {
        //                 Swal.fire("Error!", response.data.message, "error");
        //             }
        //         });
        // },
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
</style>

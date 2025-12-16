<template>
    <div>
        <h4 class="p-3 mt-2">{{ translations.title }}</h4>
        <form
            @submit.prevent="submit"
            enctype="multipart/form-data"
            class="mt-0"
        >
            <div class="row p-4">
                <!-- Left Column -->
                <div class="col-md-6 form-group">
                    <span>{{ translations.labels.camp_name }}:*</span><br />
                    <v-select
                        :options="projects"
                        label="name"
                        :reduce="project => project.id"
                        v-model="form.project_id"
                        @input="getProjectData"
                        placeholder="Select a project"
                    />
                    <br />

                    <span>{{ translations.labels.customer_name }}</span
                    ><br />
                    <input
                        v-model="form.customer_name"
                        class="form-control"
                        readonly
                    /><br />

                    <span>{{ translations.labels.department }}:</span><br />
                    <input
                        v-model="form.department_name"
                        class="form-control"
                        readonly
                    /><br />

                    <span>{{ translations.labels.supervisor_name }}:</span
                    ><br />
                    <input
                        v-model="form.supervisor_name"
                        class="form-control"
                        readonly
                    /><br />
                </div>

                <!-- Right Column -->
                <div class="col-md-6 form-group">
                    <span>{{ translations.labels.week_no }}:</span><br />
                    <v-select
                        v-model="form.week_no"
                        :options="weekOptions"
                        multiple
                        :close-on-select="false"
                        placeholder="Select Weeks"
                    ></v-select>
                    <br />

                    <span>{{ translations.labels.start_date }}:</span><br />
                    <input
                        type="date"
                        v-model="form.start_date"
                        class="form-control"
                    />
                    <br />
                    <span>{{ translations.labels.end_date }}:</span><br />
                    <input
                        type="date"
                        v-model="form.end_date"
                        class="form-control"
                    />
                    <br />

                    <span>{{ translations.labels.work_type }}:</span><br />
                    <select v-model="form.work_type" class="form-control">
                        <option value="Morning">Morning</option>
                        <option value="Extra time">Extra time</option>
                        <option value="full time">Full time</option>
                    </select>
                    <br />

                    <span>Notes:</span><br />
                    <textarea
                        v-model="form.notes"
                        class="form-control"
                        rows="3"
                        placeholder="Enter notes..."
                    ></textarea>
                    <br />
                </div>
            </div>

            <hr />

            <!-- Personnel Rows -->
            <div class="row" style="margin: 10px;">
                <div class="col-12">
                    <h5>{{ translations.labels.week_state }}:</h5>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Staff</th>
                                <th>Mon</th>
                                <th>Tue</th>
                                <th>Wed</th>
                                <th>Thu</th>
                                <th>Fri</th>
                                <th>Sat</th>
                                <th>Sun</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(row, idx) in form.rows" :key="idx">
                                <td style="min-width: 180px;">
                                    <v-select
                                        v-model="row.personnel_id"
                                        :options="personnelsWithFullName"
                                        label="fullName"
                                        :reduce="p => p.id"
                                        :searchable="true"
                                        :clearable="false"
                                    ></v-select>
                                </td>

                                <td>
                                    <input
                                        type="number"
                                        step="1"
                                        min="0"
                                        v-model.number="row.hours_mon"
                                        @input="recalcRow(row)"
                                        class="form-control"
                                    />
                                </td>
                                <td>
                                    <input
                                        type="number"
                                        step="1"
                                        min="0"
                                        v-model.number="row.hours_tue"
                                        @input="recalcRow(row)"
                                        class="form-control"
                                    />
                                </td>
                                <td>
                                    <input
                                        type="number"
                                        step="1"
                                        min="0"
                                        v-model.number="row.hours_wed"
                                        @input="recalcRow(row)"
                                        class="form-control"
                                    />
                                </td>
                                <td>
                                    <input
                                        type="number"
                                        step="1"
                                        min="0"
                                        v-model.number="row.hours_thu"
                                        @input="recalcRow(row)"
                                        class="form-control"
                                    />
                                </td>
                                <td>
                                    <input
                                        type="number"
                                        step="1"
                                        min="0"
                                        v-model.number="row.hours_fri"
                                        @input="recalcRow(row)"
                                        class="form-control"
                                    />
                                </td>
                                <td>
                                    <input
                                        type="number"
                                        step="1"
                                        min="0"
                                        v-model.number="row.hours_sat"
                                        @input="recalcRow(row)"
                                        class="form-control"
                                    />
                                </td>
                                <td>
                                    <input
                                        type="number"
                                        step="1"
                                        min="0"
                                        v-model.number="row.hours_sun"
                                        @input="recalcRow(row)"
                                        class="form-control"
                                    />
                                </td>
                                <td class="text-center">
                                    {{ row.total_hours.toFixed(2) }}
                                </td>
                                <td class="text-center">
                                    <a
                                        href="#"
                                        v-if="form.rows.length > 1"
                                        class="text-danger"
                                        @click="removeRow(idx)"
                                        ><i class="fa fa-minus-circle"></i
                                    ></a>
                                    <button
                                        v-if="form.rows.length > 1"
                                        type="button"
                                        class="text-danger"
                                        @click="removeRow(idx)"
                                    >
                                        <i class="fa fa-minus"></i>
                                    </button>
                                    <button
                                        v-if="idx === form.rows.length - 1"
                                        type="button"
                                        class="text-success"
                                        @click="addRow"
                                    >
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <hr />

            <!-- Documents Section -->
            <div style="margin: 10px;" class="row">
                <div class="col-12">
                    <h5>Documents</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Expiry Date</th>
                                <th>File</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(doc, idx) in form.documents" :key="idx">
                                <td>
                                    <input
                                        type="text"
                                        v-model="doc.type"
                                        class="form-control"
                                        placeholder="Document type"
                                    />
                                </td>
                                <td>
                                    <input
                                        type="date"
                                        v-model="doc.expiry_date"
                                        class="form-control"
                                    />
                                </td>
                                <td>
                                    <input
                                        type="file"
                                        @change="onFileChange($event, idx)"
                                        class="form-control"
                                    />
                                </td>
                                <td class="text-center">
                                    <button
                                        v-if="form.documents.length > 1"
                                        type="button"
                                        class="btn btn-danger btn-sm circle-btn"
                                        @click="removeDocument(idx)"
                                    >
                                        <i class="fa fa-minus"></i>
                                    </button>
                                    <button
                                        v-if="idx === form.documents.length - 1"
                                        type="button"
                                        class="btn btn-success btn-sm circle-btn"
                                        @click="addDocument"
                                    >
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <hr />

            <!-- Buttons -->
            <div class="row">
                <div style="margin: 10px; text-align: end" class="col-lg-12">
                    <a
                        :href="base_url + 'camp-maintenance'"
                        class="submit btn btn-danger"
                        style="width: 100px;"
                        >Cancel</a
                    >

                    <button
                        style="margin-right: 50px; width: 80px; margin-left: 10px;"
                        type="submit"
                        class="submit btn btn-primary"
                        :disabled="loading"
                    >
                        <span v-if="loading">{{
                            translations.buttons.saving
                        }}</span>
                        <span v-else>{{ translations.buttons.submit }}</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import axios from "axios";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import Swal from "sweetalert2";

export default {
    name: "CampMaintenanceForm",
    components: { vSelect },
    props: ["projects", "personnels", "translations"],
    data() {
        return {
            loading: false,
            base_url: APP_URL,
            form: {
                week_no: [],
                project_id: "",
                customer_name: "",
                department_name: "",
                supervisor_name: "",
                week_no: "",
                start_date: "",
                end_date: "",
                notes: "",
                work_type: "",
                rows: [
                    {
                        personnel_id: "",
                        hours_mon: 0,
                        hours_tue: 0,
                        hours_wed: 0,
                        hours_thu: 0,
                        hours_fri: 0,
                        hours_sat: 0,
                        hours_sun: 0,
                        total_hours: 0
                    }
                ],
                documents: [{ type: "", expiry_date: "", file: null }]
            },
            weekOptions: this.generateWeeks()
        };
    },
    computed: {
        personnelsWithFullName() {
            return this.personnels.map(p => ({
                ...p,
                fullName: `${p.first_name} ${p.last_name}`
            }));
        }
    },
    mounted() {
        const today = new Date();

        const getWeekNumber = date => {
            const firstDayOfYear = new Date(date.getFullYear(), 0, 1);
            const pastDaysOfYear = (date - firstDayOfYear) / 86400000;
            return Math.ceil(
                (pastDaysOfYear + firstDayOfYear.getDay() + 1) / 7
            );
        };
        const weekNo = getWeekNumber(today);
        const year = today.getFullYear();
        if (!this.form.week_no) {
            //this.form.week_no = `${year}${weekNo.toString().padStart(2, "0")}`;
        }

        if (!this.form.start_date) {
            this.form.start_date = today.toISOString().split("T")[0];
        }
    },
    methods: {
        generateWeeks() {
            const currentYear = new Date().getFullYear();
            const weeks = [];
            for (let i = 1; i <= 53; i++) {
                const weekString = `${currentYear}${String(i).padStart(
                    2,
                    "0"
                )}`;
                weeks.push(weekString);
            }
            return weeks;
        },
        getProjectData() {
            if (!this.form.project_id) return;
            this.loading = true;
            axios
                .get(`${APP_URL}/staffing_projects/${this.form.project_id}`)
                .then(response => {
                    const project = response.data;
                    this.form.customer_name =
                        (project.customer &&
                            (project.customer.name ||
                                project.customer.title)) ||
                        "";
                    this.form.department_name =
                        (project.department && project.department.name) || "";
                    this.form.supervisor_name = project.project_performer
                        ? `${project.project_performer.first_name} ${project
                              .project_performer.last_name || ""}`
                        : "";
                })
                .catch(() =>
                    Swal.fire(
                        "Error",
                        "Failed to load project details",
                        "error"
                    )
                )
                .finally(() => (this.loading = false));
        },

        addRow() {
            this.form.rows.push({
                personnel_id: "",
                hours_mon: 0,
                hours_tue: 0,
                hours_wed: 0,
                hours_thu: 0,
                hours_fri: 0,
                hours_sat: 0,
                hours_sun: 0,
                total_hours: 0
            });
        },

        removeRow(idx) {
            this.form.rows.splice(idx, 1);
        },

        recalcRow(row) {
            row.total_hours =
                (row.hours_mon || 0) +
                (row.hours_tue || 0) +
                (row.hours_wed || 0) +
                (row.hours_thu || 0) +
                (row.hours_fri || 0) +
                (row.hours_sat || 0) +
                (row.hours_sun || 0);
        },

        addDocument() {
            this.form.documents.push({ type: "", expiry_date: "", file: null });
        },

        removeDocument(idx) {
            this.form.documents.splice(idx, 1);
        },

        onFileChange(event, idx) {
            const file = event.target.files[0];
            if (file) {
                this.form.documents[idx].file = file; // store the File object
            }
        },

        async submit() {
            if (!this.form.project_id)
                return Swal.fire("Error", "Select a project first", "warning");

            this.loading = true;
            const fd = new FormData();

            const weekString = Array.isArray(this.form.week_no)
                ? this.form.week_no.join(",")
                : this.form.week_no;

            fd.append("project_id", this.form.project_id);
            fd.append("week_no", weekString);
            fd.append("start_date", this.form.start_date);
            fd.append("end_date", this.form.end_date);
            fd.append("notes", this.form.notes);
            fd.append("work_type", this.form.work_type);

            this.form.rows.forEach((r, i) => {
                fd.append(`rows[${i}][personnel_id]`, r.personnel_id);
                fd.append(`rows[${i}][hours_mon]`, r.hours_mon);
                fd.append(`rows[${i}][hours_tue]`, r.hours_tue);
                fd.append(`rows[${i}][hours_wed]`, r.hours_wed);
                fd.append(`rows[${i}][hours_thu]`, r.hours_thu);
                fd.append(`rows[${i}][hours_fri]`, r.hours_fri);
                fd.append(`rows[${i}][hours_sat]`, r.hours_sat);
                fd.append(`rows[${i}][hours_sun]`, r.hours_sun);
            });

            this.form.documents.forEach((doc, i) => {
                fd.append(`documents[${i}][type]`, doc.type);
                fd.append(`documents[${i}][expiry_date]`, doc.expiry_date);
                if (doc.file) fd.append(`documents[${i}][file]`, doc.file);
            });

            try {
                const resp = await axios.post("/camp-maintenance", fd, {
                    headers: { "Content-Type": "multipart/form-data" }
                });

                if (resp.data.status === 200) {
                    Swal.fire(
                        "Good job!",
                        resp.data.message ||
                            "Camp Maintenance Created Successfully!",
                        "success"
                    );
                    setTimeout(() => {
                        window.location.href =
                            this.base_url + "camp-maintenance";
                    }, 1500);
                } else {
                    Swal.fire(
                        "Error",
                        resp.data.message || "Unknown error",
                        "error"
                    );
                }
            } catch (e) {
                Swal.fire("Server Error", "Failed to save project", "error");
            } finally {
                this.loading = false;
            }
        }
    }
};
</script>

<style>
.vs__dropdown-menu {
    background-color: #fff !important;
    color: #000 !important;
    z-index: 9999 !important;
}
.vs__dropdown-option {
    color: #000 !important;
    background-color: #fff !important;
}
.vs__dropdown-option--highlight {
    background-color: #2445ff !important;
    color: #fff !important;
}
.v-select {
    z-index: 9999 !important;
}
/* form {
    padding: 10px;
} */
span {
    font-size: 16px;
    font-weight: 500;
}
input,
textarea,
select {
    margin-bottom: 8px;
}
.table td input {
    width: 100%;
}
.submit {
    font-size: 15px;
    color: #fff;
    background: #2445ff;
    padding: 6px 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

/* Inline clean look */
.circle-btn {
    border-radius: 50%;
    width: 28px;
    height: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    margin: 0 2px;
}

.table td {
    vertical-align: middle;
}

.table input[type="text"],
.table input[type="date"],
.table input[type="file"] {
    height: 36px;
    padding: 4px 8px;
}
</style>

<template>
    <div>
        <h4 class="p-3 mt-2">{{ translations.update_title }}</h4>
        <form @submit.prevent="update" enctype="multipart/form-data">
            <!-- Project Info -->
            <div style="margin: 10px;" class="row">
                <div class="col-md-6 form-group">
                    <span>{{ translations.labels.camp_name }}:*</span><br />
                    <v-select
                        :options="projects"
                        label="name"
                        :reduce="p => p.id"
                        v-model="form.project_id"
                        placeholder="Select project"
                        @input="onProjectChange"
                    />
                    <br />

                    <span>{{ translations.labels.customer_name }}:</span><br />
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

                <div class="col-md-6 form-group">
                    <span>{{ translations.labels.week_no }}:</span><br />
                    <v-select
                        v-model="form.week_no"
                        :options="weekOptions"
                        placeholder="Select Weeks"
                        multiple
                        clearable
                    ></v-select
                    ><br />

                    <span>{{ translations.labels.start_date }}:</span><br />
                    <input
                        type="date"
                        v-model="form.start_date"
                        class="form-control"
                    /><br />

                    <span>{{ translations.labels.end_date }}:</span><br />
                    <input
                        type="date"
                        v-model="form.end_date"
                        class="form-control"
                    /><br />

                    <span>{{ translations.labels.work_type }}:</span><br />
                    <select v-model="form.work_type" class="form-control">
                        <option value="Morning">Morning</option>
                        <option value="Extra time">Extra time</option>
                        <option value="Full time">Full time</option> </select
                    ><br />

                    <span>Notes:</span><br />
                    <textarea
                        v-model="form.notes"
                        class="form-control"
                        rows="3"
                    ></textarea>
                </div>
            </div>

            <hr />

            <!-- Personnel -->
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
                                <td>
                                    <v-select
                                        v-model="row.personnel_id"
                                        :options="personnelsWithFullName"
                                        label="fullName"
                                        :reduce="p => p.id"
                                        :clearable="false"
                                    ></v-select>
                                </td>
                                <td
                                    v-for="day in [
                                        'mon',
                                        'tue',
                                        'wed',
                                        'thu',
                                        'fri',
                                        'sat',
                                        'sun'
                                    ]"
                                    :key="day"
                                >
                                    <input
                                        type="number"
                                        step="1"
                                        min="0"
                                        v-model.number="row['hours_' + day]"
                                        @input="recalc(row)"
                                        class="form-control"
                                    />
                                </td>
                                <td class="text-center">
                                    {{ row.total_hours.toFixed(2) }}
                                </td>
                                <td class="text-center">
                                    <button
                                        v-if="form.rows.length > 1"
                                        class="btn btn-danger btn-sm circle-btn"
                                        @click.prevent="removeRow(idx)"
                                    >
                                        <i class="fa fa-minus"></i>
                                    </button>
                                    <button
                                        v-if="idx === form.rows.length - 1"
                                        class="btn btn-success btn-sm circle-btn"
                                        @click.prevent="addRow"
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

            <!-- Documents -->
            <div class="row" style="margin: 10px;">
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
                                    <div v-if="doc.old_file && !doc.file">
                                        <a
                                            :href="doc.old_file"
                                            target="_blank"
                                            >{{
                                                doc.old_file.split("/").pop()
                                            }}</a
                                        >
                                    </div>
                                    <input
                                        type="file"
                                        @change="onFileChange($event, idx)"
                                        class="form-control mt-1"
                                    />
                                </td>
                                <td class="text-center">
                                    <button
                                        v-if="form.documents.length > 1"
                                        class="btn btn-danger btn-sm circle-btn"
                                        @click.prevent="removeDocument(idx)"
                                    >
                                        <i class="fa fa-minus"></i>
                                    </button>
                                    <button
                                        v-if="idx === form.documents.length - 1"
                                        class="btn btn-success btn-sm circle-btn"
                                        @click.prevent="addDocument"
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
                    >
                        {{ translations.buttons.cancel }}
                    </a>

                    <button
                        :disabled="loading"
                        class="submit btn btn-primary"
                        style="width: 80px; margin-left: 10px;"
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
import Swal from "sweetalert2";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
    name: "CampMaintenanceUpdate",
    components: { vSelect },
    props: ["projects", "personnels", "camp", "translations"],
    data() {
        return {
            weekOptions: this.generateWeeks(),
            base_url: APP_URL,
            loading: false,
            form: {
                project_id: "",
                customer_name: "",
                department_name: "",
                supervisor_name: "",
                week_no: "",
                date: "",
                work_type: "Morning",
                notes: "",
                rows: [],
                documents: []
            }
        };
    },
    mounted() {
        console.log("Camp data:", this.camp); // Debug log

        if (this.camp) {
            // Load form data from camp prop
            this.form.project_id = this.camp.project_id
                ? Number(this.camp.project_id)
                : "";
            this.form.customer_name = this.camp.customer_name || "";
            this.form.department_name = this.camp.department_name || "";
            this.form.supervisor_name = this.camp.supervisor_name || "";
            //this.form.week_no = this.camp.week_no || "";
            this.form.week_no = this.camp.week_no
                ? this.camp.week_no.split(",") // assuming stored as "202501,202502,202503"
                : [];
            this.form.date = this.camp.date || "";
            this.form.work_type = this.camp.work_type || "Morning";
            this.form.notes = this.camp.notes || "";

            // Load rows with personnel hours
            if (this.camp.week_states && this.camp.week_states.length > 0) {
                this.form.rows = this.camp.week_states.map(function(r) {
                    return {
                        personnel_id: Number(r.personnel_id) || "",
                        hours_mon: Number(r.hours_mon) || 0,
                        hours_tue: Number(r.hours_tue) || 0,
                        hours_wed: Number(r.hours_wed) || 0,
                        hours_thu: Number(r.hours_thu) || 0,
                        hours_fri: Number(r.hours_fri) || 0,
                        hours_sat: Number(r.hours_sat) || 0,
                        hours_sun: Number(r.hours_sun) || 0,
                        total_hours: 0
                    };
                });
                // Calculate totals for each row
                var self = this;
                this.form.rows.forEach(function(row) {
                    self.recalc(row);
                });
            } else {
                // If no rows, add one empty row
                this.addRow();
            }

            // Load documents
            if (this.camp.documents && this.camp.documents.length > 0) {
                this.form.documents = this.camp.documents.map(d => ({
                    id: d.id || null,
                    type: d.type || "",
                    expiry_date: d.expiry_date || "",
                    old_file: d.file || "",
                    file: null
                }));
            } else {
                // If no documents, add one empty document
                this.addDocument();
            }
        } else {
            // Initialize with empty row and document if no camp data
            this.addRow();
            this.addDocument();
        }
    },
    computed: {
        personnelsWithFullName: function() {
            return this.personnels.map(function(p) {
                return {
                    id: p.id,
                    first_name: p.first_name,
                    last_name: p.last_name,
                    fullName: p.first_name + " " + p.last_name
                };
            });
        }
    },
    watch: {
        "form.project_id": function(newVal) {
            if (newVal) {
                this.updateProjectDetails(newVal);
            }
        }
    },
    methods: {
        generateWeeks() {
            const currentYear = new Date().getFullYear();
            const weeks = [];
            for (let i = 1; i <= 53; i++) {
                weeks.push(`${currentYear}${String(i).padStart(2, "0")}`);
            }
            return weeks;
        },
        onProjectChange: function(projectId) {
            this.updateProjectDetails(projectId);
        },
        updateProjectDetails: function(projectId) {
            var self = this;
            var project = this.projects.find(function(p) {
                return p.id === projectId;
            });
            if (project) {
                self.form.customer_name =
                    project.customer && project.customer.name
                        ? project.customer.name
                        : "";
                self.form.department_name =
                    project.department && project.department.name
                        ? project.department.name
                        : "";
                // Keep supervisor from the camp
                self.form.supervisor_name = self.camp.supervisor_name || "";
            }
        },
        addRow: function() {
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
        removeRow: function(idx) {
            this.form.rows.splice(idx, 1);
        },
        recalc: function(row) {
            row.total_hours =
                (Number(row.hours_mon) || 0) +
                (Number(row.hours_tue) || 0) +
                (Number(row.hours_wed) || 0) +
                (Number(row.hours_thu) || 0) +
                (Number(row.hours_fri) || 0) +
                (Number(row.hours_sat) || 0) +
                (Number(row.hours_sun) || 0);
        },
        addDocument: function() {
            this.form.documents.push({
                type: "",
                expiry_date: "",
                file: null,
                old_file: ""
            });
        },
        removeDocument: function(idx) {
            this.form.documents.splice(idx, 1);
        },
        onFileChange: function(e, idx) {
            this.form.documents[idx].file = e.target.files[0];
        },
        update: function() {
            var self = this;

            // Validation
            if (!this.form.project_id) {
                Swal.fire("Error", "Please select a project", "error");
                return;
            }
            if (!this.form.week_no) {
                Swal.fire("Error", "Please enter week number", "error");
                return;
            }
            if (this.form.rows.length === 0) {
                Swal.fire(
                    "Error",
                    "Please add at least one personnel",
                    "error"
                );
                return;
            }

            this.loading = true;
            var fd = new FormData();

            // Append simple fields
            for (var key in this.form) {
                if (!["rows", "documents"].includes(key)) {
                    if (key === "week_no") {
                        // join array into string for FormData
                        fd.append(
                            "week_no",
                            (this.form.week_no || []).join(",")
                        );
                    } else {
                        fd.append(key, this.form[key] || "");
                    }
                }
            }

            // Append rows
            this.form.rows.forEach(function(r, i) {
                for (var key in r) {
                    if (key !== "total_hours") {
                        fd.append("rows[" + i + "][" + key + "]", r[key] || 0);
                    }
                }
            });

            // Append documents
            this.form.documents.forEach(function(d, i) {
                fd.append("documents[" + i + "][id]", d.id || "");
                fd.append("documents[" + i + "][type]", d.type || "");
                fd.append(
                    "documents[" + i + "][expiry_date]",
                    d.expiry_date || ""
                );
                if (d.file) {
                    fd.append(`documents[${i}][file]`, d.file);
                }
            });

            axios
                .post(
                    APP_URL +
                        "camp-maintenance/" +
                        this.camp.id +
                        "?_method=PUT",
                    fd,
                    {
                        headers: { "Content-Type": "multipart/form-data" }
                    }
                )
                .then(function(res) {
                    Swal.fire(
                        "Updated!",
                        res.data.message || "Project updated successfully",
                        "success"
                    );

                    setTimeout(function() {
                        window.location.href =
                            self.base_url + "camp-maintenance";
                    }, 1500);
                })
                .catch(function(err) {
                    console.error("Update error:", err);
                    var errorMsg =
                        err.response &&
                        err.response.data &&
                        err.response.data.message
                            ? err.response.data.message
                            : "Failed to update project";
                    Swal.fire("Error", errorMsg, "error");
                })
                .finally(function() {
                    self.loading = false;
                });
        }
    }
};
</script>

<style scoped>
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
.table input[type="file"],
.table input[type="number"] {
    height: 36px;
    padding: 4px 8px;
}
.submit {
    font-size: 15px;
    color: #fff;
    background: #2445ff;
    padding: 6px 12px;
    border: none;
    border-radius: 5px;
}
</style>

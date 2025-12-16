<template>
    <div>
        <div v-if="camp" class="card shadow-sm">
            <div class="card-header text-black bg-light">
                <h3 class="mb-0">Project Details</h3>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p>
                            <strong>Project ID:</strong> {{ camp.project_id }}
                        </p>
                        <p>
                            <strong>Customer:</strong> {{ camp.customer_name }}
                        </p>
                        <p>
                            <strong>Department:</strong>
                            {{ camp.department_name }}
                        </p>
                        <p><strong>Notes:</strong> {{ camp.notes }}</p>
                    </div>
                    <div class="col-md-6">
                        <p>
                            <strong>Supervisor:</strong>
                            {{ camp.supervisor_name }}
                        </p>
                        <p><strong>Weeks:</strong> {{ camp.week_no }}</p>
                        <p>
                            <strong>Start Date:</strong> {{ camp.start_date }}
                        </p>
                        <p><strong>End Date:</strong> {{ camp.end_date }}</p>
                        <p><strong>Work Type:</strong> {{ camp.work_type }}</p>
                    </div>
                </div>
                <h5 class="mt-4">Personnel / Hours</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
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
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(row, idx) in camp.week_states"
                                :key="idx"
                            >
                                <td>
                                    {{
                                        row.personnel
                                            ? row.personnel.first_name +
                                              " " +
                                              row.personnel.last_name
                                            : "N/A"
                                    }}
                                </td>
                                <td>{{ row.hours_mon }}</td>
                                <td>{{ row.hours_tue }}</td>
                                <td>{{ row.hours_wed }}</td>
                                <td>{{ row.hours_thu }}</td>
                                <td>{{ row.hours_fri }}</td>
                                <td>{{ row.hours_sat }}</td>
                                <td>{{ row.hours_sun }}</td>
                                <td>{{ row.total_hours }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Documents table -->
                <h5 class="mt-4">Documents</h5>
                <div
                    class="table-responsive"
                    v-if="camp.documents && camp.documents.length"
                >
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Type</th>
                                <th>Expiry Date</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(doc, idx) in camp.documents" :key="idx">
                                <td>{{ doc.type ? doc.type : "N/A" }}</td>
                                <td>
                                    {{
                                        doc.expiry_date
                                            ? doc.expiry_date
                                            : "N/A"
                                    }}
                                </td>
                                <td>
                                    <a
                                        v-if="doc.file"
                                        :href="`/storage/${doc.file}`"
                                        target="_blank"
                                    >
                                        {{ doc.file.split("/").pop() }}
                                    </a>
                                    <span v-else>N/A</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else>
                    <p>No documents uploaded.</p>
                </div>
            </div>
        </div>

        <div v-else>
            <div class="alert alert-warning mt-4">No data available.</div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ShowCampMaintenance",
    props: {
        camp: {
            type: Object,
            required: true
        }
    }
};
</script>

<style scoped>
.card {
    margin-bottom: 20px;
}
</style>

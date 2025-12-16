<template>
    <div>
        <h4>{{ translations.edit_week_state }}</h4>
        <div class="row">
            <div class="row">
                <label class="ml-5 mr-2 mt-2">Year</label>
                <select
                    v-model="year"
                    style="width: 100px;"
                    class="form-control"
                    name="year"
                >
                    <option
                        v-for="year in lastTenYears"
                        :key="year"
                        :value="year"
                        >{{ year }}</option
                    >
                </select>
                <label class="ml-2 mr-2 mt-2">Week no.</label>
                <select
                    v-model="week"
                    style="width: 70px;"
                    class="form-control"
                    name="week_no"
                >
                    <option
                        v-for="i in 52"
                        :key="i"
                        :value="i < 10 ? '0' + i : i"
                        >{{ i < 10 ? "0" + i : i }}</option
                    >
                </select>
                <button
                    @click="submitForm"
                    type="submit"
                    class="btn btn-primary btn-sm ml-2"
                >
                    Search
                </button>
            </div>

            <input
                class="form-control"
                v-model="search"
                @input="searchBar"
                placeholder="Search For Project"
                style="margin-left: 70px;width:200px"
            />
            <input
                class="form-control"
                v-model="searchPersonnel"
                @input="searchPersonnelList"
                placeholder="Search Personnel"
                style="margin-left: 20px;width:200px"
            />

            <div class="col mr-5" style="text-align:end">
                <a
                    :href="base_url + 'week-state/create'"
                    class="btn btn-success ml-2"
                >
                    <i class="fa fa-plus"> New Week State</i>
                </a>
            </div>
        </div>

        <!-- <div v-for="(personnel, index) in filteredPersonnels" :key="index">
    <h5>{{ personnel.first_name }} {{ personnel.last_name }}</h5>
</div> -->
        <div style="margin: 10px;">
            <!--div v-for="(project, index) in projects" :key="index" >
                <h5>{{ project.department.name }} > {{ project.name }} > {{ project.address }}</h5-->

            <!-- <div v-for="(projectData, index) in  searchBar" :key="index"> -->
            <!-- <div v-for="(projectData, index) in (searchPersonnelList.length > 0 ? searchPersonnelList : searchBar)" :key="index"> -->
            <div v-for="(projectData, index) in filteredProjects" :key="index">
                <!--            <div v-for="(projectData, index) in projectWisePersonnelUpdateLogs" :key="index">-->
                <h5
                    v-if="projectData.project && projectData.project.department"
                >
                    {{ projectData.project.department.name }} >
                    {{ projectData.project.name }} >
                    {{ projectData.project.address }}
                </h5>
                <div v-else>
                    <h5 v-if="!projectData.project">Project Data Missing</h5>
                    <h5 v-else-if="!projectData.project.department">
                        Department Data Missing
                    </h5>
                </div>
                <!--h5>{{ projectData.project.department.name }} > {{ projectData.project.name }} > {{ projectData.project.address }}</h5-->

                <div class="form-row card">
                    <div class="row" style="margin: 10px;">
                        <h5>Worked Hours</h5>
                        <div
                            class="row"
                            v-for="(weekly_state,
                            wsIndex) in projectData.weekly_states_data"
                            :key="wsIndex"
                            style="width: 100%;"
                        >
                            <div
                                class="d-flex justify-content-between align-items-center w-100"
                            >
                                <a
                                    href="#"
                                    class="btn btn-success ml-4"
                                    @click.prevent="
                                        downloadPdf(
                                            weekly_state.id,
                                            projectData.project.id,
                                            0
                                        )
                                    "
                                >
                                    <i>PDF</i>
                                </a>
                                <div>
                                    <a :href="base_url+'week-state/'+ weekly_state.id +'/edit?project_id='+ projectData.project.id"
                                       class="btn btn-success ml-2">
                                        <i class="fa fa-search"></i>
                                    </a>
                                    <a
                                        href="#"
                                        class="btn btn-success ml-2"
                                        @click.prevent="
                                            downloadPdf(
                                                weekly_state.id,
                                                projectData.project.id,
                                                1
                                            )
                                        "
                                    >
                                    <i>PDF</i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div v-if="projectData.weekly_states_data.length === 0">
                            <p>
                                No weekly states data available for current week
                                number {{ week_no }}.
                            </p>
                        </div>
                    </div>

                    <table class=" table table-bordered sort">
                        <thead>
                            <tr>
                                <th>{{ translations.staff }}</th>

                                <th>{{ translations.mon }}</th>

                                <th>{{ translations.tue }}</th>

                                <th>{{ translations.wed }}</th>

                                <th>{{ translations.thu }}</th>

                                <th>{{ translations.fri }}</th>

                                <th>{{ translations.sat }}</th>

                                <th>{{ translations.sun }}</th>

                                <th>+</th>

                                <th>{{ translations.customer }}</th>

                                <th>{{ translations.cost }}</th>

                                <th style="width: 10px;">
                                    {{ translations.directing }}
                                </th>

                                <th>{{ translations.comments }}</th>
                                <!--th>{{ translations.action }}</th-->
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(personnel_update_log,
                                logIndex) in projectData.personnel_update_logs"
                                :key="logIndex"
                            >
                                <td style="width: 200px">
                                    {{
                                        getPersonnelName(
                                            personnel_update_log.personnel
                                        )
                                    }}
                                </td>

                                <td>{{ personnel_update_log.hours_1 }}</td>
                                <td>{{ personnel_update_log.hours_2 }}</td>
                                <td>{{ personnel_update_log.hours_3 }}</td>
                                <td>{{ personnel_update_log.hours_4 }}</td>
                                <td>{{ personnel_update_log.hours_5 }}</td>
                                <td>{{ personnel_update_log.hours_6 }}</td>
                                <td>{{ personnel_update_log.hours_7 }}</td>

                                <td>{{ personnel_update_log.total_hours }}</td>
                                <td>{{ personnel_update_log.rate }}</td>
                                <td>{{ personnel_update_log.cost }}</td>
                                <td>
                                    {{
                                        personnel_update_log.directing
                                            ? "Yes"
                                            : "No"
                                    }}
                                </td>
                                <td>{{ personnel_update_log.wage }}</td>
                            </tr>
                            <!-- Weekly State Columns Section -->
                            <tr>
                                <td colspan="15">
                                    <div
                                        v-for="(weekly_state,
                                        wsIndex) in projectData.weekly_states_data"
                                        :key="wsIndex"
                                    >
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        {{ translations.date }}
                                                    </th>
                                                    <th>
                                                        {{
                                                            translations.received_date
                                                        }}
                                                    </th>
                                                    <th>
                                                        {{
                                                            translations.approval
                                                        }}
                                                    </th>
                                                    <th>
                                                        {{
                                                            translations.invoice_date
                                                        }}
                                                    </th>
                                                    <th>
                                                        {{
                                                            translations.invoice_no
                                                        }}
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        {{
                                                            weekly_state.created_at
                                                        }}
                                                    </td>
                                                    <td>
                                                        {{
                                                            weekly_state.receive_date
                                                        }}
                                                    </td>
                                                    <td>
                                                        {{
                                                            weekly_state.approved
                                                                ? "Yes"
                                                                : "No"
                                                        }}
                                                    </td>
                                                    <td>
                                                        {{
                                                            weekly_state.invoice_date
                                                        }}
                                                    </td>
                                                    <td>
                                                        {{
                                                            weekly_state.invoice_no
                                                        }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";
import $ from "jquery";

// function projectWisePersonnelUpdateLogs() {}

export default {
    props: ["personnels", "wages", "translations", "projects", "week_no"],
    beforeMount() {
        //this.personnel_logs.push(Object.assign({}, this.personnel_log));
    },
    data() {
        return {
            message: "",
            personnel_update_log: {
                week_card_id: "",
                personnel: null,
                hours_1: 0,
                hours_2: 0,
                hours_3: 0,
                hours_4: 0,
                hours_5: 0,
                hours_6: 0,
                hours_7: 0,
                total_hours: 0,
                rate: "",
                cost: "",
                directing: false,
                wage: ""
            },
            personnel_update_logs: [],
            week_cards: [],
            removedPersonnelIds: [],
            key_value: 0,
            year: 0,
            week: 0,
            year_week_no: "",
            base_url: APP_URL,
            search: "",
            searchPersonnel: "",
            showData: ""
        };
    },
    mounted() {
        console.log("Mounted");

        const vm = this;
        $(this.$refs.selectElement)
            .select2({
                width: "100%"
            })
            .on("select2:select", function(e) {
                vm.project = e.target.value;
                vm.getProjectData();
            });
    },
    watch: {
        project(newVal) {
            $(this.$refs.selectElement)
                .val(newVal)
                .trigger("change");
            this.getProjectData();
        },

        searchPersonnel(newVal) {
            console.log("Search query:", newVal);
            console.log("Filtered Personnel List:", this.searchPersonnelList);
        }
    },
    computed: {
        lastTenYears() {
            const currentYear = new Date().getFullYear();
            return Array.from({ length: 10 }, (v, i) => currentYear - i);
        },

        projectWisePersonnelUpdateLogs() {
            if (!this.projects || !Array.isArray(this.projects)) {
                return [];
            }

            //console.log('Update Projects list in computed property:',this.projects);

            return this.projects.map(project => {
                //console.log('Processing project:', project);

                let personnel_update_logs = [];
                let weekly_states_data = [];

                if (project.week_states && Array.isArray(project.week_states)) {
                    const weekly_states = project.week_states.filter(
                        week_state => week_state.week_no == this.week_no
                    );
                    //console.log('Filtered weekly states for week_no', this.week_no, ':', weekly_states);

                    weekly_states.forEach(weekly_state => {
                        //console.log('Processing weekly state:', weekly_state);
                        weekly_states_data.push(weekly_state); // Collect weekly state data
                        const week_cards = weekly_state.week_cards;
                        week_cards.forEach(week_card => {
                            const personnel_update_log = {
                                personnel: week_card.personnel_id,
                                directing: week_card.directing,
                                hours_1: week_card.hours_1,
                                hours_2: week_card.hours_2,
                                hours_3: week_card.hours_3,
                                hours_4: week_card.hours_4,
                                hours_5: week_card.hours_5,
                                hours_6: week_card.hours_6,
                                hours_7: week_card.hours_7,
                                total_hours: week_card.total_hours,
                                rate: week_card.customer,
                                cost: week_card.cost,
                                wage: week_card.comments,
                                week_card_id: week_card.id
                            };

                            personnel_update_logs.push(personnel_update_log);
                        });
                    });
                } else {
                    //console.log('No week_states or week_states is not an array for project:', project);
                }

                return {
                    project,
                    personnel_update_logs,
                    weekly_states_data // Include weekly states data
                };
            });
        },
        searchBar() {
            this.initializeDataTable();
            let searchLower = this.search.trim().toLowerCase();
            if (searchLower) {
                return this.projectWisePersonnelUpdateLogs.filter(item => {
                    return (
                        item.project.department.name
                            .toLowerCase()
                            .includes(searchLower) ||
                        item.project.name.toLowerCase().includes(searchLower) ||
                        item.project.address.toLowerCase().includes(searchLower)
                    );
                });

                console.log("Search Query:", this.search);
                this.showData.forEach(item => {
                    console.log(
                        "Filtered Data:",
                        item.project.department.name,
                        item.project.name
                    );
                });
            } else {
                return (this.showData = this.projectWisePersonnelUpdateLogs);
            }
        },
        searchPersonnelList() {
            this.initializeDataTable();
            let searchLower = this.searchPersonnel.trim().toLowerCase();

            if (searchLower) {
                return this.projectWisePersonnelUpdateLogs
                    .map(project => ({
                        ...project,
                        personnel_update_logs: project.personnel_update_logs.filter(
                            log => {
                                const personnel = this.personnels.find(
                                    p => p.id === log.personnel
                                );
                                console.log(personnel);
                                return (
                                    personnel &&
                                    (personnel.first_name
                                        .toLowerCase()
                                        .includes(searchLower) ||
                                        personnel.last_name
                                            .toLowerCase()
                                            .includes(searchLower))
                                );
                            }
                        )
                    }))
                    .filter(
                        project => project.personnel_update_logs.length > 0
                    ); // Only return projects with matching personnel
            } else {
                return this.projectWisePersonnelUpdateLogs;
            }
        },
        filteredProjects() {
            if (this.searchPersonnel && this.searchPersonnelList.length > 0) {
                return this.searchPersonnelList;
            }
            return this.searchBar;
        }
    },
    methods: {
        initializeDataTable() {
            this.$nextTick(() => {
                $(".datatable-table").DataTable({
                    order: [[0, "desc"]],
                    responsive: true,
                    lengthChange: true,
                    autoWidth: false
                });
            });
        },

        fetchWeekWiseData(week_no) {
            //axios.get(`/api/week-wise-data/${week_no}`)
            axios
                .get(`${APP_URL}search-week-weekly-state/${week_no}`)
                .then(response => {
                    if (response.data.status === "success") {
                        console.log("Backend data:", response.data); // Log the entire response to check the structure

                        this.projects = response.data.projects;
                        this.personnels = response.data.personnels;
                        this.comments = response.data.comments;
                        this.translations = response.data.translations;
                        this.years = response.data.years;
                        this.week_no = this.year_week_no;
                    }
                });
        },
        getPersonnelName(personnelId) {
            const personnel = this.personnels.find(p => p.id === personnelId);
            return personnel
                ? `${personnel.first_name} ${personnel.last_name}`
                : "Unknown";
        },
        // updateProjectWisePersonnelUpdateLogs() {
        //           //  this.projectWisePersonnelUpdateLogs = this.projectWisePersonnelUpdateLogs // Trigger re-computation
        //           this.$nextTick(() => {
        //         this.$forceUpdate();  // Trigger re-computation of computed properties
        // });
        // },
        submitForm() {
            this.year_week_no = `${this.year}${this.week}`;
            this.fetchWeekWiseData(this.year_week_no);
            console.log(this.year_week_no);
        },
        downloadPdf(week_state_id, project_id, directing) {
            axios
                .get(
                    `${APP_URL}week-state-pdf/${week_state_id}/project/${project_id}/directing/${directing}`,
                    {
                        responseType: "blob" // Tell axios to expect a blob response
                    }
                )

                .then(response => {
                    const blob = new Blob([response.data], {
                        type: "application/pdf"
                    });
                    const url = window.URL.createObjectURL(blob);

                    // Create a temporary link element
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute("download", "Weekstaat.pdf");

                    // Append the link to the body and click it programmatically
                    document.body.appendChild(link);
                    link.click();

                    // Cleanup: remove the link and revoke the URL
                    link.remove();
                    window.URL.revokeObjectURL(url);
                })
                .catch(error => {
                    console.error("Error downloading PDF:", error);
                });
        },
        addPersonnel() {
            this.personnel_update_logs.push(
                Object.assign({}, this.personnel_update_log)
            );
        },
        setWeekCardData(week_states) {
            const weekly_states = week_states.filter(
                week_state => week_state.week_no == this.week_no
            );

            for (let i = 0; i < weekly_states.length; i++) {
                this.week_cards = weekly_states[i].week_cards;
                console.log("WEEK CARDS", this.week_cards);
                for (let i = 0; i < this.week_cards.length; i++) {
                    this.personnel_update_logs.push(
                        Object.assign({}, this.personnel_update_log)
                    );
                    const personnel_update_log = this.personnel_update_logs[i];
                    personnel_update_log.personnel = this.week_cards[
                        i
                    ].personnel_id;
                    personnel_update_log.directing = this.week_cards[
                        i
                    ].directing;
                    personnel_update_log.hours_1 = this.week_cards[i].hours_1;
                    personnel_update_log.hours_2 = this.week_cards[i].hours_2;
                    personnel_update_log.hours_3 = this.week_cards[i].hours_3;
                    personnel_update_log.hours_4 = this.week_cards[i].hours_4;
                    personnel_update_log.hours_5 = this.week_cards[i].hours_5;
                    personnel_update_log.hours_6 = this.week_cards[i].hours_6;
                    personnel_update_log.hours_7 = this.week_cards[i].hours_7;
                    personnel_update_log.total_hours = this.week_cards[
                        i
                    ].total_hours;
                    personnel_update_log.rate = this.week_cards[i].customer;
                    personnel_update_log.cost = this.week_cards[i].cost;
                    personnel_update_log.directing = this.week_cards[
                        i
                    ].directing;
                    personnel_update_log.wage = this.week_cards[i].comments;
                    personnel_update_log.week_card_id = this.week_cards[i].id;
                }
            }
        },

        deletePersonnel(personnel_update_log) {
            if (this.personnel_update_logs.length >= 1) {
                console.log("PERSONNEL", personnel_update_log);
                const removedDocId = personnel_update_log.week_card_id;
                this.personnel_update_logs = this.personnel_update_logs.filter(
                    log => log !== personnel_update_log
                );
                this.removedPersonnelIds.push(removedDocId);
            }
        },
        updateTotalHours() {
            for (let i = 0; i < this.personnel_update_logs.length; i++) {
                const personnel_update_log = this.personnel_update_logs[i];

                personnel_update_log.total_hours =
                    Number(personnel_update_log.hours_1) +
                    Number(personnel_update_log.hours_2) +
                    Number(personnel_update_log.hours_3) +
                    Number(personnel_update_log.hours_4) +
                    Number(personnel_update_log.hours_5) +
                    Number(personnel_update_log.hours_6) +
                    Number(personnel_update_log.hours_7);
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

h4 {
    margin: 15px;
}
</style>

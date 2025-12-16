<template>
    <div>
        <h4>{{ translations.edit_week_state }}</h4>
        <div class="row">
            <div class="col">
                <button
                    class="btn btn-success btn-sm ml-2"
                    @click.prevent="downloadPdf(0)"
                >
                    PDF
                </button>
            </div>
            <div class="col mr-2" style="text-align:end">
                <a
                    :href="base_url + 'project_plannings'"
                    class="btn btn-success btn-sm"
                >
                    Project Plannings</a
                >
                <button
                    class="btn btn-success btn-sm"
                    @click.prevent="downloadPdf(1)"
                >
                    PDF
                </button>
            </div>
        </div>
        <div style="margin: 10px;">
            <div class="form-row card">
                <h4>{{ translations.add_new_week_cards }}</h4>
                <table class="table table-bordered sort">
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

                            <th>{{ translations.action }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="(personnel_log, i) in personnel_logs"
                            :key="i"
                        >
                            <td style="width: 200px">
                                <select
                                    class="form-control"
                                    v-model="personnel_log.personnel"
                                >
                                    <option
                                        v-for="personnel in personnels"
                                        :value="personnel.id"
                                        >{{
                                            personnel.first_name +
                                                " " +
                                                personnel.last_name
                                        }}</option
                                    >
                                </select>
                            </td>

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    step="1"
                                    min="0"
                                    style="padding:2px; width: 40px"
                                    @change="totalHours"
                                    v-model="personnel_log.hours_1"
                                />
                            </td>

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    step="1"
                                    min="0"
                                    style="padding:2px; width: 40px"
                                    @change="totalHours"
                                    v-model="personnel_log.hours_2"
                                />
                            </td>

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    step="1"
                                    min="0"
                                    style="padding:2px; width: 40px"
                                    @change="totalHours"
                                    v-model="personnel_log.hours_3"
                                />
                            </td>

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    step="1"
                                    min="0"
                                    style="padding:2px; width: 40px"
                                    @change="totalHours"
                                    v-model="personnel_log.hours_4"
                                />
                            </td>

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    step="1"
                                    min="0"
                                    style="padding:2px; width: 40px;"
                                    @change="totalHours"
                                    v-model="personnel_log.hours_5"
                                />
                            </td>

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    step="1"
                                    min="0"
                                    style="padding:2px; width: 40px"
                                    @change="totalHours"
                                    v-model="personnel_log.hours_6"
                                />
                            </td>

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    step="1"
                                    min="0"
                                    style="padding:2px; width: 40px"
                                    @change="totalHours"
                                    v-model="personnel_log.hours_7"
                                />
                            </td>

                            <td>
                                <label>{{ personnel_log.total_hours }}</label>
                            </td>

                            <td>
                                <input
                                    type="text"
                                    id="rate"
                                    class="form-control"
                                    style="padding:2px;"
                                    v-model="personnel_log.rate"
                                />
                            </td>

                            <td>
                                <input
                                    type="number"
                                    id="rate_cost"
                                    class="form-control"
                                    style="padding:2px;"
                                    v-model="personnel_log.cost"
                                />
                            </td>

                            <td>
                                <input
                                    type="checkbox"
                                    name="Checked"
                                    v-model="personnel_log.directing"
                                />
                            </td>

                            <td>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="personnel_log.wage"
                                />
                            </td>
                            <td>
                                <a
                                    v-if="
                                        personnel_logs.length !== i + 1 ||
                                            personnel_logs.length === i + 1
                                    "
                                    href="#"
                                    :class="
                                        personnel_logs.length == 1
                                            ? `disabled`
                                            : ''
                                    "
                                    class="text-danger"
                                    @click.prevent="
                                        removePersonnel(personnel_log)
                                    "
                                >
                                    <i
                                        class="text-danger fa fa-minus-circle"
                                    ></i>
                                </a>
                                <a
                                    v-if="personnel_logs.length === i + 1"
                                    href="#"
                                    class="text-success"
                                    @click.prevent="addPersonnel"
                                >
                                    <i class="fa fa-plus-circle bg-plus"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-row card">
                <div class="header-row">
                    <h4>{{ translations.edit_week_cards }}</h4>
                    <div class="approved-status" v-if="hasApprovedCards">
                        <span class="green-dot"></span>
                        <span class="approved-text">
                            Approved<span v-if="performer">
                                by {{ performer }}</span
                            >
                        </span>
                    </div>
                </div>
                <!-- <span v-if="hasApprovedCards" class="green-dot"></span>
                <span v-if="hasApprovedCards" class="approved-text">
                    Approved <span v-if="performer">by {{ performer }}</span>
                </span>
                <h4>{{ translations.edit_week_cards }}</h4> -->
                <table class="table table-bordered sort">
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
                            <th>{{ translations.action }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="(personnel_update_log,
                            i) in personnel_update_logs"
                            :key="i"
                            :class="{
                                'approved-row':
                                    personnel_update_log.time_approve == 1
                            }"
                        >
                            <td style="width: 180px">
                                <select
                                    class="form-control"
                                    v-model="personnel_update_log.personnel"
                                >
                                    <option
                                        v-for="personnel in personnels"
                                        :value="personnel.id"
                                        >{{
                                            personnel.first_name +
                                                " " +
                                                personnel.last_name
                                        }}</option
                                    >
                                </select>
                            </td>

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    step="1"
                                    min="0"
                                    style="padding:2px; width: 40px"
                                    @change="updateTotalHours"
                                    v-model="personnel_update_log.hours_1"
                                />
                            </td>

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    step="1"
                                    min="0"
                                    style="padding:2px; width: 40px"
                                    @change="updateTotalHours"
                                    v-model="personnel_update_log.hours_2"
                                />
                            </td>

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    step="1"
                                    min="0"
                                    style="padding:2px; width: 40px"
                                    @change="updateTotalHours"
                                    v-model="personnel_update_log.hours_3"
                                />
                            </td>

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    step="1"
                                    min="0"
                                    style="padding:2px; width: 40px"
                                    @change="updateTotalHours"
                                    v-model.number="
                                        personnel_update_log.hours_4
                                    "
                                />
                            </td>
                            <!-- <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    style="padding:2px;"
                                    @change="updateTotalHours"
                                    v-model="personnel_update_log.hours_4"
                                />
                            </td> -->

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    step="1"
                                    min="0"
                                    style="padding:2px; width: 40px;"
                                    @change="updateTotalHours"
                                    v-model="personnel_update_log.hours_5"
                                />
                            </td>

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    step="1"
                                    min="0"
                                    style="padding:2px; width: 40px"
                                    @change="updateTotalHours"
                                    v-model="personnel_update_log.hours_6"
                                />
                            </td>

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    step="1"
                                    min="0"
                                    style="padding:2px; width: 40px"
                                    @change="updateTotalHours"
                                    v-model="personnel_update_log.hours_7"
                                />
                            </td>

                            <td>
                                <label>{{
                                    personnel_update_log.total_hours
                                }}</label>
                            </td>

                            <td>
                                <input
                                    type="text"
                                    id="rate"
                                    class="form-control txt"
                                    style="padding:2px;"
                                    v-model="personnel_update_log.rate"
                                />
                            </td>

                            <td>
                                <input
                                    type="number"
                                    id="rate_cost"
                                    class="form-control"
                                    style="padding:2px; width: 40px"
                                    v-model="personnel_update_log.cost"
                                />
                            </td>

                            <td>
                                <input
                                    type="checkbox"
                                    name="Checked"
                                    v-model="personnel_update_log.directing"
                                />
                            </td>

                            <td>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="personnel_update_log.wage"
                                />
                            </td>
                            <td>
                                <div
                                    style="display: inline-flex; gap: 5px; align-items: center;"
                                >
                                    <button
                                        style="padding: 2px 6px; font-size: 10px; line-height: 1; border-radius: 3px;"
                                        class="btn btn-danger"
                                        @click="
                                            downloadSinglePdf(
                                                personnel_update_log.personnel
                                            )
                                        "
                                    >
                                        <i
                                            class="fa fa-file-pdf"
                                            style="font-size: 10px;"
                                        ></i>
                                    </button>

                                    <a
                                        v-if="personnel_update_logs.length"
                                        href="#"
                                        @click.prevent="
                                            deletePersonnel(
                                                personnel_update_log
                                            )
                                        "
                                        style="font-size: 10px; color: red;"
                                    >
                                        <i class="fa fa-minus-circle"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="col" style="text-align: center">
                    <h4>
                        Totaal van regie uren:
                        {{ this.directing_total_hours }} ___ Totaal van
                        aangenomen uren: {{ this.not_directing_total_hours }}
                    </h4>
                </div>
            </div>
        </div>
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

                        <div class="col-md-3" v-if="approved_allow === 1">
                            <span>{{ translations.approved }}:</span><br />
                            <input
                                style="width: 35px;"
                                class="form-control"
                                required
                                v-model="approved"
                                type="checkbox"
                            /><br />
                        </div>
                        <div class="col-md-3">
                            <span>{{ translations.worksheet }}:</span><br />
                            <input
                                style="width: 35px;"
                                class="form-control"
                                required
                                v-model="via_worksheet"
                                type="checkbox"
                            /><br />
                        </div>
                    </div>
                    <span>{{ translations.status }}:</span><br />
                    <select class="form-control" v-model="status">
                        <option :value="translations.choose_a_status">{{
                            translations.choose_a_status
                        }}</option>
                        <option :value="translations.accepted">{{
                            translations.accepted
                        }}</option>
                        <option :value="translations.approved">{{
                            translations.approved
                        }}</option>
                        <option
                            :value="translations.awaiting_counter_receipt"
                            >{{ translations.awaiting_counter_receipt }}</option
                        >
                        <option
                            :value="
                                translations.awaiting_agreement_from_brainnet_bm
                            "
                            >{{
                                translations.awaiting_agreement_from_brainnet_bm
                            }}</option
                        >
                        <option :value="translations.waiting_hours">{{
                            translations.waiting_hours
                        }}</option>
                        <option :value="translations.only_directed_invoiced">{{
                            translations.only_directed_invoiced
                        }}</option>
                        <option :value="translations.partially_invoiced">{{
                            translations.partially_invoiced
                        }}</option>
                        <option :value="translations.ready_for_invoicing">{{
                            translations.ready_for_invoicing
                        }}</option>
                        <option :value="translations.collect_invoice">{{
                            translations.collect_invoice
                        }}</option>
                        <option :value="translations.sent_invoice">{{
                            translations.sent_invoice
                        }}</option>
                        <option :value="translations.via_customer_voucher">{{
                            translations.via_customer_voucher
                        }}</option>
                        <option :value="translations.via_work_letter">{{
                            translations.via_work_letter
                        }}</option>
                        <option :value="translations.will_be_bill_separately">{{
                            translations.will_be_bill_separately
                        }}</option>
                    </select>
                    <br />
                    <span>{{ translations.project_name }}:*</span><br />
                    <!--select style="height: 100px" ref="selectElement" id="selectElement">
                        <option v-for="project in projects" :value="project.id">{{ project.name }}</option>
                    </select-->

                    <v-select
                        :options="projects"
                        label="name"
                        v-model="selectedProject"
                        :reduce="project => project.id"
                        @input="getProjectData"
                        ref="selectElement"
                    ></v-select>

                    <br />

                    <span>{{ translations.delay_date }}:</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="delay_date"
                        type="date"
                    /><br />

                    <span>{{ translations.received_date }}:</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="receive_date"
                        type="date"
                    /><br /><br />
                    <span>{{ translations.invoice_date }}:</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="invoice_date"
                        type="date"
                    /><br /><br />
                    <span>{{ translations.comments }}:</span><br />
                    <textarea
                        class="form-control"
                        required
                        v-model="comments"
                        type="text"
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
                        type="text"
                        disabled
                    /><br />

                    <span>{{ translations.notes }}:</span><br />
                    <textarea
                        class="form-control"
                        v-model="notes"
                        type="text"
                    /><br />
                    <span>{{ translations.approval }}:</span><br />
                    <textarea
                        class="form-control"
                        v-model="approval"
                        type="text"
                    /><br />
                    <span>{{ translations.internal }}:*</span><br />
                    <textarea
                        class="form-control"
                        v-model="internal_notes"
                        type="text"
                    /><br />
                </div>
            </div>
            <span>Documents:</span>
            <div
                class="row"
                v-for="(document_update_log, i) in document_update_logs"
                :key="i"
            >
                <div class="col-md-3">
                    <input
                        class="form-control"
                        v-model="document_update_log.doc_type"
                        type="text"
                        placeholder="Document types"
                    />
                </div>
                <div class="col-md-2">
                    <input
                        class="form-control"
                        v-model="document_update_log.expiry"
                        type="date"
                    />
                </div>
                <div class="col-md-3">
                    <a :href="document_update_log.path" target="_blank"
                        >@{{ document_update_log.file }}</a
                    >
                </div>
                <div class="col-md-1">
                    <a :href="document_update_log.path" download
                        ><i class="fas fa-download"></i
                    ></a>
                </div>
                <div class="col-md-3 mt-2">
                    <a
                        v-if="
                            document_update_logs.length !== i + 1 ||
                                document_update_logs.length === i + 1
                        "
                        href="#"
                        :class="
                            document_update_logs.length == 1 ? `disabled` : ''
                        "
                        class="text-danger"
                        @click.prevent="removeUpdateDoc(document_update_log)"
                    >
                        <i class="text-danger fa fa-minus-circle"></i>
                    </a>
                </div>
            </div>

            <div
                class="row mt-2"
                v-for="(document_log, i) in document_logs"
                :key="i"
            >
                <div class="col-md-3">
                    <AutoSuggestInput
                        v-model="document_log.doc_type"
                        placeholder="Document type"
                    />
                    <!-- <input
                        class="form-control"
                        v-model="document_log.doc_type"
                        type="text"
                        placeholder="Document types"
                    /> -->
                </div>
                <div class="col-md-2">
                    <input
                        class="form-control"
                        v-model="document_log.expiry"
                        type="date"
                    />
                </div>
                <div class="col-md-4">
                    <input
                        class="btn"
                        accept=".pdf,.csv, .xls, .xlsx, .docx, text/csv, application/pdf, application/csv,text/comma-separated-values, application/csv, application/excel,
                        application/vnd.msexcel, text/anytext, application/vnd. ms-excel,
                        application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                        type="file"
                        name="document"
                        @change="fileUpload"
                    />
                </div>
                <div class="col-md-3 mt-2">
                    <a
                        v-if="
                            document_logs.length !== i + 1 ||
                                document_logs.length === i + 1
                        "
                        href="#"
                        :class="document_logs.length == 1 ? `disabled` : ''"
                        class="text-danger"
                        @click.prevent="removeDocument(document_log)"
                    >
                        <i class="text-danger fa fa-minus-circle"></i>
                    </a>
                    <a
                        v-if="document_logs.length === i + 1"
                        href="#"
                        class="text-success"
                        @click.prevent="addDocument"
                    >
                        <i class="fa fa-plus-circle bg-plus"></i>
                    </a>
                </div>
            </div>
            <div class="row">
                <div style="margin: 10px; text-align: end" class="col-lg-12">
                    <a
                        :href="base_url + 'week-state'"
                        class="mt-0 btn btn-danger"
                        style="width: 100px;"
                    >
                        {{ translations.cancel }}
                    </a>

                    <input
                        style="margin-left: 10px;"
                        type="submit"
                        class="submit btn btn-success"
                        value="Update and Close"
                        @click.prevent="updateWeekState('close')"
                    />
                    <input
                        style="margin-left: 10px;"
                        type="submit"
                        class="submit btn btn-success"
                        value="Update"
                        @click.prevent="updateWeekState('update')"
                    />
                    <input
                        style="margin-right: 50px;
                           margin-left: 10px;"
                        type="submit"
                        class="submit btn btn-success"
                        value="Update and new"
                        @click.prevent="updateWeekState('update_and_new')"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Toastify from "toastify-js";
import Swal from "sweetalert2";
import axios from "axios";
import $ from "jquery";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
    components: {
        vSelect
    },
    props: [
        "personnels",
        "wages",
        "id",
        "project_id",
        "translations",
        "projects",
        "approved_allow",
        "week_cards"
    ],
    beforeMount() {
        this.personnel_logs.push(Object.assign({}, this.personnel_log));
        this.document_logs.push(Object.assign({}, this.document_log));
    },

    computed: {
        hasApprovedCards() {
            return this.personnel_update_logs.some(
                log => log.time_approve == 1
            );
        }
    },
    data() {
        return {
            message: "",
            week_no: "",
            fetched_week_cards: [],
            project: null,
            selectedProject: this.project_id,
            delay_date: "",
            directing_total_hours: 0,
            not_directing_total_hours: 0,
            receive_date: "",
            invoice_date: "",
            department: "",
            customer: "",
            notes: "",
            status: "",
            performer: "",
            approval: "",
            approved: "",
            via_worksheet: "",
            internal_notes: "",
            comments: "",
            personnel_log: {
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
                wage: "",
                time_approve: 0
            },
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
            personnel_logs: [],
            personnel_update_logs: [],
            week_cards: [],
            removedDocumentIds: [],
            removedPersonnelIds: [],
            document_update_logs: [],
            document_update_log: {
                doc_type: "",
                expiry: "",
                file: "",
                path: "",
                id: ""
            },
            document_log: {
                doc_type: "",
                expiry: "",
                file: ""
            },
            key_value: 0,
            document_logs: [],
            isUpdated: false,
            base_url: APP_URL
        };
    },
    mounted() {
        console.log("Mounted");
        axios
            .get(`${APP_URL}/staffing_projects/${this.project_id}`)
            .then(response => {
                const project = response.data;
                this.project = project.name;
                this.selectedProject = project.id;
                this.department = project.department.name;
                this.customer = project.customer.name;
                // this.performer = project.project_performer.first_name;

                this.performer =
                    project.projectPerformer &&
                    project.projectPerformer.first_name
                        ? project.projectPerformer.first_name
                        : "";
                this.notes = project.notes;
                this.approval = project.approval;
            });

        axios.get(`${APP_URL}/week-state/${this.id}`).then(response => {
            // console.log("THIS", response.data);
            const week_state = response.data;
            this.week_no = week_state.week_no;
            this.delay_date = week_state.delay_date;
            this.receive_date = week_state.receive_date;
            this.invoice_date = week_state.invoice_date;
            this.approved = week_state.approved;
            this.status = week_state.status;
            this.via_worksheet = week_state.via_worksheet;
            this.comments = week_state.comments;
            this.internal_notes = week_state.internal_notes;
            this.project = this.project_id;

            this.week_cards = response.data.week_cards;
            for (let i = 0; i < this.week_cards.length; i++) {
                this.personnel_update_logs.push(
                    Object.assign({}, this.personnel_update_log)
                );
                const personnel_update_log = this.personnel_update_logs[i];
                personnel_update_log.personnel = this.week_cards[
                    i
                ].personnel_id;
                personnel_update_log.directing = this.week_cards[i].directing;
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
                personnel_update_log.directing = this.week_cards[i].directing;
                personnel_update_log.wage = this.week_cards[i].comments;
                personnel_update_log.week_card_id = this.week_cards[i].id;
                personnel_update_log.time_approve =
                    this.week_cards[i].time_approve || 0;
                // log.performer_name =
                //     card.project_performer && card.project_performer.first_name
                //         ? card.project_performer.first_name
                //         : "";

                if (personnel_update_log.directing) {
                    this.directing_total_hours += Number(
                        personnel_update_log.total_hours
                    );
                } else {
                    this.not_directing_total_hours += Number(
                        personnel_update_log.total_hours
                    );
                }
            }

            console.log("documents:", week_state.documents);
            for (let i = 0; i < week_state.documents.length; i++) {
                this.document_update_logs.push(
                    Object.assign({}, this.document_update_log)
                );
                const document_update_log = this.document_update_logs[i];
                document_update_log.doc_type = week_state.documents[i].type;
                document_update_log.expiry =
                    week_state.documents[i].expiry_date;
                document_update_log.file = week_state.documents[i].file;
                document_update_log.path = week_state.documents[i].path;
                document_update_log.id = week_state.documents[i].id;
            }
        });

        const vm = this;
        $(this.$refs.selectElement)
            .select2({
                width: "100%",
                required: true
            })
            .on("select2:select", function(e) {
                vm.project = e.target.value;
                vm.getProjectData();
            });
    },
    watch: {
        // project(newVal) {
        //     $(this.$refs.selectElement).val(newVal).trigger('change');
        //     this.getProjectData();
        // },
        selectedProject(newVal) {
            this.getProjectData();
        }
    },
    methods: {
        downloadSinglePdf(personnelId) {
            const projectId = this.project_id; // make sure this exists
            const weekStateId = this.id; // make sure this exists

            axios
                .get(
                    `${APP_URL}/weekcard-pdf/${weekStateId}/${projectId}/${personnelId}`,
                    {
                        responseType: "blob"
                    }
                )
                .then(response => {
                    const blob = new Blob([response.data], {
                        type: "application/pdf"
                    });
                    const url = window.URL.createObjectURL(blob);
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute(
                        "download",
                        `Weekstaat_${personnelId}.pdf`
                    );
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                })
                .catch(error => {
                    console.error("PDF download failed", error);
                });
        },

        downloadPdf(directing) {
            axios
                .get(
                    `${APP_URL}week-state-pdf/${this.id}/project/${this.project_id}/directing/${directing}`,
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
        removeUpdateDoc(document_update_log) {
            if (this.document_update_logs.length >= 1) {
                // Extract doc_type before removing
                const removedDocId = document_update_log.id;

                // Filter out the document_update_log from the array
                this.document_update_logs = this.document_update_logs.filter(
                    log => log !== document_update_log
                );

                // Store the removed doc_type in the array
                this.removedDocumentIds.push(removedDocId);
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

        addDocument() {
            this.document_logs.push(Object.assign({}, this.document_log));
        },
        removeDocument(row) {
            if (this.document_logs.length > 1) {
                this.document_logs = this.document_logs.filter(
                    document_log => document_log !== row
                );
            }
        },
        fileUpload(event) {
            this.document_logs.push(Object.assign({}, this.document_log));

            const document_log = this.document_logs[this.key_value];
            document_log.file = event.target.files[0];

            console.log("FILEEEEEEEEEEEEE" + event.target);

            this.key_value = this.key_value + 1;
        }, //fileUpload
        getProjectData() {
            axios
                .get(`${APP_URL}/staffing_projects/${this.selectedProject}`)
                .then(response => {
                    const project = response.data;
                    this.department = project.department.name;
                    this.customer = project.customer.name;
                    //this.performer = project.project_performer.first_name
                    this.performer =
                        project.project_performer &&
                        project.project_performer.first_name
                            ? project.project_performer.first_name
                            : ""; // Reset performer to empty string if not available

                    this.notes = project.notes;
                    this.approval = project.approval;
                });
        },

        addPersonnel() {
            this.personnel_logs.push(Object.assign({}, this.personnel_log));
        },
        removePersonnel(row) {
            if (this.personnel_logs.length > 1) {
                this.personnel_logs = this.personnel_logs.filter(
                    personnel_log => personnel_log !== row
                );
            }
        },

        totalHours() {
            for (let i = 0; i < this.personnel_logs.length; i++) {
                const personnel_log = this.personnel_logs[i];

                personnel_log.total_hours =
                    Number(personnel_log.hours_1) +
                    Number(personnel_log.hours_2) +
                    Number(personnel_log.hours_3) +
                    Number(personnel_log.hours_4) +
                    Number(personnel_log.hours_5) +
                    Number(personnel_log.hours_6) +
                    Number(personnel_log.hours_7);
            }
        },
        updateTotalHours() {
            this.directing_total_hours = 0;
            this.not_directing_total_hours = 0;

            for (let i = 0; i < this.personnel_update_logs.length; i++) {
                const personnel_update_log = this.personnel_update_logs[i];

                personnel_update_log.total_hours = [
                    personnel_update_log.hours_1,
                    personnel_update_log.hours_2,
                    personnel_update_log.hours_3,
                    personnel_update_log.hours_4,
                    personnel_update_log.hours_5,
                    personnel_update_log.hours_6,
                    personnel_update_log.hours_7
                ].reduce((sum, hours) => sum + Number(hours), 0);

                if (personnel_update_log.directing) {
                    this.directing_total_hours +=
                        personnel_update_log.total_hours;
                } else {
                    this.not_directing_total_hours +=
                        personnel_update_log.total_hours;
                }
            }
        },

        updateWeekState(button) {
            console.log(this.request);
            if (!this.selectedProject) {
                Swal.fire({
                    icon: "warning",
                    title: "Project not selected",
                    text: "Please select a project before submitting."
                });
                return;
            }

            if (
                this.isUpdated &&
                (button === "close" || button === "update_and_new")
            ) {
                this.redirectAfterUpdate(button);
                return;
            }

            let formData = new FormData();
            formData.append("project", this.selectedProject);
            formData.append("week_no", this.week_no || "");
            formData.append("delay_date", this.delay_date || "");
            formData.append("receive_date", this.receive_date || "");
            formData.append("invoice_date", this.invoice_date || "");
            formData.append("approved", this.approved ? 1 : 0);
            formData.append("via_worksheet", this.via_worksheet ? 1 : 0);
            formData.append("notes", this.notes || "");
            formData.append("approval", this.approval || "");
            formData.append("status", this.status || "");
            formData.append("comments", this.comments || "");
            formData.append("internal_notes", this.internal_notes || "");
            formData.append("old_project_id", this.project_id);

            this.removedDocumentIds.forEach(id => {
                formData.append("removedDocumentIds[]", id);
            });
            this.removedPersonnelIds.forEach(id => {
                formData.append("removedPersonnelIds[]", id);
            });
            this.document_logs.forEach((log, index) => {
                formData.append(
                    `document_logs[${index}][doc_type]`,
                    log.doc_type
                );
                formData.append(`document_logs[${index}][expiry]`, log.expiry);
                formData.append(`document_logs[${index}][file]`, log.file);
            });

            this.personnel_logs.forEach((log, index) => {
                formData.append(
                    `personnel_logs[${index}][personnel]`,
                    log.personnel || ""
                );
                formData.append(
                    `personnel_logs[${index}][hours_1]`,
                    log.hours_1 || 0
                );
                formData.append(
                    `personnel_logs[${index}][hours_2]`,
                    log.hours_2 || 0
                );
                formData.append(
                    `personnel_logs[${index}][hours_3]`,
                    log.hours_3 || 0
                );
                formData.append(
                    `personnel_logs[${index}][hours_4]`,
                    log.hours_4 || 0
                );
                formData.append(
                    `personnel_logs[${index}][hours_5]`,
                    log.hours_5 || 0
                );
                formData.append(
                    `personnel_logs[${index}][hours_6]`,
                    log.hours_6 || 0
                );
                formData.append(
                    `personnel_logs[${index}][hours_7]`,
                    log.hours_7 || 0
                );
                formData.append(
                    `personnel_logs[${index}][total_hours]`,
                    log.total_hours || 0
                );
                formData.append(
                    `personnel_logs[${index}][rate]`,
                    log.rate || 0
                );
                formData.append(
                    `personnel_logs[${index}][cost]`,
                    log.cost || 0
                );
                formData.append(
                    `personnel_logs[${index}][directing]`,
                    log.directing ? 1 : 0
                );
                formData.append(
                    `personnel_logs[${index}][wage]`,
                    log.wage || ""
                );
            });

            this.personnel_update_logs.forEach((log, index) => {
                formData.append(
                    `personnel_update_logs[${index}][week_card_id]`,
                    log.week_card_id
                );
                formData.append(
                    `personnel_update_logs[${index}][personnel]`,
                    log.personnel
                );
                formData.append(
                    `personnel_update_logs[${index}][hours_1]`,
                    log.hours_1 || 0
                );
                formData.append(
                    `personnel_update_logs[${index}][hours_2]`,
                    log.hours_2 || 0
                );
                formData.append(
                    `personnel_update_logs[${index}][hours_3]`,
                    log.hours_3 || 0
                );
                formData.append(
                    `personnel_update_logs[${index}][hours_4]`,
                    log.hours_4 || 0
                );
                formData.append(
                    `personnel_update_logs[${index}][hours_5]`,
                    log.hours_5 || 0
                );
                formData.append(
                    `personnel_update_logs[${index}][hours_6]`,
                    log.hours_6 || 0
                );
                formData.append(
                    `personnel_update_logs[${index}][hours_7]`,
                    log.hours_7 || 0
                );
                formData.append(
                    `personnel_update_logs[${index}][total_hours]`,
                    log.total_hours || 0
                );
                formData.append(
                    `personnel_update_logs[${index}][rate]`,
                    log.rate || 0
                );
                formData.append(
                    `personnel_update_logs[${index}][cost]`,
                    log.cost || 0
                );
                formData.append(
                    `personnel_update_logs[${index}][directing]`,
                    log.directing ? 1 : 0
                );
                formData.append(
                    `personnel_update_logs[${index}][wage]`,
                    log.wage || ""
                );
                formData.append(
                    `personnel_update_logs[${index}][time_approve]`,
                    log.time_approve || 0
                );
            });

            axios
                .post(
                    APP_URL + "week-state/" + this.id + "?_method=PUT",
                    formData,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data"
                        }
                    }
                )
                .then(response => {
                    console.log("REQUEST", response.data.id);

                    if (response.data.status) {
                        Swal.fire("Success!", response.data.message, "success");
                        this.isUpdated = true;
                    } else {
                        Swal.fire("Error!", response.data.message, "error");
                    }
                    if (button !== "update") {
                        this.redirectAfterUpdate(button);
                    }
                    if (button === "close") {
                        let url = this.base_url + "week-state";
                        setTimeout(function() {
                            window.location.href = url;
                        }, 1500);
                    } else if (button === "update_and_new") {
                        let url = this.base_url + "week-state/create";
                        setTimeout(function() {
                            window.location.href = url;
                        }, 1500);
                    }
                });
        },

        redirectAfterUpdate(button) {
            if (button === "close") {
                let url = this.base_url + "week-state";
                setTimeout(() => {
                    window.location.href = url;
                }, 1500);
            } else if (button === "update_and_new") {
                let url = this.base_url + "week-state/create";
                setTimeout(() => {
                    window.location.href = url;
                }, 1500);
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
    margin: 10px;
}

.approved-row {
    border: 2px solid #28a745 !important;
    background-color: #eafbea;
}
.green-dot {
    height: 10px;
    width: 10px;
    background-color: #28a745;
    border-radius: 50%;
    display: inline-block;
    align-items: right;
    margin-right: 5px;
}

.header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 15px;
}

.approved-status {
    display: flex;
    align-items: center;
}

.green-dot {
    height: 10px;
    width: 10px;
    background-color: #28a745;
    border-radius: 50%;
    display: inline-block;
    margin-right: 5px;
}

.approved-text {
    color: #28a745;
    font-weight: 600;
    font-size: 16px;
}
</style>

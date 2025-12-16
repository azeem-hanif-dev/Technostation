<template>
    <div>
        <h4>Worked Hours</h4>
        <div class="mr-2" style="text-align: end">
            <button
                class="btn btn-success btn-sm"
                @click.prevent="downloadPdf()"
            >
                PDF
            </button>
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

                            <th>{{ translations.cost_per_hour }}</th>

                            <th style="width: 10px;">
                                {{ translations.directing }}
                            </th>

                            <th>{{ translations.rate_per_hour }}</th>

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
                                    style="padding:2px; width: 40px;"
                                    @change="totalHours"
                                    v-model="personnel_log.hours_1"
                                />
                            </td>

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    style="padding:2px; width: 40px;"
                                    @change="totalHours"
                                    v-model="personnel_log.hours_2"
                                />
                            </td>

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    style="padding:2px; width: 40px;"
                                    @change="totalHours"
                                    v-model="personnel_log.hours_3"
                                />
                            </td>

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    style="padding:2px; width: 40px;"
                                    @change="totalHours"
                                    v-model="personnel_log.hours_4"
                                />
                            </td>

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    style="padding:2px; width: 40px;"
                                    @change="totalHours"
                                    v-model="personnel_log.hours_5"
                                />
                            </td>

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    style="padding:2px; width: 40px;"
                                    @change="totalHours"
                                    v-model="personnel_log.hours_6"
                                />
                            </td>

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    style="padding:2px; width: 40px;"
                                    @change="totalHours"
                                    v-model="personnel_log.hours_7"
                                />
                            </td>

                            <td>
                                <label ref="sum">{{
                                    personnel_log.total_hours
                                }}</label>
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
                                    id="personnel_cost_per_hour"
                                    class="form-control"
                                    style="padding:2px;"
                                    v-model="
                                        personnel_log.personnel_cost_per_hour
                                    "
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
                                    name="personnel_cost_per_hour"
                                    style="padding:2px;width:40px;"
                                    v-model="
                                        personnel_log.personnel_rate_per_hour
                                    "
                                />
                                <!-- <select class="form-control" v-model="personnel_log.wage">
                            <option v-for="wage in wages" :value="wage.comment">{{ wage.comment }}</option>
                        </select> -->
                                <input
                                    type="text"
                                    class="form-control"
                                    style="padding:2px;display:none; margin-top:5px;"
                                    name="note_other"
                                    id="note_other"
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
                <h4>{{ translations.edit_week_cards }}</h4>
                <table class="table table-bordered sort">
                    <thead>
                        <tr>
                            <th>{{ translations.staff_id }}</th>
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

                            <th>{{ translations.cost_per_hour }}</th>

                            <th style="width: 10px;">
                                {{ translations.directing }}
                            </th>

                            <th>{{ translations.rate_per_hour }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="(personnel_update_log,
                            i) in personnel_update_logs"
                            :key="i"
                        >
                            <td>{{ personnel_update_log.personnel }}</td>
                            <td style="width: 200px">
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
                                    style="padding:2px; width: 40px;"
                                    @change="updateTotalHours"
                                    v-model="personnel_update_log.hours_1"
                                />
                            </td>

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    style="padding:2px;width: 40px;"
                                    @change="updateTotalHours"
                                    v-model="personnel_update_log.hours_2"
                                />
                            </td>

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    style="padding:2px;width: 40px;"
                                    @change="updateTotalHours"
                                    v-model="personnel_update_log.hours_3"
                                />
                            </td>

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    style="padding:2px;width: 40px;"
                                    @change="updateTotalHours"
                                    v-model="personnel_update_log.hours_4"
                                />
                            </td>

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    style="padding:2px;width: 40px;"
                                    @change="updateTotalHours"
                                    v-model="personnel_update_log.hours_5"
                                />
                            </td>

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    style="padding:2px;width: 40px;"
                                    @change="updateTotalHours"
                                    v-model="personnel_update_log.hours_6"
                                />
                            </td>

                            <td>
                                <input
                                    class="form-control txt"
                                    type="number"
                                    style="padding:2px;width: 40px;"
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
                                    class="form-control"
                                    style="padding:2px;"
                                    v-model="personnel_update_log.rate"
                                />
                            </td>

                            <td>
                                <input
                                    type="number"
                                    id="personnel_cost_per_hour"
                                    class="form-control"
                                    style="padding:2px;width:40px;"
                                    v-model="
                                        personnel_update_log.personnel_cost_per_hour
                                    "
                                    @change="updateTotalHours"
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
                                    name="personnel_rate_per_hour"
                                    class="form-control"
                                    style="padding:2px;width:40px;"
                                    v-model="
                                        personnel_update_log.personnel_rate_per_hour
                                    "
                                />
                                <!-- <select class="form-control" v-model="personnel_update_log.wage">
                            <option v-for="wage in wages" :value="wage.comment">{{ wage.comment }}</option>
                        </select> -->
                                <input
                                    type="text"
                                    class="form-control"
                                    style="padding:2px;display:none; margin-top:5px;"
                                    name="note_other"
                                    id="note_other"
                                />
                            </td>
                            <td>
                                <a
                                    v-if="
                                        personnel_update_logs.length !==
                                            i + 1 ||
                                            personnel_update_logs.length ===
                                                i + 1
                                    "
                                    href="#"
                                    :class="
                                        personnel_update_logs.length == 1
                                            ? `disabled`
                                            : ''
                                    "
                                    class="text-danger"
                                    @click.prevent="
                                        deletePersonnel(personnel_update_log)
                                    "
                                >
                                    <i
                                        class="text-danger fa fa-minus-circle"
                                    ></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="9" style="text-align: end;">
                                <b>{{ translations.total_hours }}</b>
                            </td>
                            <td>
                                <b ref="sum">{{ total_personnel_hours }}</b>
                            </td>
                            <td style="text-align: end;">
                                <b>{{ translations.total_cost }}</b>
                            </td>
                            <td style="text-align: center;">
                                <b>{{
                                    isNaN(total_personnel_costs)
                                        ? "N/A"
                                        : total_personnel_costs
                                }}</b>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div style="margin: 10px;">
            <h3>WEEKLY STATEMENT</h3>
            <div class="row">
                <div class="col-md-6 form-group">
                    <span>Dispatch date:</span><br />
                    <input
                        class="form-control"
                        v-model="dispatch_date"
                        type="date"
                    /><br />

                    <span>Receive date:</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="receive_date"
                        type="date"
                    /><br />
                    <span>Invoice date:</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="invoice_date"
                        type="date"
                    /><br />
                    <span>Invoice number:</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="invoice_number"
                        type="text"
                    /><br />
                </div>
                <div class="col-md-6 form-group">
                    <span>Completed:</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="completed"
                        type="checkbox"
                    /><br />
                    <span>Status:</span><br />
                    <select class="form-control" v-model="status">
                        <option value="Open">Open</option>
                        <option value="Close">Close</option>
                    </select>
                    <br />
                    <span>Internal Notes:</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="notes"
                        type="text"
                    /><br />
                    <span>{{ translations.comments }}:</span><br />
                    <textarea
                        class="form-control"
                        required
                        v-model="comments"
                        type="text"
                    /><br />
                </div>
            </div>
            <span>Documents:</span>
            <div
                class="row"
                v-for="(document_update_log, i) in uniqueDocumentLogs"
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

                <div class="col-md-4">
                    <span v-if="document_update_log.file">
                        {{ document_update_log.file }}
                    </span>
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
                    <span v-if="document_log.file">
                        {{ document_log.file }}
                    </span>
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
                        :href="base_url + 'employment-agency-overview'"
                        class="mt-0 btn btn-danger"
                        style="width: 100px;"
                    >
                        {{ translations.cancel }}
                    </a>

                    <input
                        style="margin-right: 50px;
                           width: 130px;
                           margin-left: 10px;"
                        type="submit"
                        class="submit btn btn-primary"
                        value="Create/Update"
                        @click.prevent="createWeekStateOverView"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Swal from "sweetalert2";
import axios from "axios";

export default {
    props: [
        "personnels",
        "wages",
        "week_state_week_cards",
        "agency_id",
        "week_state_ids",
        "project_id",
        "translations",
        "projects",
        "current_week_no",
        "week_state_over_view"
    ],
    beforeMount() {
        this.personnel_logs.push(Object.assign({}, this.personnel_log));
        this.document_logs.push(Object.assign({}, this.document_log));
    },
    data() {
        return {
            message: "",
            employ_agency_id: this.agency_id,
            dispatch_date: "",
            receive_date: "",
            invoice_date: "",
            invoice_number: "",
            status: "",
            completed: 0,
            notes: "",
            comments: "",
            week_no: this.current_week_no,
            personnel_log: {
                week_no: this.current_week_no,
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
                personnel_cost_per_hour: "",
                personnel_rate_per_hour: ""
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
                wage: "",
                personnel_cost_per_hour: "",
                personnel_rate_per_hour: ""
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
                id: ""
            },
            document_log: {
                doc_type: "",
                expiry: "",
                file: ""
            },
            key_value: 0,
            document_logs: [],
            base_url: APP_URL,
            total_personnel_hours: 0,
            total_personnel_costs: 0
        };
    },
    // mounted() {
    //     console.log("Mounted");

    //     this.setWeekCardsData();

    //     if (this.week_state_over_view && this.week_state_over_view.documents) {
    //         for (
    //             let i = 0;
    //             i < this.week_state_over_view.documents.length;
    //             i++
    //         ) {
    //             this.document_update_logs.push(
    //                 Object.assign({}, this.document_update_log)
    //             );
    //             const document_update_log = this.document_update_logs[i];
    //             const doc = this.week_state_over_view.documents[i];

    //             document_update_log.doc_type = doc.type;
    //             document_update_log.expiry = doc.expiry_date;
    //             document_update_log.file = doc.file;
    //             document_update_log.id = doc.id;
    //         }
    //     } else {
    //         console.warn("No documents found in week_state_over_view");
    //     }

    //     this.setWeekOverViewData();

    //     console.log("UPDATE_", this.personnel_update_logs);
    // },

    mounted() {
        console.log("Mounted");

        this.setWeekCardsData();
        console.log("aaaaaaaaaaaaa", this.document_update_logs);

        for (let i = 0; i < this.week_state_over_view.documents.length; i++) {
            this.document_update_logs.push(
                Object.assign({}, this.document_update_log)
            );
            const document_update_log = this.document_update_logs[i];
            document_update_log.doc_type = this.week_state_over_view.documents[
                i
            ].type;
            document_update_log.expiry = this.week_state_over_view.documents[
                i
            ].expiry_date;
            document_update_log.file = this.week_state_over_view.documents[
                i
            ].file;
            document_update_log.id = this.week_state_over_view.documents[i].id;
        }

        this.setWeekOverViewData();

        console.log("UPDATE_", this.personnel_update_logs);
    },

    computed: {
        uniqueDocumentLogs() {
            const seen = new Set();
            return this.document_update_logs.filter(log => {
                if (seen.has(log.doc_type)) {
                    return false;
                }
                seen.add(log.doc_type);
                return true;
            });
        }
    },

    methods: {
        setWeekCardsData() {
            for (let i = 0; i < this.week_state_week_cards.length; i++) {
                this.personnel_update_logs.push(
                    Object.assign({}, this.personnel_update_log)
                );
                const personnel_update_log = this.personnel_update_logs[i];
                personnel_update_log.personnel = this.week_state_week_cards[
                    i
                ].personnel_id;
                personnel_update_log.directing = this.week_state_week_cards[
                    i
                ].directing;
                personnel_update_log.hours_1 = this.week_state_week_cards[
                    i
                ].hours_1;
                personnel_update_log.hours_2 = this.week_state_week_cards[
                    i
                ].hours_2;
                personnel_update_log.hours_3 = this.week_state_week_cards[
                    i
                ].hours_3;
                personnel_update_log.hours_4 = this.week_state_week_cards[
                    i
                ].hours_4;
                personnel_update_log.hours_5 = this.week_state_week_cards[
                    i
                ].hours_5;
                personnel_update_log.hours_6 = this.week_state_week_cards[
                    i
                ].hours_6;
                personnel_update_log.hours_7 = this.week_state_week_cards[
                    i
                ].hours_7;
                personnel_update_log.total_hours = this.week_state_week_cards[
                    i
                ].total_hours;
                personnel_update_log.rate = this.week_state_week_cards[
                    i
                ].customer;
                personnel_update_log.cost = this.week_state_week_cards[i].cost;
                personnel_update_log.directing = this.week_state_week_cards[
                    i
                ].directing;
                personnel_update_log.wage = this.week_state_week_cards[
                    i
                ].comments;
                personnel_update_log.week_card_id = this.week_state_week_cards[
                    i
                ].id;
                personnel_update_log.personnel_cost_per_hour = this.personnels.find(
                    personnel =>
                        personnel.id ===
                        this.week_state_week_cards[i].personnel_id
                ).cost_per_hour;
                personnel_update_log.personnel_rate_per_hour = this.personnels.find(
                    personnel =>
                        personnel.id ===
                        this.week_state_week_cards[i].personnel_id
                ).rate_per_hour;
                this.total_personnel_hours += Number(
                    this.week_state_week_cards[i].total_hours
                );
                this.total_personnel_costs +=
                    Number(this.week_state_week_cards[i].total_hours) *
                    Number(personnel_update_log.personnel_cost_per_hour);
                if (Number(personnel_update_log.personnel_cost_per_hour) == 0) {
                    this.total_personnel_costs = NaN;
                }
            }
        },
        setWeekOverViewData() {
            console.log("week_state_over_view:", this.week_state_over_view);
            if (this.week_state_over_view !== null) {
                console.log("✅ Data found in week_state_over_view");
                this.dispatch_date =
                    this.week_state_over_view.dispatch_date || "";
                this.receive_date =
                    this.week_state_over_view.receive_date || "";
                this.invoice_date =
                    this.week_state_over_view.invoice_date || "";
                this.invoice_number =
                    this.week_state_over_view.invoice_number || "";
                this.status = this.week_state_over_view.status || "";
                this.completed = this.week_state_over_view.completed ? 1 : 0;
                this.notes = this.week_state_over_view.notes || "";
                this.comments = this.week_state_over_view.comments || "";
            }
        },
        downloadPdf() {
            axios
                .get(`${APP_URL}week-state-pdf`, {
                    params: {
                        week_state_ids: JSON.stringify(this.week_state_ids),
                        week_no: this.week_no,
                        agency_id: this.agency_id
                    },
                    responseType: "blob"
                })
                .then(response => {
                    const blob = new Blob([response.data], {
                        type: "application/pdf"
                    });
                    const url = window.URL.createObjectURL(blob);

                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute("download", "employment_agency.pdf");

                    document.body.appendChild(link);
                    link.click();

                    link.remove();
                    window.URL.revokeObjectURL(url);
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
                const removedPersonnelId = personnel_update_log.week_card_id;

                this.personnel_update_logs = this.personnel_update_logs.filter(
                    log => log !== personnel_update_log
                );

                this.removedPersonnelIds.push(removedPersonnelId);
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
                .get(`${APP_URL}/staffing_projects/${this.project}`)
                .then(response => {
                    const project = response.data;
                    this.department = project.department.name;
                    this.customer = project.customer.name;
                    this.performer = project.performer;
                    this.dates = project.dates;
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
            let total_hrs = 0;
            let total_costs = 0;
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
                total_hrs += Number(personnel_log.total_hours);
                if (Number(personnel_log.personnel_cost_per_hour == 0)) {
                    total_costs = NaN;
                }
                total_costs +=
                    Number(personnel_log.total_costs) *
                    Number(personnel_update_log.personnel_cost_per_hour);
            }
            this.total_personnel_hours = Number(total_hrs);
            this.total_personnel_costs = Number(total_costs).toFixed(2);
        },
        updateTotalHours() {
            let total_hrs = 0;
            let total_costs = 0;

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

                total_hrs += Number(personnel_update_log.total_hours);
                //console.log('totalcosts',Number(personnel_update_log.total_hours),'cost per hr', Number(personnel_update_log.personnel_cost_per_hour))
                if (Number(personnel_update_log.personnel_cost_per_hour) == 0) {
                    total_costs = NaN;
                }
                total_costs +=
                    Number(personnel_update_log.total_hours) *
                    Number(personnel_update_log.personnel_cost_per_hour);
                //console.log('totalcosts', total_costs);
            }
            this.total_personnel_hours = Number(total_hrs);
            this.total_personnel_costs = Number(total_costs).toFixed(2);
        },
        createWeekStateOverView() {
            console.log(this.request);

            let formData = new FormData();
            formData.append("week_no", this.week_no);
            formData.append("dispatch_date", this.dispatch_date);
            formData.append("receive_date", this.receive_date);
            formData.append("invoice_date", this.invoice_date);
            formData.append("invoice_number", this.invoice_number);
            formData.append("status", this.status);
            formData.append("completed", this.completed ? 1 : 0);
            formData.append("comments", this.comments);
            formData.append("notes", this.notes);
            formData.append("employ_agency_id", this.employ_agency_id);
            formData.append(
                "old_total_personnel_hours",
                this.total_personnel_hours
            );
            formData.append("new_personnel_hours", this.$refs.sum.innerText);

            this.removedDocumentIds.forEach(id => {
                formData.append("removedDocumentIds[]", id);
            });
            this.week_state_ids.forEach(id => {
                formData.append("week_state_ids[]", id);
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
                    `personnel_logs[${index}][week_no]`,
                    log.week_no
                );
                formData.append(
                    `personnel_logs[${index}][personnel]`,
                    log.personnel
                );
                formData.append(
                    `personnel_logs[${index}][hours_1]`,
                    log.hours_1
                );
                formData.append(
                    `personnel_logs[${index}][hours_2]`,
                    log.hours_2
                );
                formData.append(
                    `personnel_logs[${index}][hours_3]`,
                    log.hours_3
                );
                formData.append(
                    `personnel_logs[${index}][hours_4]`,
                    log.hours_4
                );
                formData.append(
                    `personnel_logs[${index}][hours_5]`,
                    log.hours_5
                );
                formData.append(
                    `personnel_logs[${index}][hours_6]`,
                    log.hours_6
                );
                formData.append(
                    `personnel_logs[${index}][hours_7]`,
                    log.hours_7
                );
                formData.append(
                    `personnel_logs[${index}][total_hours]`,
                    log.total_hours
                );
                formData.append(`personnel_logs[${index}][rate]`, log.rate);
                formData.append(`personnel_logs[${index}][cost]`, log.cost);
                formData.append(
                    `personnel_logs[${index}][directing]`,
                    log.directing ? 1 : 0
                );
                formData.append(`personnel_logs[${index}][wage]`, log.wage);
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
                    log.hours_1
                );
                formData.append(
                    `personnel_update_logs[${index}][hours_2]`,
                    log.hours_2
                );
                formData.append(
                    `personnel_update_logs[${index}][hours_3]`,
                    log.hours_3
                );
                formData.append(
                    `personnel_update_logs[${index}][hours_4]`,
                    log.hours_4
                );
                formData.append(
                    `personnel_update_logs[${index}][hours_5]`,
                    log.hours_5
                );
                formData.append(
                    `personnel_update_logs[${index}][hours_6]`,
                    log.hours_6
                );
                formData.append(
                    `personnel_update_logs[${index}][hours_7]`,
                    log.hours_7
                );
                formData.append(
                    `personnel_update_logs[${index}][total_hours]`,
                    log.total_hours
                );
                formData.append(
                    `personnel_update_logs[${index}][rate]`,
                    log.rate
                );
                formData.append(
                    `personnel_update_logs[${index}][cost]`,
                    log.cost
                );
                formData.append(
                    `personnel_update_logs[${index}][directing]`,
                    log.directing ? 1 : 0
                );
                formData.append(
                    `personnel_update_logs[${index}][wage]`,
                    log.wage
                );
            });

            axios
                .post(APP_URL + "employment-agency-overview", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    }
                })
                .then(response => {
                    Swal.fire("Good job!", response.data.message, "success");
                });
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

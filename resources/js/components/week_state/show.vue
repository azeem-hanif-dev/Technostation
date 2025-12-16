<template>
    <div class="row">
        <div class="card col-md-11">
            <div class="card-header row">
                <div class="col-md-6">
                    <h4>Weekly State</h4>
                </div>
                <div class="col-md-6">
                    <a :href="base_url+'week-state'"
                       class="btn btn-success btn-xs float-right"
                    > {{ translations.back }} </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
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

                            <th style="width: 10px;">{{ translations.directing }}</th>

                            <th>{{ translations.comments }}</th>
                        </tr>

                        </thead>

                        <tbody>
                        <tr v-for="(personnel_update_log, i) in personnel_update_logs" :key="i">
                            <td><p>
                                {{personnel_update_log.personnel}}
                            </p>
                            </td>
                            <td><p>
                                {{personnel_update_log.hours_1}}
                            </p>
                            </td>

                            <td><p>
                                {{personnel_update_log.hours_2}}
                            </p>
                            </td>

                            <td><p>
                                {{personnel_update_log.hours_3}}
                            </p>
                            </td>

                            <td><p>
                                {{personnel_update_log.hours_4}}
                            </p>
                            </td>

                            <td><p>
                                {{personnel_update_log.hours_5}}
                            </p>
                            </td>

                            <td><p>
                                {{personnel_update_log.hours_6}}
                            </p>
                            </td>

                            <td><p>
                                {{personnel_update_log.hours_7}}
                            </p>
                            </td>

                            <td>
                                <p>{{ personnel_update_log.total_hours }}</p>
                            </td>

                            <td><p>{{personnel_update_log.rate}}</p></td>

                            <td><p>{{personnel_update_log.cost}}</p></td>
                            <td>
                                <p v-if="personnel_update_log.directing == 0">No</p>
                                <p v-if="personnel_update_log.directing == 1">Yes</p>
                            </td>
                            <td>
                                <p>{{personnel_update_log.wage}}</p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>{{ translations.week_no }}:</strong> {{week_no}}</p>
                        <p v-if="approved == 0"><strong>{{ translations.approved }}:</strong>No</p>
                        <p v-if="approved == 1"><strong>{{ translations.approved }}:</strong>Yes</p>
                        <p v-if="via_worksheet == 0"><strong>{{ translations.worksheet }}:</strong>No</p>
                        <p v-if="via_worksheet == 1"><strong>{{ translations.worksheet }}:</strong>Yes</p>
                        <p><strong>{{ translations.delay_date }}:</strong> {{delay_date}}</p>
                        <p><strong>{{ translations.week_no }}:</strong> {{week_no}}</p>
                        <p><strong>{{ translations.internal  }}:</strong> {{internal_notes}}</p>
                        <p><strong>{{ translations.dates  }}:</strong> {{dates}}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>{{ translations.received_date }}:</strong> {{receive_date}}</p>
                        <p><strong>{{ translations.invoice_date }}:</strong> {{invoice_date}}</p>
                        <p><strong>{{ translations.comments }}:</strong> {{comments}}</p>
                        <p><strong>{{ translations.customer }}:</strong> {{customer}}</p>
                        <p><strong>{{ translations.department  }}:</strong> {{department}}</p>
                        <p><strong>Performer:</strong> {{performer}}</p>
                        <p v-if="approval == 0"><strong>{{ translations.approval }}:</strong>No</p>
                        <p v-if="approval == 1"><strong>{{ translations.approval }}:</strong>Yes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";
export default {
    props: ['personnels','id','project_id','translations'],
    data() {
        return {
            week_no: "",
            project: "",
            delay_date: "",
            receive_date: "",
            invoice_date: "",
            department: "",
            customer: "",
            dates: "",
            performer: "",
            approval: "",
            approved: "",
            via_worksheet: "",
            internal_notes: "",
            comments: "",
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
            },
            personnel_update_logs: [],
            week_cards: [],
            base_url: APP_URL,
        };
    },
    mounted() {
        axios.get(`${APP_URL}/staffing_projects/${this.project_id}`)
            .then(response => {
                const project = response.data
                this.project = project.name
                this.department = project.department.name
                this.customer = project.customer.name
                this.performer = project.performer
                this.dates = project.dates
                this.approval = project.approval
            });

        axios.get(`${APP_URL}/week-state/${this.id}`)
            .then(response => {
                console.log('THIS',response.data)
                const week_state = response.data
                this.week_no = week_state.week_no
                this.delay_date = week_state.delay_date
                this.receive_date = week_state.receive_date
                this.invoice_date = week_state.invoice_date
                this.approved = week_state.approved
                this.via_worksheet = week_state.via_worksheet
                this.comments = week_state.comments
                this.internal_notes = week_state.internal_notes

                this.week_cards = response.data.week_cards
                for (let i = 0; i < this.week_cards.length; i++) {
                    this.personnel_update_logs  .push(Object.assign({}, this.personnel_update_log));
                    const personnel_update_log = this.personnel_update_logs[i];
                    personnel_update_log.personnel = this.week_cards[i].personnel.first_name
                    personnel_update_log.directing = this.week_cards[i].directing
                    personnel_update_log.hours_1 = this.week_cards[i].hours_1
                    personnel_update_log.hours_2 = this.week_cards[i].hours_2
                    personnel_update_log.hours_3 = this.week_cards[i].hours_3
                    personnel_update_log.hours_4 = this.week_cards[i].hours_4
                    personnel_update_log.hours_5 = this.week_cards[i].hours_5
                    personnel_update_log.hours_6 = this.week_cards[i].hours_6
                    personnel_update_log.hours_7 = this.week_cards[i].hours_7
                    personnel_update_log.total_hours = this.week_cards[i].total_hours
                    personnel_update_log.rate = this.week_cards[i].customer
                    personnel_update_log.cost = this.week_cards[i].cost
                    personnel_update_log.directing = this.week_cards[i].directing
                    personnel_update_log.wage = this.week_cards[i].comments
                    personnel_update_log.week_card_id = this.week_cards[i].id
                }
            });
    },
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

</style>

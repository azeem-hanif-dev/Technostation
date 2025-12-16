<template>
    <div class="row">
        <div class="card col-md-11">
            <div class="card-header row">
                <div class="col-md-6">
                    <h4>Request Staff</h4>
                </div>
                <div class="col-md-6">
                    <a :href="base_url+'request_personnels'"
                       class="btn btn-success btn-xs float-right"
                    > {{ common.back }} </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>{{ translations.project_name }}:</strong> {{project}}</p>
                        <p><strong>{{ translations.project_address }}:</strong> {{project_address}}</p>
                        <p><strong>{{ translations.zipcode }}:</strong> {{zipcode}}</p>
                        <p><strong>Performer:</strong> {{performer}}</p>
                        <p><strong>{{ common.mobile }}:</strong> {{r_mobile}}</p>
                        <p><strong>{{ translations.adopted_by }}:</strong> {{adopted_by}}</p>
                        <p><strong>{{ translations.starting_date_time }}:</strong> {{starting_date_time}}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>{{ translations.no_of_person }}:</strong> {{no_of_people}}</p>
                        <p><strong>{{ translations.how_many_days }}:</strong> {{how_many_days}}</p>
                        <p><strong>{{ translations.report_to }}:</strong> {{report_to}}</p>
                        <p><strong>{{ translations.activities }}:</strong> {{activity}}</p>
                        <p><strong>{{ translations.requirements }}:</strong> {{requirements}}</p>
                        <p><strong>{{ translations.project_notes }}:</strong> {{notes}}</p>
                        <p><strong>{{ common.comments  }}:</strong> {{comments}}</p>
                        <p><strong>{{ translations.application_date_time }}:</strong> {{application_date_time}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";
export default {
    props: ['request','translations','common'],
    data() {
        return {
            project: null,
            department: "",
            project_address: "",
            zipcode: "",
            performer: "",
            order_by: "",
            phone_number: "",
            mobile: "",
            r_mobile: "",
            adopted_by: "",
            application_date_time: "",
            starting_date_time: "",
            no_of_people: "",
            how_many_days: "",
            report_to: "",
            activity: null,
            requirements: "",
            notes: "",
            comments: "",
            base_url:APP_URL,
        };
    },
    mounted() {
        axios.get(`${APP_URL}/request_personnels/${this.request}`)
            .then(response => {
                const req_personnel = response.data
                console.log('req_personnel:', response.data )
                this.project = req_personnel.project.name
                if (req_personnel.project.project_performer!= null){
                this.performer = req_personnel.project.project_performer.first_name
                this.order_by = req_personnel.project.project_performer.first_name
                this.report_to = req_personnel.project.project_performer.first_name
                }
                this.project_address = req_personnel.project.address
                this.zipcode = req_personnel.project.post_code+' '+req_personnel.project.city
                this.r_mobile = req_personnel.mobile
                this.phone_number = req_personnel.phone
                this.adopted_by = req_personnel.adopted_by
                this.application_date_time = req_personnel.application_date_time
                this.starting_date_time = req_personnel.starting_date_time
                this.no_of_people = req_personnel.no_of_people
                this.how_many_days = req_personnel.days
                // this.activity = req_personnel.employee_function.name
                  this.activity = req_personnel.employee_function.map(func => func.name).join(', ');
                this.requirements = req_personnel.requirements
                this.notes = req_personnel.notes
                this.comments = req_personnel.comments
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

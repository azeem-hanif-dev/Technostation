<template>
    <div class="row">
        <div class="card col-md-11">
            <div class="card-header row">
                <div class="col-md-6">
                    <h4>Staff</h4>
                </div>
                <div class="col-md-6">
                    <a :href="base_url+'personnels'"
                       class="btn btn-success btn-xs float-right"
                    > {{ translations.back }} </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <p v-if="salutation == 1"><strong>Salutation:</strong> Mr </p>
                        <p v-if="salutation == 2"><strong>Salutation:</strong> Ms </p>
                        <p v-if="gender == 1"><strong>Gender:</strong> Male </p>
                        <p v-if="gender == 2"><strong>Gender:</strong> Female </p>
                        <p v-if="gender == 3"><strong>Gender:</strong> Other </p>
                        <p><strong>{{ translations.initials }}:</strong> {{initials}}</p>
                        <p><strong>{{ translations.first_name }}:</strong> {{first_name}}</p>
                        <p><strong>{{ translations.last_name }}:</strong> {{last_name}}</p>
                        <p><strong>{{ translations.dob }}:</strong> {{dob}}</p>
                        <p><strong>{{ translations.ssn }}:</strong> {{social_security_number}}</p>
                        <p><strong>{{ translations.date_service }}:</strong> {{date_service}}</p>
                    </div>
                    <div class="col-md-4">
                        <p v-if="id_type == 1"><strong>{{ translations.id_type }}:</strong> Passport </p>
                        <p v-if="id_type == 2"><strong>{{ translations.id_type }}:</strong> ID card </p>
                        <p v-if="id_type == 3"><strong>{{ translations.id_type }}:</strong> Anders </p>
                        <p><strong>{{ translations.id_number }}:</strong> {{id_number}}</p>
                        <p><strong>{{ translations.expiry_date }}:</strong> {{expiration_date}}</p>
                        <p><strong>{{ translations.nationality }}:</strong> {{nationality}}</p>
                        <p v-if="vca_certificate==1"><strong>{{ translations.vca }}:</strong> Yes</p>
                        <p v-if="vca_certificate==0"><strong>{{ translations.vca }}:</strong> No</p>
                        <p v-if="own_car==1"><strong>{{ translations.own_car }}:</strong> Yes</p>
                        <p v-if="own_car==0"><strong>{{ translations.own_car }}:</strong> No</p>
                        <p v-if="active==1"><strong>{{ translations.active }}:</strong> Yes</p>
                        <p v-if="active==0"><strong>{{ translations.active }}:</strong> No</p>
                        <p><strong>{{ translations.suitable_for }}:</strong> {{employee_function}}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>{{ translations.mobile }}:</strong> {{mobile}}</p>
                        <p><strong>{{ translations.mobile }}2:</strong> {{mobile2}}</p>
                        <p><strong>{{ translations.mobile }}3:</strong> {{mobile3}}</p>
                        <p><strong>{{ translations.email }}:</strong> {{email}}</p>
                        <p><strong>{{ translations.telephone }}:</strong> {{telephone}}</p>
                        <p><strong>{{ translations.address }}:</strong> {{address}}</p>
                        <p><strong>{{ translations.post_code }}:</strong> {{postcode}}</p>
                        <p><strong>{{ translations.city }}:</strong> {{city}}</p>
                    </div>

                    <div class="col-md-4">
                        <p><strong>{{ translations.emp_agency }}:</strong> {{employment_agency}}</p>
                        <p><strong>{{ translations.emp_agency_note }}:</strong> {{employment_agency_note}}</p>
                        <p><strong>{{ translations.rate }}3:</strong> {{rate_per_hour}}</p>
                        <p><strong>{{ translations.cost }}:</strong> {{cost_per_hour}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";
export default {
    props: ['personnel','translations'],
    data() {
        return {
            currentPage: 1,
            message: "",
            initials: "",
            first_name: "",
            last_name: "",
            dob: "",
            social_security_number: "",
            date_service: "",
            id_type: "",
            id_number: "",
            expiration_date: "",
            nationality: null,
            mobile: "",
            mobile2: "",
            mobile3: "",
            email: "",
            password: "",
            telephone: "",
            address: "",
            postcode: "",
            city: "",
            employment_agency: "",
            employment_agency_note: "",
            rate_per_hour: "",
            cost_per_hour: "",
            dates: "",
            vca_certificate: 0,
            own_car: 0,
            active: 0,
            function_id: "",
            gender: "",
            salutation: null,
            employee_function: "",
            base_url:APP_URL,
        };
    },
    mounted() {
        axios.get(`${APP_URL}personnels/${this.personnel}`)
            .then(response => {
                const personnel = response.data

                this.initials = personnel.initials
                this.first_name = personnel.first_name
                this.last_name = personnel.last_name
                this.dob = personnel.dob
                this.social_security_number = personnel.social_security_number
                this.date_service = personnel.date_service
                this.id_type = personnel.id_type
                this.id_number = personnel.id_number
                this.expiration_date = personnel.id_expiry
                this.nationality = personnel.nationality
                this.mobile = personnel.mobile
                this.mobile2 = personnel.mobile2
                this.mobile3 = personnel.mobile3
                this.email = personnel.email
                this.telephone = personnel.telephone
                this.address = personnel.address
                this.postcode = personnel.postcode
                this.city = personnel.city
                this.employment_agency = personnel.agency.name
                this.employment_agency_note = personnel.employment_agency_note
                this.rate_per_hour = personnel.rate_per_hour
                this.cost_per_hour = personnel.cost_per_hour
                this.dates = personnel.personnel_dates
                this.vca_certificate = personnel.vca_certificate
                this.own_car = personnel.own_car
                this.active = personnel.active
                this.employee_function = personnel.employee_function.name
                this.gender = personnel.gender
                this.salutation = personnel.salutation
            }).catch((err)=>{
            console.log('catch',err)
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

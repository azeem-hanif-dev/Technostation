<template>
    <div class="row">
        <div class="card col-md-11">
            <div class="card-header row">
                <div class="col-md-6">
                    <h4>Employment Agency</h4>
                </div>
                <div class="col-md-6">
                    <a :href="base_url+'employment-agencies'"
                       class="btn btn-success btn-xs float-right"
                    > {{ translations.back }} </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>{{ translations.name }}:</strong> {{name}}</p>
                        <p><strong>Email:</strong> {{email}}</p>
                        <p><strong>{{ translations.contact_person }}:</strong> {{contact}}</p>
                        <p><strong>{{ translations.phone }}:</strong> {{phone}}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>{{ translations.address }}:</strong> {{address}}</p>
                        <p><strong>{{ translations.city }}:</strong> {{city}}</p>
                        <p><strong>{{ translations.country }}:</strong> {{country}}</p>
                        <p><strong>Postcode:</strong> {{zipcode}}</p>
                        <p><strong>{{ translations.agency_url }}:</strong> {{agency_url}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";
export default {
    data() {
        return {
            name: "",
            email: "",
            contact: "",
            phone: "",
            address: "",
            city: "",
            country: "",
            agency_url: "",
            zipcode: "",
            base_url:APP_URL,
        };
    },
    props: ['agency','translations'],
    mounted() {
        axios.get(`${APP_URL}/employment-agencies/${this.agency}`)
            .then(response => {
                const agency = response.data
                //console.log(agency);
                this.name = agency.name
                this.email = agency.email
                this.contact = agency.contact_person
                this.phone = agency.phone
                this.address = agency.address
                this.city = agency.city
                this.country = agency.country
                this.zipcode = agency.zipcode
                this.agency_url = agency.url
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

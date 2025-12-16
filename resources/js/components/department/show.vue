<template>
    <div class="row">
        <div class="card col-md-11">
            <div class="card-header row">
                <div class="col-md-6">
                    <h4>Department</h4>
                </div>
                <div class="col-md-6">
                    <a :href="base_url+'departments'"
                       class="btn btn-success btn-xs float-right"
                    > {{ translations.back }} </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>{{ translations.customer }}:</strong> {{customer}}</p>
                        <p><strong>{{ translations.department_name }}:</strong> {{name}}</p>
                        <p><strong>{{ translations.address }}:</strong> {{address}}</p>
                        <p><strong>Post Code:</strong> {{post_code}}</p>
                        <p><strong>{{ translations.city }}:</strong> {{city}}</p>
                        <p><strong>{{ translations.mail_box }}:</strong> {{mailbox}}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>{{ translations.po_box }}:</strong> {{postal_code}}</p>
                        <p><strong>{{ translations.po_box_city }}:</strong> {{po_box_city}}</p>
                        <p><strong>{{ translations.phone }}:</strong> {{phone}}</p>
                        <p><strong>Fax:</strong> {{fax}}</p>
                        <p><strong>Email:</strong> {{email}}</p>
                        <p><strong>Notes:</strong> {{notes}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";
export default {
    props: ['department','translations'],
    data() {
        return {
            name: "",
            address: "",
            post_code: "",
            mailbox: "",
            postal_code: "",
            po_box_city: "",
            email: "",
            fax: "",
            phone: "",
            city: "",
            customer: "",
            notes: "",
            base_url:APP_URL,
        };
    },
    mounted() {
        axios.get(`${APP_URL}/departments/${this.department}`)
            .then(response => {
                const department = response.data
                this.customer = department.customer.name
                this.name = department.name
                this.email = department.email
                this.phone = department.phone
                this.address = department.address
                this.city = department.city
                this.post_code = department.postcode
                this.mailbox = department.mailbox
                this.postal_code = department.postal_code
                this.po_box_city = department.po_box_city
                this.fax = department.fax
                this.notes = department.notes
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

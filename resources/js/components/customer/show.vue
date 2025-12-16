<template>
    <div class="row">
        <div class="card col-md-11">
            <div class="card-header row">
                <div class="col-md-6">
                    <h4>Customer</h4>
                </div>
                <div class="col-md-6">
                    <a :href="base_url+'customers'"
                       class="btn btn-success btn-xs float-right"
                    > {{ translations.back }} </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>{{ translations.name }}:</strong> {{name}}</p>
                        <p><strong>{{ translations.notes }}:</strong> {{notes}}</p>
                    </div>
                    <div class="col-md-6">

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";
export default {
    props: ['customer','translations'],
    data() {
        return {
            name: "",
            notes: "",
            contact: "",
            phone: "",
            address: "",
            city: "",
            country: "",
            base_url:APP_URL,
        };
    },
    mounted() {
        axios.get(`${APP_URL}/customers/${this.customer}`)
            .then(response => {
                const customer = response.data

                this.name = customer.name
                this.notes = customer.notes
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

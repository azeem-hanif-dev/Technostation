<template>
    <div class="row">
        <div class="card col-md-11">
            <div class="card-header row">
                <div class="col-md-6">
                    <h4>Container Supplier</h4>
                </div>
                <div class="col-md-6">
                    <a :href="base_url+'container-supplier'"
                       class="btn btn-success btn-xs float-right"
                    > {{ common.back }} </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>{{ translations.company_name }}:</strong> {{company_name}}</p>
                        <p><strong>Code:</strong> {{code}}</p>
                        <p><strong>{{ common.telephone }}:</strong> {{telephone}}</p>
                        <p><strong>{{ common.mobile }}:</strong> {{mobile}}</p>
                        <p><strong>{{ common.city }}:</strong> {{city}}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>{{ common.email }}:</strong> {{email}}</p>
                        <p><strong>{{ common.address }}:</strong> {{address}}</p>
                        <p><strong>{{ common.post_code }}:</strong> {{post_code}}</p>
                        <p><strong>{{ common.notes }}:</strong> {{notes}}</p>

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
            next: this.common.next,
            update: this.common.update,
            currentPage: 1,
            message: "",
            company_name: "",
            code: "",
            email: "",
            address: "",
            post_code: "",
            notes:"",
            city: "",
            telephone: "",
            mobile: "",
            priceLists: [],
            base_url: APP_URL,
        };
    },
    props: ['container_supplier','translations','common'],
    mounted() {
        this.priceLists = this.container_supplier.priceLists
        this.company_name = this.container_supplier.company_name
        this.code = this.container_supplier.code
        this.address = this.container_supplier.address
        this.post_code = this.container_supplier.post_code
        this.notes = this.container_supplier.notes
        this.city = this.container_supplier.city
        this.email = this.container_supplier.email
        this.telephone = this.container_supplier.telephone
        this.mobile = this.container_supplier.mobile

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

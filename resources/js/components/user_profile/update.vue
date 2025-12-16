<template>
    <div>

        <h2>Profile</h2>

        <div style="margin: 5px;" class="row">
            <div class="col-md-4 form-group">
                <span>Name:</span><br>
                <input class="form-control"
                       v-model="name"
                       type="text"
                /><br>

                <span>{{ translations.email }}:</span><br>
                <input class="form-control"
                       disabled
                       v-model="email"
                /><br>

                <span>{{ translations.language }}:</span><br>
                <select class="form-control" v-model="language">
                    <option :value="en">English</option>
                    <option :value="nl">Ducth</option>
                </select>
                <br>
                <span>{{ translations.allow_leaves }}:</span><br>
                <input class="form-control"
                       disabled
                       v-model="allow_leaves"
                       type="text"
                /><br>

            </div>

            <div class="col-md-4 form-group">
                <span>{{ translations.company_name }}</span><br>
                <input class="form-control"
                       v-model="company_name"
                       disabled
                /><br>

                <span>{{ translations.agency_name }}:</span><br>
                <input class="form-control"
                       disabled
                       v-model="agency_name"
                /><br>
                <span>{{ translations.notes }}:</span><br>
                <input class="form-control"
                       disabled
                       v-model="notes"
                       type="text"
                /><br>
                <span>{{ translations.phone }}</span><br>
                <input class="form-control"
                       v-model="phone"
                       type="text"
                /><br>

            </div>

            <div class="col-md-4 form-group">
                <span>{{ translations.houseNumber }}</span><br>
                <input class="form-control"
                       v-model="houseNumber"
                /><br>

                <span>{{ translations.city }}:</span><br>
                <input class="form-control"
                       v-model="city"
                /><br>
                <span>{{ translations.postcode }}:</span><br>
                <input class="form-control"
                       v-model="postcode"
                       type="text"
                /><br>
                <span>{{ translations.address }}</span><br>
                <input class="form-control"
                       v-model="address"
                /><br>

            </div>
        </div>
        <div class="row">
            <div style="margin: 10px; text-align: end" class="col-lg-12">

                <a :href="base_url+'home'"
                   class="submit btn btn-danger"
                   value="Cancel"
                   style="width: 100px;"

                > {{ translations.cancel }} </a>

                <input style="margin-right: 50px;
                           width: 80px;
                           margin-left: 10px;"
                       type="submit"
                       class="submit btn btn-primary"
                       value="Update"
                       @click.prevent="updateContact"
                />
            </div>
        </div>

    </div>
</template>
<script>
import axios from 'axios';
import Swal from 'sweetalert2'
export default {
    props: ['user','translations'],
    data() {
        return {
            en: "en",
            nl: "nl",
            address: "",
            allow_leaves: "",
            city: "",
            agency_name: "",
            email: "",
            employment_agency_id: "",
            houseNumber: "",
            language: "",
            name: "",
            company_name: "",
            notes: "",
            phone: "",
            postcode: "",
            user_id: "",
            base_url:APP_URL,
        };
    },
    mounted(){
        this.employment_agency_id = this.user.employment_agency_id;

        axios.get(`${APP_URL}/employment-agencies/${this.employment_agency_id}`)
            .then(response => {
                const agency = response.data
                console.log(response.data)
                this.company_name = agency.company.name
                this.agency_name = agency.name
            });

        this.address = this.user.address;
        this.user_id = this.user.id;
        this.allow_leaves = this.user.allow_leaves;
        this.city = this.user.city;
        this.company_id = this.user.company_id;
        this.email = this.user.email;
        this.houseNumber = this.user.houseNumber;
        this.language = this.user.language;
        this.name = this.user.name;
        this.notes = this.user.notes;
        this.phone = this.user.phone;
        this.postcode = this.user.postcode;
    },
    methods: {
        updateContact() {
            axios.put(APP_URL + 'user-profile', {
                name: this.name,
                notes: this.notes,
                phone: this.phone,
                postcode: this.postcode,
                language: this.language,
                houseNumber: this.houseNumber,
                city: this.city,
                user_id: this.user_id,

            }).then((response) => {
                // Swal.fire(
                //     'Good job!',
                //     'Profile Updated Successfully!',
                //     'success'
                // )
                  Swal.fire(
                        "Good job!",
                        this.message = response.data.message,
                        "success"
                    );
                let url = this.base_url + 'home';
                setTimeout(function() {
                    window.location.href = url;
                }, 1500);
            });
        },
    },
};
</script>
<style>
form {
    padding: 10px;
}

input {
    padding: 4px 8px;
    margin: 4px;
    width: 500px;
}

span {
    font-size: 18px;
    margin: 4px;
    font-weight: 500;
}

h2 {
    text-align: center;
}

.submit {
    font-size: 15px;
    color: #fff;
    background: #2445ff;
    padding: 6px 12px;
    border: none;
    margin-top: 8px;
    cursor: pointer;
    border-radius: 5px;
}

</style>

<template>
    <div>
        <br>
        <h2>{{ translations.update }}</h2>
        <div style="margin: 10px;" class="row">
            <div class="col-md-6">
                <span>{{ translations.name }}:*</span><br>
                <input class="form-control"
                       required
                       v-model="name"
                       type="text"
                       placeholder="Enter name"
                /><br>
            </div>
            <div class="col-md-6">
                <span>Code:*</span><br>
                <input class="form-control"
                       required
                       v-model="code"
                       type="text"
                       placeholder="Enter code"
                /><br>
            </div>
        </div>
        <div class="row">
            <div style="margin: 10px; text-align: end" class="col-lg-12">

                <a     :href="base_url+'employee_functions'"
                       class="mt-0 btn btn-danger"
                       style="width: 100px;"

                > {{ translations.cancel }} </a>

                <input style="margin-right: 50px;
                           width: 80px;
                           margin-left: 10px;"
                       type="submit"
                       class="submit mt-0 btn btn-primary"
                       value="Update"
                       @click.prevent="updateFunction"
                />
            </div>
        </div>

    </div>
</template>
<script>
import axios from 'axios';
import Swal from 'sweetalert2'
export default {
    data() {
        return {
            name: "",
            code: "",
            base_url:APP_URL,
        };
    },
    props: ['employee_function','translations'],
    mounted(){
        console.log("Update component mounted");

        axios.get(`${APP_URL}/employee_functions/${this.employee_function}`)
            .then(response => {
                const employee_function = response.data

                this.name = employee_function.name
                this.code = employee_function.code
            });
    },
    methods: {
        updateFunction() {
            axios.put(APP_URL + 'employee_functions/'+this.employee_function, {
                name: this.name,
                code: this.code,

            }).then((response) => {
                  Swal.fire({
                        title: "Success",
                        text: response.data.message,
                        icon: "success"
                    });
                // Swal.fire(
                //     'Good job!',
                //     'Function Updated Successfully!',
                //     'success'
                // )
                let url = this.base_url + 'employee_functions';
                setTimeout(function() {
                    window.location.href = url;
                }, 1500);

                this.$emit('Customer updated', response.data);
                this.name = '';
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

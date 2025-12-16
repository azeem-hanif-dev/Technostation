<template>
    <div class="row">
        <div class="card col-md-10">
            <div class="card-header row">
                <div class="col-md-6">
                    <h3 class="ml-4 mt-2">Function</h3>
                </div>
                <div class="col-md-6">
                    <a :href="base_url+'employee_functions'"
                       class="btn btn-success btn-xs float-right"
                    > Back </a>
                </div>
            </div>
            <div class="card-body">
                <h6>{{ name }}</h6>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";
export default {
    props: ['e_function','translations'],
    data() {
        return {
            message: "",
            name: "",
            base_url:APP_URL,
        }
    },
    mounted() {
        axios.get(`${APP_URL}/employee_functions/${this.e_function}`)
            .then(response => {
                const employee_function = response.data

                this.name = employee_function.name
            });
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

.card {
    font-size: 15px;
    padding: 6px 12px;
    margin-top: 15px;
    margin-left: 30px;
    border-radius: 5px;
}

</style>

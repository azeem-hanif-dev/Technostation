<template>
    <div>
        <br>
        <h2>{{ translations.add_new }}</h2>
        <form @submit.prevent="createfunction">
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

                <a :href="base_url+'employee_functions'"
                       class="mt-0 btn btn-danger"
                       style="width: 100px;"
                > {{ translations.cancel }} </a>

                <button style="margin-right: 50px;
                           width: 80px;
                           margin-left: 10px;"
                       type="submit"
                        class="submit mt-0 btn btn-primary">{{ translations.submit }}</button>
            </div>
        </div>
        </form>
    </div>
</template>
<script>
import Toastify from 'toastify-js';
import Swal from "sweetalert2";
export default {
    props: ['translations'],
    data() {
        return {
            message: "",
            name: "",
            code: "",
            base_url:APP_URL,
        };
    },
    mounted() {
        console.log("create component mounted");
    },
    methods: {
        createfunction() {
            axios.post(APP_URL + '/employee_functions', {
                name: this.name,
                code: this.code,
            }).then((response) => {
                this.message = response.data.message
                this.showToast(this.message);
                this.$emit('Function created', response.data);
                this.name = '';
                this.code = '';
                Swal.fire(
                    'Good job!',
                     this.message,
                    'success'
                )
                let url = this.base_url + 'employee_functions';
                setTimeout(function() {
                    window.location.href = url;
                }, 1500);
            });
        },

        showToast(message) {
            Toastify({
                text: message,
                duration: 3000,
                newWindow: true,
                close: true,
                gravity: 'top',
                position: 'right',
                backgroundColor: 'linear-gradient(to right, #00b09b, #96c93d)',
                stopOnFocus: true,
            }).showToast();
        }
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

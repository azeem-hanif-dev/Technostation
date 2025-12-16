<template>
    <div>
        <form @submit.prevent="updateRights">
            <div class="row">
                <div class="col-md-6">
                    <label for="user">User:</label>
                    <select disabled required class="form-control" v-model="user_id" @change="getUserData">
                        <option v-for="user in users" :value="user.id">{{ user.name }}</option>
                    </select>
                    <br>
                </div>
                <div class="col-md-6">
                    <label for="role">Role:</label>
                    <select required class="form-control" v-model="role_id">
                        <option v-for="role in roles" :value="role.id">{{ role.name }}</option>
                    </select>
                    <br>
                </div>
            </div>
            <div class="row mt-0">
                <div class="col-md-6">
                    <label for="email">Email:</label>
                    <input type="email" disabled v-model="email" id="email" name="email" required>
                    <br>
                </div>
                <div class="col-md-6">
                    <label for="role">Status:</label>
                    <select id="role" v-model="status">
                        <option value="1">Active</option>
                        <option value="0">Blocked</option>
                    </select>
                    <br>
                </div>
            </div>
            <div class="row">
                <div style="margin: 10px; text-align: end" class="col-lg-12">
                    <a :href="base_url+'user-options'"
                       class="submit btn btn-danger"
                       style="width: 100px;"

                    > Cancel </a>

                    <button style="margin-right: 50px;
                           width: 80px;
                           margin-left: 10px;"
                            type="submit"
                            class="submit btn btn-success"
                    >Submit</button>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
import Toastify from 'toastify-js';
import Swal from "sweetalert2";
import axios from "axios";

export default {
    props: ['id','users', 'roles','options'],
    data() {
        return {
            user_id: "",
            role_id: "",
            name: "",
            email: "",
            status: "",
            base_url: APP_URL,
        };
    },
    mounted() {
        axios.get(`${APP_URL}/user-options/${this.id}`)
            .then(response => {
                const user_right = response.data

                this.role_id = user_right.role_id
                this.user_id = user_right.user_id
                this.status= user_right.active
                this.getUserData();
            });
    },
    methods: {
        getUserData(){
            axios.get(`${APP_URL}/show-user/${this.user_id}`)
                .then(response => {
                    const user = response.data
                    this.email = user.email
                });
        },
        updateRights() {
            axios.put(APP_URL + 'user-options/'+this.id, {
                role_id: this.role_id,
                status: this.status,
            }).then((response) => {
                Swal.fire(
                    'Good Job',
                    response.data.message,
                    'success'
                )

                let url = this.base_url + 'user-options';
                setTimeout(function () {
                    window.location.href = url;
                }, 1500);
            });
        },
        submitForm: function () {
            this.formSubmitted = true
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
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

form {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);

}

h1 {
    text-align: center;
}

label {
    display: block;
    margin-bottom: 8px;
}

select,
input {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 4px;
}
</style>

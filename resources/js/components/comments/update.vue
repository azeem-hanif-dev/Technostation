<template>
    <div>
        <h3 class="ml-4 mt-2">Update {{ translations.comments }}</h3>

        <form @submit.prevent="updateComment">
            <div style="margin: 5px;" class="row">
                <span>{{ translations.comments }}:*</span><br>
                <input class="form-control" required v-model="name">
                <br>
            </div>

            <div class="row">
                <div style="margin: 10px; text-align: end" class="col-lg-12">
                    <a :href="base_url+'comments'"
                       class="submit btn btn-danger"
                       style="width: 100px;"

                    > {{ translations.cancel }} </a>

                    <input style="margin-right: 50px;
                           width: 80px;
                           margin-left: 10px;"
                           type="submit"
                           class="submit btn btn-success"
                           value="Update"
                    />
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
    props: ['comment','translations'],
    data() {
        return {
            message: "",
            name: "",
            base_url:APP_URL,
        }
    },
    mounted() {
        console.log("Mounted");

        axios.get(`${APP_URL}comments/${this.comment.id}`)
            .then(response => {
                const comment_data = response.data
                this.name = comment_data.comment;
            });
    },
    methods: {
        updateComment() {
            axios.put(APP_URL + 'comments/'+this.comment.id, {
                name: this.name,
            }).then((response) => {
                this.message = response.data.message
                this.showToast(this.message);
                this.$emit('Comment created', response.data);
                this.name = '';
                   Swal.fire(
                    'Good job!',
                    this.message =response.data.message,
                    'success'
                )
                let url = this.base_url + 'comments';
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

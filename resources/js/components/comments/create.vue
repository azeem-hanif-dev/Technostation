<template xmlns="http://www.w3.org/1999/html">
    <div>
        <h3 class="ml-4 mt-2">{{ translations.add_new }} {{ translations.comments }}</h3>

        <form @submit.prevent="createComment">
        <div style="margin: 5px;" class="row">
            <span>{{ translations.comments }}:*</span><br>
            <textarea maxlength="2000" rows="5" class="form-control" required v-model="comment"></textarea>
            <br>
        </div>


        <div class="row">
            <div style="margin: 10px; text-align: end" class="col-lg-12">
                <a :href="base_url+'comments'"
                   class="submit btn btn-danger"
                   style="width: 100px;"

                > {{ translations.cancel }} </a>

                <button style="margin-right: 50px;
                           width: 80px;
                           margin-left: 10px;"
                       type="submit"
                       class="submit btn btn-success"
                >{{ translations.submit }}</button>
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
            status: false,
            comment: "",
            base_url:APP_URL,
        }
    },
    mounted() {
        console.log("Mounted");
    },
    methods: {
        createComment() {
            axios.post(APP_URL + 'comments', {
                comment: this.comment,
            }).then((response) => {
                Swal.fire(
                    'Good job!',
                    this.message =response.data.message,
                    'success'
                )
                let url = this.base_url + 'comments';
                setTimeout(function() {
                    window.location.href = url;
                }, 1500);

            }).catch((err)=>{
                this.message = err.response.data.metadata.message.comment[0]
                Swal.fire(
                    'Bad job!',
                    this.message,
                    'error'
                )
            })
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

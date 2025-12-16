<template>
    <div>
        <br>
        <div style="margin: 10px;" class="row">
            <div class="col-md-6 form-group">
                <span>{{ translations.staff }}:*</span><br>
                <select class="form-control" v-model="personnel">
                    <option v-for="personnel in personnels" :value="personnel.id">{{ personnel.first_name }}  {{ personnel.last_name }}</option>
                </select><br>
                <span>{{ translations.e_function }}:*</span><br>
                <select class="form-control" v-model="employee_function">
                    <option v-for="e_function in e_functions" :value="e_function.id">{{ e_function.name }}</option>
                </select>
                <br>



            </div>

            <div class="col-md-6 form-group">
                <span>{{ translations.status }}:*</span><br>
                <select class="form-control" v-model="p_status">
                    <option value="1">{{ translations.directing }}</option>
                    <option value="2">{{ translations.accepting }}</option>
                </select>
                <br>
                <span>{{ translations.comments }}:</span><br>
                <textarea class="form-control"
                          v-model="comments"
                          type="text"
                          aria-multiline="true"
                          placeholder="Enter comment"
                /><br>
<!--                <span>{{ translations.group }}:*</span><br>-->
<!--                <select class="form-control" v-model="group">-->
<!--                    <option v-for="group in groups" :value="group.id">{{ group.name }}</option>-->
<!--                </select>-->
<!--                <br>-->
            </div>
        </div>

        <div class="row">
            <div style="margin: 10px; text-align: end" class="col-lg-12">
                <a :href="base_url+'project_plannings'"
                   class="submit btn btn-danger"
                   value="Cancel"
                   style="width: 100px; background-color: red;"

                > {{ translations.cancel }} </a>

                <input style="margin-right: 50px; background-color: green;
                           width: 80px;
                           margin-left: 10px;"
                       type="submit"
                       class="submit btn btn-success"
                       value="Update"
                       @click.prevent="updatePlanning"
                />
            </div>
        </div>

    </div>
</template>
<script>
import Toastify from 'toastify-js';
import Swal from "sweetalert2";
import axios from "axios";
export default {
    props: ['e_functions','personnels','employee_project','groups','translations'],
    data() {
        return {
            message: "",
            personnel: "",
            employee_function: null,
            // group: null,
            p_status: null,
            comments: '',
            plan_date: '',
            base_url:APP_URL,
        };
    },
    mounted(){
        console.log("Update component mounted");
        this.employee_function = this.employee_project.geschikt
        // this.group = this.employee_project.group_id
        this.personnel = this.employee_project.employee_id
        this.p_status = this.employee_project.status
        this.comments = this.employee_project.notes
    },
    methods: {
        updatePlanning()
        {
            axios.put(APP_URL + 'employee_plannings/'+this.employee_project.id, {
                employee_function: this.employee_function,
                group: this.group,
                personnel: this.personnel,
                p_status: this.p_status,
                comments: this.comments,
            }).then((response) => {
                // Swal.fire(
                //     'Good job!',
                //     'Planning Updated Successfully!',
                //     'success'
                // )
                   Swal.fire(
                            "Good job!",
                            this.message = response.data.message,
                            "success"
                        );
                let url = this.base_url + 'project_plannings';
                setTimeout(function() {
                    window.location.href = url;
                }, 1500);

                this.$emit('PLanning updated', response.data);
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

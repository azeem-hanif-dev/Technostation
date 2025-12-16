<template>
    <div>
        <br>
        <form @submit.prevent="createPlanning">
        <div style="margin: 10px;" class="row">
            <div class="col-md-6 form-group">
                <span>{{ translations.staff }}:*</span><br>
                <!--select class="form-control" v-model="personnel">
                    <option v-for="personnel in personnels" :value="personnel.id">{{ personnel.first_name }} {{ personnel.last_name }}</option>
                </select-->
                <v-select 
                    v-model="personnel"
                    :options="personnelsWithFullName"
                    label="fullName"
                    :reduce="person => person.id"
                    :searchable="true"
                    :clearable="false"
                ></v-select>
                <br>

                <span>{{ translations.e_function }}:*</span><br>
                <!--select class="form-control" v-model="employee_function">
                    <option v-for="e_function in e_functions" :value="e_function.id">{{ e_function.name }}</option>
                </select-->

                <v-select 
                    v-model="employee_function"
                    :options="e_functions"
                    label="name"
                    :reduce="func => func.id"
                    :searchable="true"
                    :clearable="false"
                ></v-select>

                <br>

            </div>

            <div class="col-md-6 form-group">
                <span>{{ translations.status }}:*</span><br>
                <select class="form-control" v-model="p_status">
                    <option :value="1">{{ translations.directing }}</option>
                    <option :value="2">{{ translations.accepting }}</option>
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
                <a :href="base_url+'project_plannings/'+this.planning_id+'/edit'"
                   class="submit btn btn-danger"
                   value="Cancel"
                   style="width: 100px;"

                > {{ translations.cancel }} </a>

                <button style="margin-right: 50px;
                           width: 80px;
                           margin-left: 10px;"
                       type="submit"
                       class="submit btn btn-primary"
                >{{ translations.submit }}</button>
            </div>
        </div>
        </form>
    </div>
</template>
<script>
import Toastify from 'toastify-js';
import Swal from "sweetalert2";
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

export default {
    props: ['e_functions','personnels','planning_id','plan_date','project_id','groups','translations'],
    data() {
        console.log(this.selected);
        return {
            message: "",
            personnel: null,
            employee_function: null,
            // group: null,
            p_status: null,
            comments: '',
            plan_date: '',
            project_id: '',
            base_url:APP_URL,
        };
    },
    components: {        
        vSelect,
    },
    mounted() {
        console.log("Mounted");
    },
    computed: {
    personnelsWithFullName() {
        return this.personnels.map(person => ({
            ...person,
            fullName: `${person.first_name} ${person.last_name}`
        }));
    }
    },
    methods: {
        createPlanning() {
            console.log(this.selected);
            axios.post(APP_URL + 'employee_plannings', {
                personnel: this.personnel,
                employee_function: this.employee_function,
                // group: this.group,
                p_status: this.p_status,
                comments: this.comments,
                plan_date: this.plan_date,
                project_id: this.project_id,
            }).then((response) => {

                this.personnel = null;
                this.employee_function = null;
                // this.group = null;
                this.p_status = null;
                this.comments = "";

                if (response.data.status)
                {

                       Swal.fire(
                        "Good job!",
                        this.message = response.data.message,
                        "success"
                    );
                    // Swal.fire(
                    //     'Good job!',
                    //     'Staff added Successfully!',
                    //     'success'
                    // )
                    let url = this.base_url + 'project_plannings/'+this.planning_id+'/edit';
                    setTimeout(function() {
                        window.location.href = url;
                    }, 1500);
                } else {
                    Swal.fire(
                        'Bad job!',
                        response.data.message,
                        'error'
                    )
                }
            }).catch((err)=>{
                Swal.fire(
                    'Bad job!',
                    "Invalid input",
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

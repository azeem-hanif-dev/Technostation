<template>
    <div>
        <h2 class="p-4">{{ translations.create_order }}</h2>
        <div></div>
        <form>
            <!-- PAGE 1 -->
            <div v-if="currentPage === 1" style="margin: 10px;">
                <div style="margin: 10px;" class="row">
                    <div class="col-md-6 form-group">
                        <span>{{ translations.project_name }}:*</span><br />
                        <v-select
                            v-model="project"
                            :options="projects"
                            label="name"
                            :reduce="project => project.id"
                            @input="getProjectData"
                            placeholder="Select a customer"
                        ></v-select>
                        <br />
                        <!--                        <select class="form-control" v-model="project" @change="getProjectData">-->
                        <!--                            <option v-for="project in projects" :value="project.id">{{ project.name }}</option>-->
                        <!--                        </select><br>-->

                        <span>{{ translations.department }}:*</span><br />
                        <input
                            class="form-control"
                            v-model="department"
                            type="text"
                            disabled
                        /><br />

                        <span>{{ translations.project_address }}:</span><br />
                        <input
                            class="form-control"
                            v-model="project_address"
                            type="text"
                            disabled
                        /><br />

                        <span>{{ translations.zipcode_city }}:</span><br />
                        <input
                            class="form-control"
                            v-model="zipcode"
                            type="text"
                            disabled
                        /><br />

                        <span>Performer:</span><br />
                        <input
                            class="form-control"
                            v-model="performer"
                            type="text"
                            disabled
                        /><br />
                    </div>

                    <div class="col-md-6 form-group">
                        <span>{{ translations.mobile_number }}:</span><br />
                        <input
                            class="form-control"
                            v-model="mobile"
                            type="text"
                            disabled
                        /><br />

                        <span>{{ translations.order_date_time }}:*</span><br />
                        <input
                            class="form-control"
                            :min="minDateTime"
                            v-model="order_date_time"
                            type="datetime-local"
                            required
                        /><br />

                        <span>{{ translations.waste_processor }}:*</span><br />
                        <select
                            class="form-control"
                            v-model="supplier"
                            required
                            @change="getSupplierData"
                        >
                            <option
                                v-for="supplier in suppliers"
                                :value="supplier.id"
                                >{{ supplier.company_name }}
                            </option> </select
                        ><br />

                        <span>{{ translations.email_waste_processor }}:</span
                        ><br />
                        <input
                            class="form-control"
                            v-model="email"
                            type="email"
                            required
                        /><br />

                        <span>{{ translations.telephone }}:</span><br />
                        <input
                            class="form-control"
                            v-model="phone_number"
                            type="text"
                            required
                        /><br />
                    </div>
                </div>
                <div style="margin: 10px;" class="row">
                    <div class="col-md-6 form-group">
                        <span>{{ translations.order_create_by }}:</span><br />
                        <input
                            class="form-control"
                            v-model="order_by"
                            type="text"
                            required
                        /><br />

                        <span>{{ translations.execution_date }}:*</span><br />
                        <input
                            class="form-control"
                            v-model="execution_date"
                            :min="minDate"
                            type="date"
                            required
                        />
                    </div>

                    <div class="col-md-6 form-group">
                        <span>{{ translations.approved_by }}:</span><br />
                        <input
                            class="form-control"
                            v-model="approved_by"
                            type="text"
                            required
                        /><br />

                        <span>{{ translations.part_of_the_day }}:*</span><br />
                        <select class="form-control" v-model="desired_time">
                            <option value="1">As soon as possible</option>
                            <option value="2">Morning</option>
                            <option value="3">Afternoon</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <span>{{ translations.container_notes }}:</span><br />
                        <textarea
                            class="form-control"
                            v-model="container_notes"
                        ></textarea
                        ><br />

                        <span>{{ translations.comments }}:</span><br />
                        <textarea
                            class="form-control"
                            v-model="comments"
                        ></textarea
                        ><br />
                    </div>
                </div>
                <div class="row">
                    <div
                        style="margin: 10px; text-align: end"
                        class="col-lg-12"
                    >
                        <button
                            @click="cancelButton"
                            class="mt-0 btn btn-danger"
                            style="width: 100px;"
                        >
                            Cancel
                        </button>
                        <button
                            style="margin-right: 50px;
                           width: 80px;
                           margin-left: 10px;"
                            type="button"
                            class="btn btn-primary"
                            @click="pageIncrement"
                        >
                            {{ translations.next }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- PAGE 2 -->
            <!--            <div v-else-if="currentPage === 2" style="margin: 10px;">-->
            <!--                <div class="row">-->
            <!--                    <div class="col-md-6 form-group">-->
            <!--                        <span>{{ translations.order_create_by }}:</span><br>-->
            <!--                        <input class="form-control" v-model="order_by" type="text" required/><br>-->

            <!--                        <span>{{ translations.execution_date }}:</span><br>-->
            <!--                        <input class="form-control" v-model="execution_date" type="date" required/>-->
            <!--                    </div>-->

            <!--                    <div class="col-md-6 form-group">-->
            <!--                        <span>{{ translations.approved_by }}:</span><br>-->
            <!--                        <input class="form-control" v-model="approved_by" type="text" required/><br>-->

            <!--                        <span>{{ translations.part_of_the_day }}:</span><br>-->
            <!--                        <select class="form-control" v-model="desired_time">-->
            <!--                            <option value="1">As soon as possible</option>-->
            <!--                            <option value="2">Morning</option>-->
            <!--                            <option value="3">Afternoon</option>-->
            <!--                        </select>-->
            <!--                    </div>-->

            <!--                    <div class="col-md-12">-->
            <!--                        <span>{{ translations.container_notes }}:</span><br>-->
            <!--                        <textarea class="form-control" v-model="container_notes"></textarea><br>-->

            <!--                        <span>{{ translations.comments }}:</span><br>-->
            <!--                        <textarea class="form-control" v-model="comments"></textarea><br>-->
            <!--                    </div>-->
            <!--                </div>-->

            <!--                <div class="text-end m-3">-->
            <!--                    <a @click="pageDecrement" class="btn btn-primary me-2">{{ translations.back }}</a>-->
            <!--                    <button type="button" class="btn btn-primary" @click="pageIncrement">{{-->
            <!--                            translations.next-->
            <!--                        }}-->
            <!--                    </button>-->
            <!--                </div>-->
            <!--            </div>-->
            <!-- PAGE 3 - Containers -->
            <!--            <div v-else-if="currentPage === 2" style="margin: 10px;">-->
            <!--                <div class="card-body">-->
            <!--                    <table class="table table-bordered text-center">-->
            <!--                        <thead>-->
            <!--                        <tr>-->
            <!--                            <th rowspan="2">{{ translations.container_type }}</th>-->
            <!--                            <th colspan="3">{{ translations.number_of_containers }}</th>-->
            <!--                        </tr>-->
            <!--                        <tr>-->
            <!--                            <th>{{ translations.place }}</th>-->
            <!--                            <th>{{ translations.vary }}</th>-->
            <!--                            <th>{{ translations.disposal }}</th>-->
            <!--                        </tr>-->
            <!--                        </thead>-->
            <!--                        <tbody>-->
            <!--                        <tr v-for="(container, key) in containerFields" :key="key">-->
            <!--                            <th>{{ container.label }}</th>-->
            <!--                            <td><input class="form-control" type="number" min="0" v-model="container.place"></td>-->
            <!--                            <td><input class="form-control" type="number" min="0" v-model="container.vary"></td>-->
            <!--                            <td><input class="form-control" type="number" min="0" v-model="container.disposal"></td>-->
            <!--                        </tr>-->
            <!--                        </tbody>-->
            <!--                    </table>-->
            <!--                </div>-->

            <!--                <div class="text-end m-3">-->
            <!--                    <a @click="pageDecrement" class="btn btn-primary me-2">{{ translations.back }}</a>-->
            <!--                    <button type="button" class="btn btn-primary" @click="pageIncrement">{{-->
            <!--                            translations.next-->
            <!--                        }}-->
            <!--                    </button>-->
            <!--                </div>-->
            <!--            </div>-->

            <!-- PAGE 4 - Waste Breakdown -->
            <div v-else-if="currentPage === 2" style="margin: 10px;">
                <div class="card-body">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th colspan="4">
                                    {{ translations.number_of_containers }}
                                </th>
                                <th colspan="7">
                                    {{ translations.number_per_waste }}
                                </th>
                            </tr>
                            <tr>
                                <th>{{ translations.container_type }}</th>
                                <th>{{ translations.place }}</th>
                                <th>{{ translations.vary }}</th>
                                <th>{{ translations.disposal }}</th>
                                <th>BSA</th>
                                <th>{{ translations.debris }}</th>
                                <th>{{ translations.wood }}</th>
                                <th>Plastic Foil</th>
                                <th>{{ translations.paper }}</th>
                                <th>{{ translations.diverse }}</th>
                                <th>{{ translations.comments }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in fields" :key="index">
                                <th>{{ item.label }}</th>
                                <td>
                                    <input
                                        class="form-control"
                                        type="number"
                                        min="0"
                                        v-model="item.place"
                                    />
                                </td>
                                <td>
                                    <input
                                        class="form-control"
                                        with="60px"
                                        type="number"
                                        min="0"
                                        v-model="item.vary"
                                    />
                                </td>
                                <td>
                                    <input
                                        class="form-control"
                                        type="number"
                                        min="0"
                                        v-model="item.disposal"
                                    />
                                </td>
                                <td>
                                    <input
                                        class="form-control"
                                        type="number"
                                        min="0"
                                        v-model="item.bsa"
                                    />
                                </td>
                                <td>
                                    <input
                                        class="form-control"
                                        type="number"
                                        min="0"
                                        v-model="item.debris"
                                    />
                                </td>
                                <td>
                                    <input
                                        class="form-control"
                                        type="number"
                                        min="0"
                                        v-model="item.wood"
                                    />
                                </td>
                                <td>
                                    <input
                                        class="form-control"
                                        type="number"
                                        min="0"
                                        v-model="item.plastic_foil"
                                    />
                                </td>
                                <td>
                                    <input
                                        class="form-control"
                                        type="number"
                                        min="0"
                                        v-model="item.paper"
                                    />
                                </td>
                                <td>
                                    <input
                                        class="form-control"
                                        type="number"
                                        min="0"
                                        v-model="item.diverse"
                                    />
                                </td>
                                <td>
                                    <input
                                        class="form-control"
                                        v-model="item.comment"
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div
                        style="margin: 10px; text-align: end"
                        class="col-lg-12"
                    >
                        <div class="text-end m-3">
                            <a
                                @click="pageDecrement"
                                class="btn btn-primary me-2"
                                >{{ translations.back }}</a
                            >
                            <button
                                style="margin-right: 50px;
                           width: 80px;
                           margin-left: 10px;"
                                type="button"
                                class="btn btn-primary"
                                @click="createOrderContainer"
                            >
                                {{ translations.submit }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
<style>
#vs1__combobox {
    padding: 4px !important;
}
</style>
<script>
import axios from "axios";
import Swal from "sweetalert2";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import ProjectMap from "../project/project_map.vue";
import Datepicker from "vuejs-datepicker";
import Upload_documents from "../common/upload_documents.vue";

export default {
    props: ["projects", "suppliers", "translations", "containerType", "user"],
    components: {
        vSelect
    },
    data() {
        return {
            currentPage: 1,
            base_url: APP_URL,
            // Order details
            project: null,
            supplier: null,
            email: "",
            department: "",
            project_address: "",
            zipcode: "",
            performer: "",
            mobile: "",
            phone_number: "",
            order_by: this.user.name,
            order_by_id: this.user.id,
            order_date_time: "",
            minDateTime: this.getCurrentDateTime(),
            minDate: this.getTodayDate(),
            execution_date: "",
            approved_by: "",
            desired_time: "",
            container_notes: "",
            comments: "",
            fields: [
                {
                    label: "3m³",
                    place: 0,
                    vary: 0,
                    disposal: 0,
                    bsa: 0,
                    debris: 0,
                    wood: 0,
                    plastic_foil: 0,
                    paper: 0,
                    diverse: 0,
                    comment: ""
                },
                {
                    label: "6m³",
                    place: 0,
                    vary: 0,
                    disposal: 0,
                    bsa: 0,
                    debris: 0,
                    wood: 0,
                    plastic_foil: 0,
                    paper: 0,
                    diverse: 0,
                    comment: ""
                },
                {
                    label: "10m³",
                    place: 0,
                    vary: 0,
                    disposal: 0,
                    bsa: 0,
                    debris: 0,
                    wood: 0,
                    plastic_foil: 0,
                    paper: 0,
                    diverse: 0,
                    comment: ""
                },
                {
                    label: "10m³dicht",
                    place: 0,
                    vary: 0,
                    disposal: 0,
                    bsa: 0,
                    debris: 0,
                    wood: 0,
                    plastic_foil: 0,
                    paper: 0,
                    diverse: 0,
                    comment: ""
                },
                {
                    label: "20m³",
                    place: 0,
                    vary: 0,
                    disposal: 0,
                    bsa: 0,
                    debris: 0,
                    wood: 0,
                    plastic_foil: 0,
                    paper: 0,
                    diverse: 0,
                    comment: ""
                }
            ]
        };
    },

    methods: {
        pageIncrement() {
            this.currentPage++;
        },
        pageDecrement() {
            this.currentPage--;
        },
        getCurrentDateTime() {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            return now.toISOString().slice(0, 16);
        },
        getTodayDate() {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, "0");
            const day = String(today.getDate()).padStart(2, "0");
            return `${year}-${month}-${day}`;
        },
        getProjectData() {
            axios
                .get(`${APP_URL}/staffing_projects/${this.project}`)
                .then(response => {
                    const project = response.data;
                    this.department = project.department.name;
                    this.mobile = project.department.phone;
                    this.performer = project.performer;
                    this.project_address = project.address;
                    this.zipcode = project.post_code + " " + project.city;
                });
        },

        getSupplierData() {
            const supplier = this.suppliers.find(data => {
                return data.id === this.supplier;
            });
            this.email = supplier.email;
            this.phone_number = supplier.telephone || null;
        },

        createOrderContainer() {
            axios
                .post(APP_URL + "order-waste-container", {
                    project: this.project,
                    email: this.email,
                    container_notes: this.container_notes,
                    approved_by: this.approved_by,
                    order_date_time: this.order_date_time,
                    execution_date: this.execution_date,
                    desired_time: this.desired_time,
                    supplier: this.supplier,
                    department: this.department,
                    project_address: this.project_address,
                    zipcode: this.zipcode,
                    performer: this.performer,
                    // order_by: this.order_by_id,
                    order_by: this.order_by,
                    phone_number: this.phone_number,
                    mobile: this.mobile,
                    comments: this.comments,
                    fields: this.fields
                })
                .then(response => {
                    console.log(response.data);
                    Swal.fire("Success", response.data.message, "success");
                    setTimeout(
                        () =>
                            (window.location.href =
                                this.base_url + "order-waste-container"),
                        1500
                    );
                })
                .catch(error => {
                    if (error.response.data.metadata.message) {
                        const messages = error.response.data.metadata.message;
                        const allMessages = Object.values(messages).flat();
                        const errorText = allMessages.join("<br>");

                        Swal.fire({
                            icon: "error",
                            title: "Validation Error",
                            html: errorText
                        });
                    } else {
                        Swal.fire("Error", "Something went wrong.", "error");
                    }
                });
        },
        cancelButton() {
            const base_url = window.location.origin;
            window.location.href = base_url + "/order-waste-container";
        }
    },
    mounted() {
        console.log(this.user.id);
    }
};
</script>

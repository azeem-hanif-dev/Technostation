<template>
    <div>
        <h2>Update</h2>
        <div style="margin: 10px;" v-if="currentPage === 1">
            <div class="row">
                <div class="col-md-6 form-group">
                    <span>{{ translations.project_name }}:*</span><br />
                    <input
                        class="form-control"
                        v-model="project_name"
                        type="text"
                        @input="checkIfChanged"
                        disabled
                    /><br />
                    <span>{{ translations.order_date_time }}:*</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="order_date_time"
                        @input="checkIfChanged"
                        :min="minDateTime"
                        type="datetime-local"
                    /><br />
                    <span>{{ translations.waste_processor }}*</span><br />
                    <select
                        class="form-control"
                        v-model="supplier_id"
                        @change="getSupplierData"
                        required
                    >
                        <option
                            v-for="supplier in suppliers"
                            :value="supplier.id"
                            :key="supplier.id"
                        >
                            {{ supplier.company_name }}
                        </option>
                    </select>

                    <!-- <span>{{ translations.waste_processor }}*</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="supplier_name"
                        @input="checkIfChanged"
                        disabled
                    /> -->
                    <br />
                    <span>{{ translations.execution_date }}:*</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="execution_date"
                        @input="checkIfChanged"
                        :min="minDate"
                        type="date"
                    /><br />
                </div>
                <div class="col-md-6 form-group">
                    <span>{{ translations.order_by }}:*</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="order_by"
                        @input="checkIfChanged"
                        type="text"
                    /><br />
                    <span>{{ translations.approved_by }}:*</span><br />
                    <input
                        class="form-control"
                        required
                        v-model="approved_by"
                        @input="checkIfChanged"
                        type="text"
                    /><br />
                    <span>{{ translations.container_notes }}:</span><br />
                    <input
                        class="form-control"
                        v-model="container_notes"
                        @input="checkIfChanged"
                        type="text"
                    /><br />
                    <span>{{ translations.part_of_the_day }}:*</span><br />
                    <select class="form-control" v-model="desired_time">
                        <option value="1">As soon as possible</option>
                        <option value="2">Morning</option>
                        <option value="3">Afternoon</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <span>Comments:</span><br />
                    <textarea
                        class="form-control"
                        v-model="comments"
                        @input="checkIfChanged"
                        placeholder="Comments"
                    ></textarea
                    ><br />
                </div>
            </div>
            <div class="row">
                <div style="margin: 10px; text-align: end" class="col-lg-12">
                    <button
                        @click="cancelButton"
                        class="mt-0 btn btn-danger"
                        style="width: 100px;"
                    >
                        Cancel
                    </button>
                    <button
                        style="margin-right: 50px; width: 80px; margin-left: 10px;"
                        class="btn mt-0 btn-primary"
                        @click="pageIncrement"
                    >
                        {{ translations.next }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Container Page -->
        <div style="margin: 10px;" v-else-if="currentPage === 2">
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
                                    @input="checkIfChanged"
                                />
                            </td>
                            <td>
                                <input
                                    class="form-control"
                                    type="number"
                                    min="0"
                                    v-model="item.vary"
                                    @input="checkIfChanged"
                                />
                            </td>
                            <td>
                                <input
                                    class="form-control"
                                    type="number"
                                    min="0"
                                    v-model="item.disposal"
                                    @input="checkIfChanged"
                                />
                            </td>
                            <td>
                                <input
                                    class="form-control"
                                    type="number"
                                    min="0"
                                    v-model="item.bsa"
                                    @input="checkIfChanged"
                                />
                            </td>
                            <td>
                                <input
                                    class="form-control"
                                    type="number"
                                    min="0"
                                    v-model="item.debris"
                                    @input="checkIfChanged"
                                />
                            </td>
                            <td>
                                <input
                                    class="form-control"
                                    type="number"
                                    min="0"
                                    v-model="item.wood"
                                    @input="checkIfChanged"
                                />
                            </td>
                            <td>
                                <input
                                    class="form-control"
                                    type="number"
                                    min="0"
                                    v-model="item.plastic_foil"
                                    @input="checkIfChanged"
                                />
                            </td>
                            <td>
                                <input
                                    class="form-control"
                                    type="number"
                                    min="0"
                                    v-model="item.paper"
                                    @input="checkIfChanged"
                                />
                            </td>
                            <td>
                                <input
                                    class="form-control"
                                    type="number"
                                    min="0"
                                    v-model="item.diverse"
                                    @input="checkIfChanged"
                                />
                            </td>
                            <td>
                                <input
                                    class="form-control"
                                    v-model="item.comment"
                                    @input="checkIfChanged"
                                />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div style="margin: 10px; text-align: end" class="col-lg-12">
                    <a
                        style="margin-top: 10px; width: 80px;"
                        class="btn btn-primary"
                        @click="pageDecrement"
                    >
                        {{ translations.back }}
                    </a>
                    <button
                        style="margin-right: 50px; width: 80px; margin-left: 10px;"
                        :disabled="!isChanged"
                        @click.prevent="updateOrder"
                        class="submit btn btn-primary"
                    >
                        {{ translations.submit }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Waste Page -->
        <!--        <div style="margin: 10px;" v-else-if="currentPage === 3">-->
        <!--            <div class="card-body">-->
        <!--                <table class="table table-bordered" style="text-align:center;">-->
        <!--                    <thead>-->
        <!--                    <tr>-->

        <!--                        <th colspan="7" class="center">{{ translations.number_per_waste }}</th>-->
        <!--                    </tr>-->
        <!--                    <tr>-->
        <!--                        <th class="center">{{ translations.container_type }}</th>-->
        <!--                        <th class="center">BSA</th>-->
        <!--                        <th class="center">Debris</th>-->
        <!--                        <th class="center">{{ translations.wood }}</th>-->
        <!--                        <th class="center">Plastic Folie</th>-->
        <!--                        <th class="center">{{ translations.paper }}</th>-->
        <!--                        <th class="center">Diverse</th>-->
        <!--                        <th class="center" style="width: 20%;">{{ translations.comments }}</th>-->
        <!--                    </tr>-->
        <!--                    </thead>-->
        <!--                    <tbody>-->
        <!--                    <tr v-for="(waste, index) in wasteFields" :key="index">-->
        <!--                        <th>{{ waste.label }}</th>-->
        <!--                        <td><input class="form-control" type="number" v-model="waste.bsa"></td>-->
        <!--                        <td><input class="form-control" type="number" v-model="waste.debris"></td>-->
        <!--                        <td><input class="form-control" type="number" v-model="waste.wood"></td>-->
        <!--                        <td><input class="form-control" type="number" v-model="waste.plastic_foil"></td>-->
        <!--                        <td><input class="form-control" type="number" v-model="waste.paper"></td>-->
        <!--                        <td><input class="form-control" type="number" v-model="waste.diverse"></td>-->
        <!--                        <td><input type="text" class="form-control" v-model="waste.comment"></td>-->
        <!--                    </tr>-->
        <!--                    </tbody>-->
        <!--                </table>-->
        <!--            </div>-->
        <!--            <div class="row">-->
        <!--                <div style="margin: 10px; text-align: end" class="col-lg-12">-->
        <!--                    <a style="margin-top: 10px; width: 80px;" class="btn btn-primary" @click="pageDecrement">-->
        <!--                        {{ translations.back }}-->
        <!--                    </a>-->
        <!--                    <button style="margin-right: 50px; width: 80px; margin-left: 10px;"-->
        <!--                            type="submit"-->
        <!--                            @click.prevent="updateOrder"-->
        <!--                            class="submit btn btn-primary">-->
        <!--                        {{ translations.submit }}-->
        <!--                    </button>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
    </div>
</template>

<script>
import axios from "axios";
import Swal from "sweetalert2";

export default {
    props: ["order", "translations", "containerType", "suppliers"],
    data() {
        return {
            currentPage: 1,
            base_url: APP_URL,

            isChanged: false,
            originalState: {},

            // Order details
            project_id: null,
            project_name: null,
            supplier_id: null,
            supplier_name: null,
            email: "",
            department: "",
            project_address: "",
            zipcode: "",
            performer: "",
            mobile: "",
            phone_number: "",
            order_by: "",
            order_date_time: "",
            execution_date: "",
            minDateTime: this.getCurrentDateTime(),
            minDate: this.getTodayDate(),
            approved_by: "",
            desired_time: "",
            container_notes: "",
            comments: "",
            containerFields: [
                { label: "3m³", place: 0, vary: 0, disposal: 0 },
                { label: "6m³", place: 0, vary: 0, disposal: 0 },
                { label: "10m³", place: 0, vary: 0, disposal: 0 },
                { label: "10m³dicht", place: 0, vary: 0, disposal: 0 },
                { label: "20m³", place: 0, vary: 0, disposal: 0 }
            ],
            wasteFields: [
                {
                    label: "3m³",
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
                    bsa: 0,
                    debris: 0,
                    wood: 0,
                    plastic_foil: 0,
                    paper: 0,
                    diverse: 0,
                    comment: ""
                }
            ],

            cancel: 0,
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

    // watch: {
    //     fields: {
    //         deep: true,
    //         handler() {
    //             this.checkIfChanged();
    //         }
    //     }
    // },
    watch: {
        fields: {
            handler() {
                this.checkIfChanged();
            },
            deep: true
        },
        order_by(val) {
            this.checkIfChanged();
        },
        approved_by(val) {
            this.checkIfChanged();
        },
        order_date_time(val) {
            this.checkIfChanged();
        },
        execution_date(val) {
            this.checkIfChanged();
        },
        desired_time(val) {
            this.checkIfChanged();
        },
        container_notes(val) {
            this.checkIfChanged();
        },
        comments(val) {
            this.checkIfChanged();
        },
        supplier_id(val) {
            this.checkIfChanged();
        }
    },

    mounted() {
        axios
            .get(`${APP_URL}/order-waste-container/${this.order}`)
            .then(response => {
                const order = response.data;

                this.project_id = order.project_id;
                this.project_name = order.project.name;
                this.supplier_id =
                    order.supplier && order.supplier.id
                        ? order.supplier.id
                        : "";
                this.supplier_name =
                    order.supplier && order.supplier.company_name
                        ? order.supplier.company_name
                        : "";

                this.container_notes = order.notes;
                this.approved_by = order.approved_by;
                this.execution_date = order.execution_date;
                this.desired_time = order.part_of_day.toString();
                this.comments = order.comments;
                this.order_by = order.order_by;
                this.phone_number = order.phone_number;
                this.mobile = order.mobile;
                this.order_date_time = this.formatDateTime(
                    order.order_date_time
                );

                if (
                    order.container_operation &&
                    order.container_operation.length > 0
                ) {
                    order.container_operation.forEach(op => {
                        const containerType = op.container_type
                            ? op.container_type.name.toLowerCase()
                            : "";
                        const fields = this.fields.find(
                            c => c.label.toLowerCase() === containerType
                        );
                        const breakdown =
                            op.waste_breakdown && op.waste_breakdown[0];

                        if (
                            order.container_operation &&
                            order.container_operation.length > 0
                        ) {
                            order.container_operation.forEach(op => {
                                const containerType = op.container_type
                                    ? op.container_type.name.toLowerCase()
                                    : "";
                                const fields = this.fields.find(
                                    c => c.label.toLowerCase() === containerType
                                );
                                const breakdown =
                                    op.waste_breakdown && op.waste_breakdown[0];

                                if (fields && breakdown) {
                                    fields.bsa = breakdown.bsa || 0;
                                    fields.debris = breakdown.debris || 0;
                                    fields.wood = breakdown.wood || 0;
                                    fields.plastic_foil =
                                        breakdown.plastic_foil || 0;
                                    fields.paper = breakdown.paper || 0;
                                    fields.diverse = breakdown.diverse || 0;
                                    fields.comment = breakdown.comment || "";

                                    fields.place = op.placement || 0;
                                    fields.vary = op.exchange || 0;
                                    fields.disposal = op.discharge || 0;
                                }
                            });

                            // ✅ Now update wasteFields after fields are populated
                            this.wasteFields = this.fields.map(field => ({
                                label: field.label,
                                bsa: field.bsa,
                                debris: field.debris,
                                wood: field.wood,
                                plastic_foil: field.plastic_foil,
                                paper: field.paper,
                                diverse: field.diverse,
                                comment: field.comment
                            }));
                        }
                    });
                }

                // Save original state after data load
                this.originalState = JSON.stringify({
                    project_id: this.project_id,
                    project_name: this.project_name,
                    supplier_id: this.supplier_id,
                    supplier_name: this.supplier_name,
                    email: this.email,
                    department: this.department,
                    project_address: this.project_address,
                    zipcode: this.zipcode,
                    performer: this.performer,
                    mobile: this.mobile,
                    phone_number: this.phone_number,
                    order_by: this.order_by,
                    order_date_time: this.order_date_time,
                    execution_date: this.execution_date,
                    approved_by: this.approved_by,
                    desired_time: this.desired_time,
                    container_notes: this.container_notes,
                    comments: this.comments,
                    fields: JSON.parse(JSON.stringify(this.fields))
                });
            });
    },
    watch: {
        fields: {
            handler() {
                this.checkIfChanged();
            },
            deep: true
        }
    },
    methods: {
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
        getSupplierData() {
            const supplier = this.suppliers.find(data => {
                return data.id === this.supplier;
            });
            this.email = supplier.email;
            this.phone_number = supplier.telephone || null;
        },
        checkIfChanged() {
            const currentState = JSON.stringify({
                project_id: this.project_id,
                project_name: this.project_name,
                supplier_id: this.supplier_id,
                supplier_name: this.supplier_name,
                email: this.email,
                department: this.department,
                project_address: this.project_address,
                zipcode: this.zipcode,
                performer: this.performer,
                mobile: this.mobile,
                phone_number: this.phone_number,
                order_by: this.order_by,
                order_date_time: this.order_date_time,
                execution_date: this.execution_date,
                approved_by: this.approved_by,
                desired_time: this.desired_time,
                container_notes: this.container_notes,
                comments: this.comments,
                fields: JSON.parse(JSON.stringify(this.fields))
            });
            this.isChanged = currentState !== this.originalState;
        },
        formatDateTime(datetime) {
            if (!datetime) return "";
            const date = new Date(datetime);
            const pad = num => num.toString().padStart(2, "0");
            return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(
                date.getDate()
            )}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
        },
        pageIncrement() {
            this.currentPage++;
        },
        pageDecrement() {
            this.currentPage--;
        },
        updateOrder() {
            const fields = this.fields
                .filter(
                    waste =>
                        waste.place != 0 ||
                        waste.vary != 0 ||
                        waste.disposal != 0 ||
                        waste.bsa != 0 ||
                        waste.debris != 0 ||
                        waste.wood != 0 ||
                        waste.plastic_foil != 0 ||
                        waste.paper != 0 ||
                        waste.diverse != 0 ||
                        (waste.comment && waste.comment.trim() !== "")
                )
                .map(waste => ({
                    label: waste.label,
                    place: waste.place,
                    vary: waste.vary,
                    disposal: waste.disposal,
                    bsa: waste.bsa,
                    debris: waste.debris,
                    wood: waste.wood,
                    plastic_foil: waste.plastic_foil,
                    paper: waste.paper,
                    diverse: waste.diverse,
                    comment: waste.comment
                }));

            axios
                .put(APP_URL + "order-waste-container/" + this.order, {
                    project: this.project_id,
                    order_date_time: this.order_date_time,
                    execution_date: this.execution_date,
                    order_by: this.order_by,
                    approved_by: this.approved_by,
                    container_notes: this.container_notes,
                    desired_time: this.desired_time,
                    comments: this.comments,
                    supplier: this.supplier_id,
                    department: this.department,
                    project_address: this.project_address,
                    zipcode: this.zipcode,
                    performer: this.performer,
                    phone_number: this.phone_number,
                    mobile: this.mobile,
                    fields: fields
                })
                .then(response => {
                    Swal.fire(
                        "Good job!",
                        (this.message = response.data.message),
                        "success"
                    );
                    setTimeout(() => {
                        window.location.href =
                            this.base_url + "order-waste-container";
                    }, 1500);
                    this.$emit("Order updated", response.data);
                })
                .catch(error => {
                    Swal.fire(
                        "Error!",
                        "Failed to update order: " +
                            (error.response.data.message || error.message),
                        "error"
                    );
                });
        },
        cancelButton() {
            window.location.href =
                window.location.origin + "/order-waste-container";
        }
    }
};
</script>

<style>
form {
    padding: 10px;
}

input {
    padding: 4px;
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

.div-margin {
    margin: 10px;
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

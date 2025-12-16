<template>
    <div class="row">
        <div class="card col-md-11 mx-auto">
            <!-- Header -->
            <div class="card-header row">
                <div class="col-md-6">
                    <h4>{{ translations.waste_container }}</h4>
                </div>
                <div class="col-md-6 text-right">
                    <a
                        :href="base_url + 'order-waste-container'"
                        class="btn btn-success btn-xs"
                    >
                        {{ translations.back }}
                    </a>
                </div>
            </div>

            <!-- Order Info -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p>
                            <strong>{{ translations.project_name }}:</strong>
                            {{ project_name }}
                        </p>
                        <p>
                            <strong>{{ translations.order_date_time }}:</strong>
                            {{ order_date_time }}
                        </p>
                        <p>
                            <strong>{{ translations.waste_processor }}:</strong>
                            {{ supplier_name }}
                        </p>
                        <p>
                            <strong>{{ translations.execution_date }}:</strong>
                            {{ execution_date }}
                        </p>
                        <p>
                            <strong>{{ translations.order_by }}:</strong>
                            {{ order_by }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p>
                            <strong>{{ translations.approved_by }}:</strong>
                            {{ approved_by }}
                        </p>
                        <p>
                            <strong>{{ translations.container_notes }}:</strong>
                            {{ container_notes }}
                        </p>
                        <p>
                            <strong>{{ translations.comments }}:</strong>
                            {{ comments }}
                        </p>
                        <p>
                            <strong>{{ translations.part_of_the_day }}:</strong>
                            <span v-if="desired_time == 1"
                                >As soon as possible</span
                            >
                            <span v-else-if="desired_time == 2">Morning</span>
                            <span v-else-if="desired_time == 3">Afternoon</span>
                            <span v-else-if="desired_time == 4">Evening</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>{{ translations.container_type }}</th>
                            <th>{{ translations.place }}</th>
                            <th>{{ translations.vary }}</th>
                            <th>{{ translations.disposal }}</th>
                            <th>BSA</th>
                            <th>Debris</th>
                            <th>{{ translations.wood }}</th>
                            <th>Plastic Folie</th>
                            <th>{{ translations.paper }}</th>
                            <th>Diverse</th>
                            <th>{{ translations.comments }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(container, index) in containerFields"
                            :key="'container-' + index"
                        >
                            <th>{{ container.label }}</th>
                            <td>
                                <input
                                    type="number"
                                    class="form-control text-center"
                                    v-model="container.place"
                                    disabled
                                />
                            </td>
                            <td>
                                <input
                                    type="number"
                                    class="form-control text-center"
                                    v-model="container.vary"
                                    disabled
                                />
                            </td>
                            <td>
                                <input
                                    type="number"
                                    class="form-control text-center"
                                    v-model="container.disposal"
                                    disabled
                                />
                            </td>

                            <!-- Waste Breakdown Fields (fetched by matching label) -->
                            <template
                                v-if="
                                    wasteFields[index] &&
                                        wasteFields[index].label ===
                                            container.label
                                "
                            >
                                <td>
                                    <input
                                        type="number"
                                        class="form-control text-center"
                                        v-model="wasteFields[index].bsa"
                                        disabled
                                    />
                                </td>
                                <td>
                                    <input
                                        type="number"
                                        class="form-control text-center"
                                        v-model="wasteFields[index].debris"
                                        disabled
                                    />
                                </td>
                                <td>
                                    <input
                                        type="number"
                                        class="form-control text-center"
                                        v-model="wasteFields[index].wood"
                                        disabled
                                    />
                                </td>
                                <td>
                                    <input
                                        type="number"
                                        class="form-control text-center"
                                        v-model="
                                            wasteFields[index].plastic_foil
                                        "
                                        disabled
                                    />
                                </td>
                                <td>
                                    <input
                                        type="number"
                                        class="form-control text-center"
                                        v-model="wasteFields[index].paper"
                                        disabled
                                    />
                                </td>
                                <td>
                                    <input
                                        type="number"
                                        class="form-control text-center"
                                        v-model="wasteFields[index].diverse"
                                        disabled
                                    />
                                </td>
                                <td>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="wasteFields[index].comment"
                                        disabled
                                    />
                                </td>
                            </template>
                            <template v-else>
                                <td colspan="7">No waste data</td>
                            </template>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    props: ["orderContainer", "container", "translations"],
    data() {
        return {
            currentPage: 1,
            base_url: APP_URL,

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
            approved_by: "",
            desired_time: "",
            container_notes: "",
            comments: "",

            // Containers - now using array structure like create form
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
            ]
        };
    },

    mounted() {
        const orderData = this.orderContainer.original || this.orderContainer;

        console.log("Order Data:", orderData);

        // Basic orderContainer info
        this.project_id = orderData.project_id;
        this.project_name =
            orderData.project && orderData.project.name
                ? orderData.project.name
                : "";

        this.supplier_id =
            orderData.supplier && orderData.supplier.id
                ? orderData.supplier.id
                : "";

        this.supplier_name =
            orderData.supplier && orderData.supplier.company_name
                ? orderData.supplier.company_name
                : "";
        console.log(orderData.supplier.company_name);
        this.container_notes = orderData.notes || "";
        this.approved_by = orderData.approved_by || "";
        this.execution_date = orderData.execution_date || "";
        this.desired_time = orderData.part_of_day
            ? orderData.part_of_day.toString()
            : "";
        this.comments = orderData.comments || "";
        this.order_by = orderData.order_by || "";
        this.phone_number = orderData.phone_number || "";
        this.mobile = orderData.mobile || "";
        this.order_date_time = this.formatDateTime(orderData.order_date_time);

        // Process container operations
        if (
            orderData.container_operation &&
            orderData.container_operation.length > 0
        ) {
            orderData.container_operation.forEach(op => {
                const containerType =
                    op.container_type && op.container_type.name
                        ? op.container_type.name.toLowerCase()
                        : "";

                const containerField = this.containerFields.find(
                    c => c.label.toLowerCase() === containerType
                );
                const wasteField = this.wasteFields.find(
                    c => c.label.toLowerCase() === containerType
                );

                const breakdown =
                    op.waste_breakdown && op.waste_breakdown.length > 0
                        ? op.waste_breakdown[0]
                        : null;

                if (wasteField && breakdown) {
                    wasteField.bsa = breakdown.bsa || 0;
                    wasteField.debris = breakdown.debris || 0;
                    wasteField.wood = breakdown.wood || 0;
                    wasteField.plastic_foil = breakdown.plastic_foil || 0;
                    wasteField.paper = breakdown.paper || 0;
                    wasteField.diverse = breakdown.diverse || 0;
                    wasteField.comment = breakdown.comment || "";
                }
                if (containerField) {
                    containerField.place = op.placement || 0;
                    containerField.vary = op.exchange || 0;
                    containerField.disposal = op.discharge || 0;
                }
            });
        }
    },
    methods: {
        formatDateTime(datetime) {
            if (!datetime) return "";
            const date = new Date(datetime);
            const pad = num => num.toString().padStart(2, "0");
            return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(
                date.getDate()
            )}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
        }
    }
};
</script>
<style>
span {
    font-size: 18px;
    margin: 4px;
    font-weight: 500;
}

.card {
    padding-left: 15px;
    padding-right: 15px;
    padding-bottom: 15px;
    margin-top: 15px;
    margin-left: 30px;
    border-radius: 5px;
}
</style>

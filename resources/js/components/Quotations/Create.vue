<template>
    <div class="container">
        <h2>{{ translations.title }}</h2>

        <form @submit.prevent="submitForm">
            <!-- customer -->
            <div class="form-row">
                <!-- Customer -->
                <div class="form-group col-md-4">
                    <label for="customer">{{ translations.customer }}</label>
                    <v-select
                        v-model="form.customer_id"
                        :options="customers"
                        label="name"
                        :reduce="c => c.id"
                        placeholder="Select a customer"
                    />
                </div>

                <!-- Project -->
                <div class="form-group col-md-4">
                    <label for="project">{{ translations.project }}</label>

                    <input
                        v-model="form.project"
                        type="text"
                        class="form-control"
                        required
                    />
                </div>

                <!-- Contact Person -->
                <div class="form-group col-md-4">
                    <label for="contact_person">{{
                        translations.contact_person
                    }}</label>
                    <input
                        v-model="form.contact_person"
                        type="text"
                        class="form-control"
                        required
                    />
                </div>
            </div>

            <hr />
            <h4>{{ translations.items }}</h4>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>{{ translations.date }}</th>
                        <th>{{ translations.content }}</th>
                        <th>{{ translations.waste_type }}</th>
                        <th>{{ translations.weight }}</th>
                        <th>{{ translations.price_per_ton }}</th>
                        <th>{{ translations.extra_charge }}</th>
                        <th>{{ translations.remarks }}</th>
                        <th>{{ translations.action }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in form.items" :key="index">
                        <td>
                            <input
                                v-model="item.date"
                                type="date"
                                class="form-control"
                                required
                            />
                        </td>
                        <td>
                            <input
                                v-model="item.content"
                                type="text"
                                class="form-control"
                                required
                            />
                        </td>
                        <td>
                            <input
                                v-model="item.waste_type"
                                type="text"
                                class="form-control"
                                required
                            />
                        </td>
                        <td>
                            <input
                                v-model.number="item.weight"
                                type="number"
                                step="0.01"
                                class="form-control"
                                required
                            />
                        </td>
                        <td>
                            <input
                                v-model.number="item.price_per_ton"
                                type="number"
                                step="0.01"
                                class="form-control"
                                required
                            />
                        </td>
                        <td>
                            <input
                                v-model.number="item.extra_charge"
                                type="number"
                                step="0.01"
                                class="form-control"
                            />
                        </td>
                        <td>
                            <input
                                v-model="item.remarks"
                                type="text"
                                class="form-control"
                            />
                        </td>
                        <td>
                            <button
                                type="button"
                                class="btn btn-danger"
                                @click="removeRow(index)"
                            >
                                X
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center">
            
                <div>
                    <button
                        type="button"
                        class="add_more btn btn-success"
                        @click="addRow"
                    >
                        + {{ translations.add_item }}
                    </button>
                </div>

                <!-- Right side: Cancel and Submit buttons -->
                <div>
                    <a
                        :href="base_url + 'quotations'"
                        class="btn btn-danger mt-2"
                        style="width: 100px; margin-right: 5px;"
                    >
                        {{ translations.cancel }}
                    </a>

                    <button type="submit" class="submit btn btn-primary">
                        {{ translations.save }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import axios from "axios";
import vSelect from "vue-select";
import Swal from "sweetalert2";
import "vue-select/dist/vue-select.css";

export default {
    components: {
        vSelect
    },
    props: {
        customers: {
            type: Array,
            required: true
        },
        translations: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            base_url: APP_URL,
            form: {
                project: "",
                contact_person: "",
                customer_id: "",
                items: [
                    {
                        date: "",
                        content: "",
                        waste_type: "",
                        weight: null,
                        price_per_ton: null,
                        extra_charge: null,
                        remarks: ""
                    }
                ]
            }
        };
    },
    methods: {
        addRow() {
            this.form.items.push({
                date: "",
                content: "",
                waste_type: "",
                weight: null,
                price_per_ton: null,
                extra_charge: null,
                remarks: ""
            });
        },
        removeRow(index) {
            this.form.items.splice(index, 1);
        },
        async submitForm() {
            try {
                let response = await axios.post(
                    this.base_url + "quotations",
                    this.form
                );
                Swal.fire({
                    title: "Good job!",
                    text: response.data.message,
                    icon: "success",
                    showConfirmButton: true,
                    timer: 3000
                }).then(() => {
                    window.location.href = this.base_url + "quotations";
                });
            } catch (error) {
                Swal.fire({
                    icon: "error",
                    title: error.response.data.message || "Something went wrong"
                });
            }
        }
    }
};
</script>
<style>
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
.add_more {
    font-size: 15px;
    color: #fff;
    background: #28a745;
    padding: 6px 12px;
    border: none;
    margin-top: 8px;
    cursor: pointer;
    border-radius: 5px;
}
</style>

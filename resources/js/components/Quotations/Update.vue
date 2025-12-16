<template>
    <div class="container">
        <h2>{{ translations.edit_quotations }}</h2>

        <form @submit.prevent="updateForm">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="customer">{{ translations.customer }}</label>
                    <v-select
                        v-model="form.customer_id"
                        :options="customers"
                        label="name"
                        :reduce="c => c.id"
                    />
                </div>
                <div class="form-group col-md-4">
                    <label for="project">{{ translations.project }}</label>
                    <input v-model="form.project" class="form-control" />
                </div>
                <div class="form-group col-md-4">
                    <label for="contact_person">{{
                        translations.contact_person
                    }}</label>
                    <input v-model="form.contact_person" class="form-control" />
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
                    <tr v-for="(item, i) in form.items" :key="i">
                        <td>
                            <input
                                v-model="item.date"
                                type="date"
                                class="form-control"
                            />
                        </td>
                        <td>
                            <input
                                v-model="item.content"
                                class="form-control"
                            />
                        </td>
                        <td>
                            <input
                                v-model="item.waste_type"
                                class="form-control"
                            />
                        </td>
                        <td>
                            <input
                                v-model.number="item.weight"
                                type="number"
                                class="form-control"
                            />
                        </td>
                        <td>
                            <input
                                v-model.number="item.price_per_ton"
                                type="number"
                                class="form-control"
                            />
                        </td>
                        <td>
                            <input
                                v-model.number="item.extra_charge"
                                type="number"
                                class="form-control"
                            />
                        </td>
                        <td>
                            <input
                                v-model="item.remarks"
                                class="form-control"
                            />
                        </td>
                        <td>
                            <button
                                @click="removeRow(i)"
                                class="btn btn-danger"
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
                        {{ translations.update }}
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
    components: { vSelect },

    // 👇 props ko accept karna zaroori hai
    props: {
        quotation: {
            type: Object,
            required: true
        },
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
            form: { ...this.quotation } // 👈 prop ka clone bana liya taake reactive ho
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
        removeRow(i) {
            this.form.items.splice(i, 1);
        },
        async updateForm() {
            try {
                let response = await axios.put(
                    this.base_url + "quotations/" + this.form.id,
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

<template>
    <div class="container">
        <div class="card col-md-11">
            <div class="card-header row">
                <div class="col-md-6">
                    <h4>{{ translations.title }}</h4>
                </div>
                <div class="col-md-6">
                    <a :href="base_url+'quotations'"
                       class="btn btn-success btn-xs float-right">
                        {{ translations.back }}
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div v-if="quotation">
                    <p><strong>{{ translations.customer }}:</strong> {{ quotation.customer.name }}</p>
                    <p><strong>{{ translations.project }}:</strong> {{ quotation.project }}</p>
                    <p><strong>{{ translations.contact_person }}:</strong> {{ quotation.contact_person }}</p>
                    <p><strong>{{ translations.date }}:</strong> {{ quotation.date }}</p>
                    <p><strong>{{ translations.total }}:</strong> {{ quotation.total }}</p>

                    <hr />
                    <h4>{{ translations.items }}</h4>
                    <table
                        class="table table-bordered"
                        v-if="quotation.items && quotation.items.length"
                    >
                        <thead>
                            <tr>
                                <th>{{ translations.date }}</th>
                                <th>{{ translations.content }}</th>
                                <th>{{ translations.waste_type }}</th>
                                <th>{{ translations.weight }}</th>
                                <th>{{ translations.price_per_ton }}</th>
                                <th>{{ translations.extra_charge }}</th>
                                <th>{{ translations.amount }}</th>
                                <th>{{ translations.remarks }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in quotation.items" :key="item.id">
                                <td>{{ item.date }}</td>
                                <td>{{ item.content }}</td>
                                <td>{{ item.waste_type }}</td>
                                <td>{{ item.weight }}</td>
                                <td>{{ item.price_per_ton }}</td>
                                <td>{{ item.extra_charge }}</td>
                                <td>{{ item.amount }}</td>
                                <td>{{ item.remarks }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-else>
                    Loading...
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['quotation','translations','common'],
    data() {
        return {
            base_url: APP_URL + "/", // same as container-supplier
        };
    }
};
</script>

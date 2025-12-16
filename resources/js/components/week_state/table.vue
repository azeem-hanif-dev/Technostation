<template>
    <div>
        <button @click="addTable" class="btn btn-primary mb-3">Add New Table</button>

        <div v-for="(table, index) in tables" :key="index">
            <h4>Table {{ index + 1 }}</h4>
            <table :id="'table-' + index" class="display">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(user, i) in table.data" :key="i">
                    <td>{{ user.id }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            tables: [],
            defaultUsers: [
                { id: 1, name: "John Doe", email: "john@example.com" },
                { id: 2, name: "Jane Doe", email: "jane@example.com" }
            ]
        };
    },
    methods: {
        addTable() {
            // Add a new table object
            const newIndex = this.tables.length;
            this.tables.push({
                id: `table-${newIndex}`,
                data: JSON.parse(JSON.stringify(this.defaultUsers)) // Clone data
            });

            // Wait for Vue to update DOM, then initialize DataTable
            this.$nextTick(() => {
                $(`#table-${newIndex}`).DataTable();
            });
        }
    }
};
</script>

<style>
/* Optional styling */
.btn {
    display: block;
    margin-bottom: 10px;
}
</style>

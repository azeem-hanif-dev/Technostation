<template>
    <div class="dropdown">
        <input type="text" v-model="searchQuery" @input="filterItems" placeholder="Search...">
        <ul class="dropdown-content">
            <li v-for="item in filteredItems" :key="item.id" @click="selectItem(item)">{{ item.name }}</li>
        </ul>
    </div>
</template>

<script>
export default {
    props: {
        staffList: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            searchQuery: ''
        };
    },
    computed: {
        filteredItems() {
            return this.staffList.filter(item => {
                // Convert item to string if it's not already a string
                const itemString = typeof item === 'string' ? item : String(item);
                return itemString.toLowerCase().includes(this.searchQuery.toLowerCase());
            });
        },
    },
    methods: {
        filterItems() {
            // Filter items based on search query
        },
        selectItem(item) {
            this.$emit('item-selected', item); // Emit event with selected item
            this.searchQuery = ''; // Clear search query after selecting an item
        },
    },
};
</script>

<style scoped>
/* Your component's styles */
</style>

<template>
    <div class="multiselect">
        <label>{{ label }}</label>
        <div class="options">
            <div
                v-for="option in options"
                :key="option.id"
                @click="toggleOption(option)"
                :class="{ 'selected': isSelected(option) }"
            >
                {{ option.name }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        label: String,
        options: Array,
        value: Array,
    },
    data() {
        return {
            selectedOptions: this.value,
        };
    },
    methods: {
        toggleOption(option) {
            const index = this.selectedOptions.indexOf(option.id);
            if (index === -1) {
                this.selectedOptions.push(option.id);
            } else {
                this.selectedOptions.splice(index, 1);
            }
        },
        isSelected(option) {
            return this.selectedOptions.includes(option.id);
        },
    },
    watch: {
        selectedOptions: function () {
            this.$emit('input', this.selectedOptions);
        },
    },
};
</script>

<style scoped>
.multiselect {
    display: inline-block;
    margin-right: 20px;
}

.options {
    display: flex;
    flex-direction: column;
    width: 100%;
}

.options > div {
    cursor: pointer;
    padding: 2px;
    margin: 2px;
    background-color: #f0f0f0;
    border: 1px solid #ddd;
}

.options > div.selected {
    background-color: #007BFF;
    color: white;
}
</style>

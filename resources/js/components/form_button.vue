<template>
    <button :class="classes" @click="onClick">
        <slot></slot>
    </button>
</template>

<script>
export default {
    props: {
        variant: {
            type: String,
            default: 'primary',
        },
        disabled: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        classes() {
            return `button button--${this.variant} ${this.disabled ? 'button--disabled' : ''}`;
        },
    },
    methods: {
        onClick() {
            if (!this.disabled) {
                this.$emit('click');
            }
        },
    },
};
</script>

<style>
.button {
    border-radius: 4px;
    padding: 8px 16px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
}

.button--primary {
    background-color: #0077ff;
    color: #fff;
}

.button--secondary {
    background-color: #fff;
    color: #0077ff;
    border: 2px solid #0077ff;
}

.button--disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>

<script setup>
import { computed } from 'vue';

defineEmits(['update:modelValue']);

const props = defineProps({
    value: {
        type: String,
        default: null,
    },
    name: {
        type: String,
    },
});
</script>


<template>
    <input
        type="radio"
        :value="value"
        :name="name"
        @click="$emit('update:modelValue', $event.target.value)"
        class="border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
    >
</template>

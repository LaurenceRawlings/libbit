<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import { ref } from 'vue';

const props = defineProps({
    list: {
        type: Array,
        required: true,
    },
    modelValue: {
        type: Array,
        default: () => [],
    },
});

const open = ref(false);

const emit = defineEmits(['update:modelValue']);

const selected = ref(props.modelValue);

const toggle = (item) => {
    if (selected.value.includes(item)) {
        selected.value = selected.value.filter((i) => i !== item);
    } else {
        selected.value.push(item);
    }

    emit('update:modelValue', selected.value);
};
</script>

<template>
    <div class="relative h-full">
        <button class="border border-gray-300 focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50 sm:rounded-r-md shadow-sm bg-white px-3 py-1 w-full h-full text-left" @click="open = !open">
            <slot></slot>
        </button>

        <!-- Full Screen Dropdown Overlay -->
        <div v-show="open" class="fixed inset-0 z-40" @click="open = false" />

        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div
                v-show="open"
                class="absolute z-50 mt-2 rounded-l-md sm:rounded-r-md shadow-xl origin-top-left left-0 bg-white w-full border border-gray-300 overflow-x-hidden max-h-32 overflow-y-scroll"
                style="display: none;"
            >
                <div class="flex flex-col divide-y">
                    <div v-for="(item, i) in props.list" :key="i" class="flex items-center px-4 py-2">
                        <Checkbox @update:modelValue="toggle(item)"/>
                        <span class="ml-2">{{ item }}</span>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

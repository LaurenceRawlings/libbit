<script setup>
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { ref } from "vue";

const props = defineProps({
    tabNames: {
        type: Array,
        required: true,
    },
});

const currentTab = ref(0);
</script>

<template>
    <div>
        <div class="w-full flex overflow-hidden">
            <SecondaryButton v-for="(name, i) in tabNames" @click="currentTab = i"
                :class="{'opacity-50': currentTab !== i, 'font-bold': currentTab === i, 'sm:rounded-tl-lg': i === 0, 'rounded-tr-lg': i === tabNames.length - 1}"
                class="rounded-b-none rounded-t-none border-b-0">
                {{ name }}
            </SecondaryButton>
            <slot name="buttons"></slot>
        </div>

        <template v-for="(name, i) in tabNames">
            <div v-if="currentTab === i" :key="i"
                class="w-full h-full sm:rounded-b-lg sm:rounded-tr-lg border border-gray-300 overflow-hidden bg-white shadow-xl">
                <slot :name="i + 1"></slot>
            </div>
        </template>
    </div>
</template>

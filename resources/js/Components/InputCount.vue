<script setup>
const props = defineProps({
    current: Number,
    max: Number,
});
</script>

<template>
    <span class="bg-green-600 text-white rounded-full px-2 text-sm">
        {{ props.current }} / {{ props.max }}
    </span>
</template>

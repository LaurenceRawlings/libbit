<script setup>
import { ref } from 'vue';

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(['update:modelValue']);

const tags = ref(props.modelValue);
const tagInput = ref(null);
const searchResults = ref([]);

const addTag = (tag) => {
    if (!tags.value.includes(tag)) {
        tags.value.push(tag);
    }

    tagInput.value.value = null;
    searchResults.value = [];
};

const removeTag = (tag) => {
    tags.value = tags.value.filter((t) => t !== tag);
};

const emitTags = () => {
    emit('update:modelValue', tags.value);
};

const _searchTags = (query) => {
    if (query.length < 3 || query.includes(' ')) {
        searchResults.value = [];
        return;
    }

    axios
        .get(`/api/tags/${query}`)
        .then((response) => {
            searchResults.value = response.data.filter((tag) => !tags.value.includes(tag.name));
        });
};

const searchTags = _.throttle(_searchTags, 1000);

const handleTagAdded = (tag) => {
    tag.split(' ').forEach((t) => {
        if (t) {
            addTag(t);
        }
    });

    emitTags();
};

const handleTagRemoved = (tag) => {
    removeTag(tag);
    emitTags();
};

</script>

<template>
    <div class="border border-gray-300 rounded-md shadow-sm flex flex-wrap p-2">
        <div class="rounded-2xl bg-green-600 text-white m-1 flex items-center" v-for="tag in tags" :key="tag">
            <div class="mx-2">
                {{tag}}
            </div>
            <div @click="handleTagRemoved(tag)" class="w-6 h-6 p-1 rounded-full bg-green-700 hover:bg-green-800 cursor-pointer">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z" fill="currentColor"/></svg>
            </div>
        </div>
        <input ref="tagInput" @keydown.space="handleTagAdded($event.target.value)" @input="searchTags($event.target.value)" type="text" class="border-gray-300 focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50 rounded-md shadow-sm w-full my-1" />

        <div class="w-full pr-8 sm:pr-16">
            <div v-if="searchResults.length > 0" class="border border-gray-300 rounded-md shadow-sm">
                <div v-for="result in searchResults" :key="result" class="p-2 border-b border-gray-300 hover:bg-gray-100 cursor-pointer" @click="handleTagAdded(result.name)">
                    {{result.name}}
                </div>
            </div>
        </div>
    </div>
</template>

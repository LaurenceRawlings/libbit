<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import { hasPermission } from '@/Shared/permissions.js';
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ProjectCard from '@/Pages/Projects/Partials/ProjectCard.vue';
import Paginator from '@/Components/Paginator.vue';
import MultiSelect from '@/Components/MultiSelect.vue';
import { ref, onMounted } from 'vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    projects: Object,
    search: String,
    tags: Array,
});

const tags = ref([])

onMounted(() => {
    axios
        .get(`/api/tags`)
        .then((response) => {
            tags.value = response.data.map((tag) => tag.name);
        });
});

const filters = ref({
    search: props.search,
    tags: props.tags,
});

const _search = () => {
    let data = {
        search: filters.value.search,
        tags: filters.value.tags.join(','),
    }

    if (data.tags === '') {
        delete data.tags;
    }

    if (data.search === '') {
        delete data.search;
    }

    Inertia.get(route('projects.index'), data);
}

const search = _.debounce(_search, 1000);
</script>

<template>
    <AppLayout title="Projects">
        <template #header>
            <div class="flex items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Projects
                </h2>

                <Link class="ml-auto" v-if="hasPermission('create')" :href="route('projects.create')">
                    <PrimaryButton>
                        New
                    </PrimaryButton>
                </Link>
            </div>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div class="flex mb-6 h-12">
                    <TextInput class="rounded-none sm:rounded-l-md border-r-0 w-1/2" v-model="filters.search" placeholder="Search projects..." type="text" @input="search" autofocus />

                    <MultiSelect
                        class="mb-4 w-1/2"
                        :list="tags"
                        v-model="filters.tags">
                        <div v-if="filters.tags.length == 0" class="text-gray-500">Filter by tag...</div>

                        <div class="flex items-center overflow-hidden" v-else>
                            <div class="text-sm text-white mr-2 rounded-2xl bg-green-600 px-2 m-1 whitespace-nowrap" v-for="tag in filters.tags" :key="tag">
                                {{ tag }}
                            </div>
                        </div>
                    </MultiSelect>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-6">
                    <ProjectCard v-for="project in props.projects.data" :key="project.id" :project="project" />
                </div>

                <div class="grid place-items-center w-full mt-6">
                    <Paginator :links="props.projects.links" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

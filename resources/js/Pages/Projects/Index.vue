<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { hasPermission } from '@/Shared/permissions.js';
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ProjectCard from '@/Pages/Projects/Partials/ProjectCard.vue';
import Paginator from '@/Components/Paginator.vue';
import MultiSelect from '@/Components/MultiSelect.vue';
import { ref } from 'vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    projects: Object,
});

const filters = ref({
    search: '',
    tags: [],
});
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
                    <TextInput class="rounded-none sm:rounded-l-md border-r-0 w-1/2" v-model="filters.search" placeholder="Search projects..." type="text" />

                    <MultiSelect
                        class="mb-4 w-1/2"
                        :list="['All', 'Mine', 'Starred', 'Test', 'Yeah-Yeah']"
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

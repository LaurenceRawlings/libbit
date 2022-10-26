<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ProjectCard from '@/Pages/Projects/Partials/ProjectCard.vue';
import ResourceCard from '@/Pages/Projects/Partials/ResourceCard.vue';
import { ref } from 'vue';

const props = defineProps({
    projects: Object,
    resources: Object,
});

const tab = ref(0);
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto sm:px-6 py-10 lg:px-8">
                <div class="flex mb-6">
                    <div class="w-1/2 rounded-l-none sm:rounded-l-md rounded-r-none cursor-pointer border border-gray-300 shadow-sm bg-white text-center py-1" :class="{'bg-green-600 text-white': tab == 0}" @click="tab = 0">
                        Pinned Projects
                    </div>
                    <div class="w-1/2 rounded-r-none sm:rounded-r-md rounded-l-none cursor-pointer border border-gray-300 shadow-sm bg-white text-center py-1" :class="{'bg-green-600 text-white': tab == 1}" @click="tab = 1">
                        Pinned Resources
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-6">
                    <template v-if="tab == 0">
                        <ProjectCard v-for="project in props.projects" :key="project.id" :project="project" />
                    </template>
                    <template v-if="tab == 1">
                        <ResourceCard v-for="resource in props.resources" :key="resource.id" :resource="resource" :project="{id: resource.project_id}" />
                    </template>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { hasPermission } from '@/Shared/permissions.js';
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ProjectCard from '@/Pages/Projects/Partials/ProjectCard.vue';

const props = defineProps({
    projects: Array,
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
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-6">
                    <ProjectCard v-for="project in props.projects" :key="project.id" :project="project" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

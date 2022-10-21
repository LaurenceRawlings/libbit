<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { hasPermission } from '@/Shared/permissions.js';
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    projects: Array,
});
</script>

<template>
    <AppLayout title="Projects">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Projects
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Link v-if="hasPermission('create')" :href="route('projects.create')">
                    <PrimaryButton>
                        Create Project
                    </PrimaryButton>
                </Link>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <ul>
                        <li v-for="project in props.projects" :key="project.id">
                            <Link :href="route('projects.show', project.id)">
                                {{project.name}}
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

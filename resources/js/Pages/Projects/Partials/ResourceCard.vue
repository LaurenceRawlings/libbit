<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { hasPermission } from '@/Shared/permissions.js';

const props = defineProps({
    project: Object,
    resource: Object,
});

</script>

<template>
    <template v-if="props.resource.type == 'link'">
        <a :href="props.resource.content" rel="noopener noreferrer" target="_blank">
            <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-md flex items-center">
                <div>
                    {{ resource.name }}
                </div>
                <Link class="ml-auto rounded-full bg-gray-200 text-gray-400 p-1" v-if="hasPermission('update')" :href="route('projects.resources.edit', [props.project.id, props.resource.id])">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 512">
                        <path d="M64 360c30.9 0 56 25.1 56 56s-25.1 56-56 56s-56-25.1-56-56s25.1-56 56-56zm0-160c30.9 0 56 25.1 56 56s-25.1 56-56 56s-56-25.1-56-56s25.1-56 56-56zM120 96c0 30.9-25.1 56-56 56S8 126.9 8 96S33.1 40 64 40s56 25.1 56 56z" fill="currentColor"/>
                    </svg>
                </Link>
            </div>
        </a>
    </template>
    <template v-else>
        <Link :href="route('projects.resources.show', [props.project.id, resource.id])">
            <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-md">
                {{ resource.name }}
            </div>
        </Link>
    </template>
</template>

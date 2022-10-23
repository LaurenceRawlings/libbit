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
                <svg class="w-4 h-4 mr-4 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M288 32c-17.7 0-32 14.3-32 32s14.3 32 32 32h50.7L169.4 265.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L384 141.3V192c0 17.7 14.3 32 32 32s32-14.3 32-32V64c0-17.7-14.3-32-32-32H288zM80 64C35.8 64 0 99.8 0 144V400c0 44.2 35.8 80 80 80H336c44.2 0 80-35.8 80-80V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v80c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16V144c0-8.8 7.2-16 16-16h80c17.7 0 32-14.3 32-32s-14.3-32-32-32H80z" fill="currentColor"/></svg>

                <div>
                    {{ resource.name }}
                </div>
                <Link class="ml-auto rounded-full p-1 border border-gray-300 shadow-sm text-gray-700 bg-white hover:bg-gray-50" v-if="hasPermission('update')" :href="route('projects.resources.edit', [props.project.id, props.resource.id])">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 512">
                        <path d="M64 360c30.9 0 56 25.1 56 56s-25.1 56-56 56s-56-25.1-56-56s25.1-56 56-56zm0-160c30.9 0 56 25.1 56 56s-25.1 56-56 56s-56-25.1-56-56s25.1-56 56-56zM120 96c0 30.9-25.1 56-56 56S8 126.9 8 96S33.1 40 64 40s56 25.1 56 56z" fill="currentColor"/>
                    </svg>
                </Link>
            </div>
        </a>
    </template>
    <template v-else>
        <Link :href="route('projects.resources.show', [props.project.id, resource.id])">
            <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-md flex items-center">
                <svg class="w-4 h-4 mr-4 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M96 0C43 0 0 43 0 96V416c0 53 43 96 96 96H384h32c17.7 0 32-14.3 32-32s-14.3-32-32-32V384c17.7 0 32-14.3 32-32V32c0-17.7-14.3-32-32-32H384 96zm0 384H352v64H96c-17.7 0-32-14.3-32-32s14.3-32 32-32zm32-240c0-8.8 7.2-16 16-16H336c8.8 0 16 7.2 16 16s-7.2 16-16 16H144c-8.8 0-16-7.2-16-16zm16 48H336c8.8 0 16 7.2 16 16s-7.2 16-16 16H144c-8.8 0-16-7.2-16-16s7.2-16 16-16z" fill="currentColor"/></svg>

                <div>
                    {{ resource.name }}
                </div>
            </div>
        </Link>
    </template>
</template>

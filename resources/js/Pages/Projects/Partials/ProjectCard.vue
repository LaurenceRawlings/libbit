<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import Pin from '@/Components/Pin.vue';

const props = defineProps({
    project: Object,
});

</script>

<template>
    <Link :href="route('projects.show', project.id)">
        <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-md flex items-center">
            <div class="text-green-600 font-black mr-4">/</div>
            <div>
                {{ project.name }}
            </div>
            <Pin :pinableId="props.project.id" :pinned="props.project.is_pinned" pinableType="project" class="text-green-600 ml-auto"/>
        </div>
    </Link>
</template>

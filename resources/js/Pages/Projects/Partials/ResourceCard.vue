<script setup>
import { Link } from '@inertiajs/inertia-vue3';

const props = defineProps({
    project: Object,
    resource: Object,
});

</script>

<template>
    <Link :href="route('projects.resources.show', [props.project.id, resource.id])">
        <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-md">
            {{ resource.name }}
        </div>
    </Link>
</template>

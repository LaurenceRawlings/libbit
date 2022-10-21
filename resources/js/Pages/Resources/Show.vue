<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

const props = defineProps({
    project: Object,
    resource: Object,
});

const confirmingResourceDeletion = ref(false);
const form = useForm();

const confirmResourceDeletion = () => {
    confirmingResourceDeletion.value = true;
};

const deleteResource = () => {
    form.delete(route('projects.resources.destroy', [props.project.id, props.resource]), {
        errorBag: 'deleteResource',
    });
};
</script>

<template>
    <AppLayout :title="props.resource.name">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{props.resource.name}}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Link :href="route('projects.resources.edit', [props.project.id, props.resource.id])">
                    <PrimaryButton>
                        Edit Resource
                    </PrimaryButton>
                </Link>
                <DangerButton @click="confirmResourceDeletion">
                    Delete Resource
                </DangerButton>
                <ConfirmationModal :show="confirmingResourceDeletion" @close="confirmingResourceDeletion = false">
                    <template #title>
                        Delete Resource
                    </template>

                    <template #content>
                        Are you sure you want to delete this resource? Once a resource is deleted, all of its resources and data will be permanently deleted.
                    </template>

                    <template #footer>
                        <SecondaryButton @click="confirmingResourceDeletion = false">
                            Cancel
                        </SecondaryButton>

                        <DangerButton
                            class="ml-3"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            @click="deleteResource"
                        >
                            Delete Resource
                        </DangerButton>
                    </template>
                </ConfirmationModal>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                </div>
            </div>
        </div>
    </AppLayout>
</template>

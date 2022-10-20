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
});

const confirmingProjectDeletion = ref(false);
const form = useForm();

const confirmProjectDeletion = () => {
    confirmingProjectDeletion.value = true;
};

const deleteProject = () => {
    form.delete(route('projects.destroy', props.project), {
        errorBag: 'deleteProject',
    });
};
</script>

<template>
    <AppLayout :title="props.project.name">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{props.project.name}}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Link :href="route('projects.edit', props.project.id)">
                    <PrimaryButton>
                        Edit Project
                    </PrimaryButton>
                </Link>
                <DangerButton @click="confirmProjectDeletion">
                    Delete Project
                </DangerButton>
                <ConfirmationModal :show="confirmingProjectDeletion" @close="confirmingProjectDeletion = false">
                    <template #title>
                        Delete Project
                    </template>

                    <template #content>
                        Are you sure you want to delete this project? Once a project is deleted, all of its resources and data will be permanently deleted.
                    </template>

                    <template #footer>
                        <SecondaryButton @click="confirmingProjectDeletion = false">
                            Cancel
                        </SecondaryButton>

                        <DangerButton
                            class="ml-3"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            @click="deleteProject"
                        >
                            Delete Project
                        </DangerButton>
                    </template>
                </ConfirmationModal>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                </div>
            </div>
        </div>
    </AppLayout>
</template>

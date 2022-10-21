<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import ActionSection from '@/Components/ActionSection.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';

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
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingResourceDeletion.value = false;

    form.reset();
};
</script>

<template>
    <ActionSection>
        <template #title>
            Delete Resource
        </template>

        <template #description>
            Permanently delete this resource.
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                Once this resource is deleted, all of its resources and data will be permanently deleted. Before deleting this resource, please download any data or information that you wish to retain.
            </div>

            <div class="mt-5">
                <DangerButton @click="confirmResourceDeletion">
                    Delete Resource
                </DangerButton>
            </div>

            <!-- Delete Resource Confirmation Modal -->
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
        </template>
    </ActionSection>
</template>

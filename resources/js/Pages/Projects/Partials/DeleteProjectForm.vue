<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import ActionSection from '@/Components/ActionSection.vue';
import DangerButton from '@/Components/DangerButton.vue';
import DialogModal from '@/Components/DialogModal.vue';
import InputError from '@/Components/InputError.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    project: Object,
});

const confirmingProjectDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmProjectDeletion = () => {
    confirmingProjectDeletion.value = true;

    setTimeout(() => passwordInput.value.focus(), 250);
};

const deleteProject = () => {
    if (props.project.name !== form.password) {
        form.errors.password = 'The project name does not match.';

        return;
    }

    form.delete(route('projects.destroy', props.project), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingProjectDeletion.value = false;

    form.reset();
};
</script>

<template>
    <ActionSection>
        <template #title>
            Delete Project
        </template>

        <template #description>
            Permanently delete this project.
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                Once this project is deleted, all of its resources and data will be permanently deleted. Before deleting this project, please download any data or information that you wish to retain.
            </div>

            <div class="mt-5">
                <DangerButton @click="confirmProjectDeletion">
                    Delete Project
                </DangerButton>
            </div>

            <!-- Delete Project Confirmation Modal -->
            <DialogModal :show="confirmingProjectDeletion" @close="closeModal">
                <template #title>
                    Delete Project
                </template>

                <template #content>
                    Are you sure you want to delete this project? Once this project is deleted, all of its resources and data will be permanently deleted. Please enter the project name to confirm you would like to permanently delete this project.

                    <div class="mt-4">
                        <TextInput
                            ref="passwordInput"
                            v-model="form.password"
                            type="text"
                            class="mt-1 block w-3/4"
                            :placeholder="props.project.name"
                            @keyup.enter="deleteProject"
                        />

                        <InputError :message="form.errors.password" class="mt-2" />
                    </div>
                </template>

                <template #footer>
                    <SecondaryButton @click="closeModal">
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
            </DialogModal>
        </template>
    </ActionSection>
</template>

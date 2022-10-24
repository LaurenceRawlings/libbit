<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import { computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import FormSection from '@/Components/FormSection.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import DeleteProjectForm from '@/Pages/Projects/Partials/DeleteProjectForm.vue';
import { hasPermission } from '@/Shared/permissions.js';
import Breadcrumbs from '@/Components/Breadcrumbs.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Link } from '@inertiajs/inertia-vue3';
import TagEditor from '@/Components/TagEditor.vue';

const props = defineProps({
    project: Object,
});

const form = useForm({
    name: props.project ? props.project.name : '',
    tags: props.project ? props.project.tags.map((tag) => tag.name) : [],
});

const submit = () => {
    if (props.project) {
        form.put(route('projects.update', props.project.id), {
            preserveScroll: true,
        });
    } else {
        form.post(route('projects.store'), {
            preserveScroll: true,
        });
    }
};

const action = computed(() => {
  return props.project ? 'Update' : 'Create'
})

const tagError = computed(() => {
    const keys = Object.keys(form.errors).filter((key) => key.startsWith('tags.'));
    return keys ? form.errors[keys[0]] : null;
});
</script>

<template>
    <AppLayout :title="`${action} Project`">
        <template #header>
            <Breadcrumbs class="font-semibold text-xl text-gray-800 leading-tight" :crumbs="[
                    { title: 'Projects', link: route('projects.index') },
                ]" :title="props.project ? props.project.name : (form.name ? form.name : 'New Project')" />
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <FormSection @submitted="submit">
                    <template #title>
                        {{action}} Project
                    </template>

                    <template #description>
                        <template v-if="props.project">
                            Update the project details.
                        </template>
                        <template v-else>
                            Create a new project to store your resources.
                        </template>
                    </template>

                    <template #form>
                        <!-- Project Name -->
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="name" value="Name" />
                            <TextInput
                                id="name"
                                type="text"
                                v-model="form.name"
                                class="mt-1 block w-full"
                                required
                            />
                            <InputError :message="form.errors.name" class="mt-2" />
                        </div>

                        <!-- Project Tags -->
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="tags">
                                Tags
                                <span class="ml-1 text-gray-400">(separate with spaces)</span>
                            </InputLabel>
                            <TagEditor id="tags" v-model="form.tags" />
                            <InputError :message="tagError" class="mt-2" />
                        </div>
                    </template>

                    <template #actions>
                        <ActionMessage :on="form.recentlySuccessful" class="mr-3">
                            {{action}}ed.
                        </ActionMessage>

                        <Link class="mr-3" :href="props.project ? route('projects.show', project.id) : route('projects.index')">
                            <SecondaryButton>
                                Cancel
                            </SecondaryButton>
                        </Link>

                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            {{action}}
                        </PrimaryButton>
                    </template>
                </FormSection>

                <template v-if="props.project && hasPermission('delete')">
                    <SectionBorder />

                    <DeleteProjectForm class="mt-10 sm:mt-0" :project="props.project" />
                </template>
            </div>
        </div>
    </AppLayout>
</template>

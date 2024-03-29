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
import DeleteResourceForm from '@/Pages/Resources/Partials/DeleteResourceForm.vue';
import { hasPermission } from '@/Shared/permissions.js';
import Breadcrumbs from '@/Components/Breadcrumbs.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Link } from '@inertiajs/inertia-vue3';
import TagEditor from '@/Components/TagEditor.vue';

const props = defineProps({
    project: Object,
    resource: Object,
});

const form = useForm({
    name: props.resource ? props.resource.name : '',
    type: props.resource ? props.resource.type : 'link',
    link: props.resource ? props.resource.link : '',
    tags: props.resource ? props.resource.tags.map((tag) => tag.name) : [],
});

const submit = () => {
    if (props.resource) {
        form.put(route('projects.resources.update', [props.project.id, props.resource.id]), {
            preserveScroll: true,
        });
    } else {
        form.post(route('projects.resources.store', props.project.id), {
            preserveScroll: true,
        });
    }
};

const action = computed(() => {
  return props.resource ? 'Update' : 'Create'
})

const types = [
    { value: 'link', name: 'Link', description: 'A link to a website or document.' },
    { value: 'note', name: 'Note', description: 'A note or description.' },
];


const tagError = computed(() => {
    const keys = Object.keys(form.errors).filter((key) => key.startsWith('tags.'));
    return keys ? form.errors[keys[0]] : null;
});
</script>

<template>
    <AppLayout :title="`${action} Resource`">
        <template #header>
            <Breadcrumbs class="font-semibold text-xl text-gray-800 leading-tight" :crumbs="[
                    { title: 'Projects', link: route('projects.index') },
                    { title: props.project.name, link: route('projects.show', props.project.id) },
                ]" :title="props.resource ? props.resource.name : (form.name ? form.name : 'New Resource')" />
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <FormSection @submitted="submit">
                    <template #title>
                        {{action}} Resource
                    </template>

                    <template #description>
                        <template v-if="props.resource">
                            Update the resource details.
                        </template>
                        <template v-else>
                            Create a new resource to your project to help you and your team stay organized.
                        </template>
                    </template>

                    <template #form>
                        <!-- Resource Name -->
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

                        <!-- Type -->
                        <div class="col-span-6 lg:col-span-4">
                            <InputLabel for="types" value="Resource Type" />
                            <InputError :message="form.errors.type" class="mt-2" />

                            <div class="relative z-0 mt-1 border border-gray-200 rounded-lg cursor-pointer">
                                <button
                                    v-for="(type, i) in types"
                                    :key="type.value"
                                    type="button"
                                    class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-green-300 focus:ring focus:ring-green-200"
                                    :class="{'border-t border-gray-200 rounded-t-none': i > 0, 'rounded-b-none': i != Object.keys(types).length - 1}"
                                    @click="form.type = type.value"
                                >
                                    <div :class="{'opacity-50': form.type && form.type != type.value}">
                                        <!-- Type Name -->
                                        <div class="flex items-center">
                                            <div class="text-sm text-gray-600" :class="{'font-semibold': form.type == type.value}">
                                                {{ type.name }}
                                            </div>

                                            <svg
                                                v-if="form.type == type.value"
                                                class="ml-2 h-5 w-5 text-green-400"
                                                fill="none"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            ><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </div>

                                        <!-- Type Description -->
                                        <div class="mt-2 text-xs text-gray-600 text-left">
                                            {{ type.description }}
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <!-- Resource Link -->
                        <div v-if="form.type == 'link'" class="col-span-6 sm:col-span-4">
                            <InputLabel for="link" value="Link" />
                            <TextInput
                                id="link"
                                type="text"
                                v-model="form.link"
                                class="mt-1 block w-full"
                                required
                            />
                            <InputError :message="form.errors.link" class="mt-2" />
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

                        <Link class="mr-3" :href="route('projects.show', project.id)">
                            <SecondaryButton>
                                Cancel
                            </SecondaryButton>
                        </Link>

                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            {{action}}
                        </PrimaryButton>
                    </template>
                </FormSection>

                <template v-if="props.resource && hasPermission('delete')">
                    <SectionBorder />

                    <DeleteResourceForm class="mt-10 sm:mt-0" :project="props.project" :resource="props.resource" />
                </template>
            </div>
        </div>
    </AppLayout>
</template>

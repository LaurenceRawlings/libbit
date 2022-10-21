<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { computed, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Radio from '@/Components/Radio.vue';

onMounted(() => {
    if (props.resource) {
        form.name = props.resource.name;
        form.type = props.resource.type;
        form.content = props.resource.content;
    }
});

const props = defineProps({
    project: Object,
    resource: Object,
});

const form = useForm({
    name: '',
    type: 'link',
    content: '',
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
</script>

<template>
    <AppLayout :title="`${action} Resource`">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{action}} Resource
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form @submit.prevent="submit">
                        <div>
                            <InputLabel for="name" value="Name" />
                            <TextInput
                                id="name"
                                v-model="form.name"
                                class="mt-1 block w-full"
                                required
                                autofocus
                            />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div>
                            <InputLabel for="type" value="Resource Type" />
                            <div id="type">
                                <InputLabel for="type-link" value="Link" class="inline" />
                                <Radio
                                    id="type-link"
                                    name="type"
                                    v-model="form.type"
                                    value="link"
                                    label="Link"
                                    checked />

                                <InputLabel for="type-note" value="Note" class="inline" />
                                <Radio
                                    id="type-note"
                                    name="type"
                                    v-model="form.type"
                                    value="note"
                                    label="Note"
                                    :checked="props.resource && props.resource.type == 'note'" />
                            </div>
                            <InputError class="mt-2" :message="form.errors.type" />
                        </div>

                        <div>
                            <InputLabel for="content" value="Content" />
                            <TextInput
                                id="content"
                                v-model="form.content"
                                class="mt-1 block w-full"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.content" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                {{action}}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

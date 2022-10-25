<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { hasPermission } from '@/Shared/permissions.js';
import Breadcrumbs from '@/Components/Breadcrumbs.vue';
import TabView from '@/Components/TabView.vue';
import { computed, ref, onUnmounted } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import InputCount from '@/Components/InputCount.vue';

const props = defineProps({
    project: Object,
    resource: Object,
});

const channel = Echo.join(`resource.${props.resource.id}`);
const editors = ref([]);

onUnmounted(() => {
    Echo.leave(`resource.${props.resource.id}`);
});

channel
    .here(users => {
        editors.value = users;
    })
    .joining(user => {
        editors.value.push(user);
    })
    .leaving(user => {
        editors.value = editors.value.filter(u => u.id !== user.id);
    })
    .listenForWhisper('editing', (e) => {
        form.content = e.content;
    });

const form = useForm({
    name: props.resource.name,
    type: props.resource.type,
    content: props.resource.content,
    tags: props.resource.tags.map((tag) => tag.name),
});

const compiledMarkdown = computed(() => {
    return marked.parse(form.content ? form.content : '*head to the edit tab to start your note...*', {gfm: true});
});

const _updateContent = () => {
    form.put(route('projects.resources.update', [props.project.id, props.resource.id]), {
        preserveScroll: true,
    });
};

const updateContent = _.debounce(_updateContent, 3000);

const _editing = () => {
    channel.whisper('editing', {
        content: form.content,
    });
};

const editing = _.throttle(_editing, 200);
</script>

<template>
    <AppLayout :title="props.resource.name">
        <template #header>
            <div class="flex items-center">
                <Breadcrumbs class="font-semibold text-xl text-gray-800 leading-tight" :crumbs="[
                    { title: 'Projects', link: route('projects.index') },
                    { title: props.project.name, link: route('projects.show', props.project.id) },
                ]" :title="props.resource.name" />

                <Link v-if="hasPermission('update')" :href="route('projects.resources.edit', [props.project.id, props.resource.id])">
                    <svg class="ml-2 w-4 h-4 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.8 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z" fill="currentColor"/>
                    </svg>
                </Link>
            </div>

            <div v-if="props.resource.tags.length > 0" class="flex flex-wrap items-center mt-1">
                <span class="text-sm text-gray-400 mr-2">Tags:</span>
                <span v-for="tag in props.resource.tags" :key="tag.id" class="text-sm text-white mr-2 rounded-2xl bg-green-600 px-2 m-1">
                    {{ tag.name }}
                </span>
            </div>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <TabView :tab-names="hasPermission('update') ? ['View', 'Edit'] : ['View']">
                    <template #buttons>
                        <InputCount class="ml-3" :current="form.content ? form.content.length : 0" :max="10000"/>

                        <ActionMessage :on="form.recentlySuccessful" class="ml-3">
                            Saved.
                        </ActionMessage>

                        <div v-if="editors.length > 0" class="flex items-center ml-auto mr-1 sm:mr-0">
                            <img v-for="editor in editors" :key="editor.id" class="h-6 w-6 ml-1 rounded-full object-cover border border-white" :src="editor.profile_photo_url" :alt="editor.name" :title="editor.name"/>
                        </div>
                    </template>
                    <template #1>
                        <div class="max-w-full prose px-3 sm:px-24 py-8"
                            v-html="compiledMarkdown"></div>
                    </template>
                    <template #2 v-if="hasPermission('update')">
                        <div class="w-full h-full relative">
                            <textarea id="content"
                                @input="updateContent"
                                @keydown="editing"
                                class="w-full rounded-t-none rounded-b-none border-0 focus:outline-none focus:ring-0 font-mono -mb-2 bg-transparent"
                                v-model="form.content" rows="10" maxlength="10000"
                                style="height: 512px"
                                placeholder="type your note here, github flavoured markdown supported..."></textarea>
                        </div>
                    </template>
                </TabView>
            </div>
        </div>
    </AppLayout>
</template>

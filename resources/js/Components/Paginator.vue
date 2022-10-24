<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue'
import { Link } from '@inertiajs/inertia-vue3'

const props = defineProps({
    links: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <div v-if="props.links.length > 3">
        <div class="flex flex-wrap -mb-1">
            <template v-for="(link, i) in links" :key="i">
                <Link v-if="link.url !== null" :href="link.url">
                    <SecondaryButton class="mr-1" :class="{ 'active': link.active }" v-html="link.label"/>
                </Link>
            </template>
        </div>
    </div>
</template>

<style scoped>
.active {
    background: #16a34a;
    color: white;
    font-weight: bolder;
}
</style>

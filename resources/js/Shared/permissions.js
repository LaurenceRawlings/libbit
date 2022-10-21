import { usePage } from '@inertiajs/inertia-vue3'

export function hasPermission(permission) {
    const permissions = usePage().props.value.permissions;

    if (permissions.includes('*')) {
        return true;
    }

    return permissions.includes(permission);
}

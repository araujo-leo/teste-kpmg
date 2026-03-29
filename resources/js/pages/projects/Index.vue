<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Projects',
                href: '/projects',
            },
        ],
    },
});

interface Company {
    id: number;
    name: string;
}

interface User {
    id: number;
    name: string;
}

interface Project {
    id: number;
    code: string;
    name: string;
    status: string;
    priority: string;
    starts_at?: string;
    ends_at?: string;
    company?: Company;
    manager?: User;
}

const props = defineProps<{
    projects: {
        data: Project[];
        links: any[];
    };
}>();

const formatDate = (dateString?: string) => {
    if (!dateString) return '-';
    const date = new Date(dateString + 'T00:00:00');
    return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    }).format(date);
};

// Cores e Labels para o Status
const getStatusColor = (status: string) => {
    switch (status) {
        case 'not_started':
            return 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300';
        case 'in_progress':
            return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
        case 'completed':
            return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
        case 'paused':
            return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const getStatusLabel = (status: string) => {
    switch (status) {
        case 'not_started':
            return 'Not Started';
        case 'in_progress':
            return 'In Progress';
        case 'completed':
            return 'Completed';
        case 'paused':
            return 'Paused';
        default:
            return status;
    }
};

// Cores e Labels para a Prioridade
const getPriorityColor = (priority: string) => {
    switch (priority) {
        case 'high':
            return 'text-red-600 bg-red-100 dark:text-red-400 dark:bg-red-900/30';
        case 'medium':
            return 'text-orange-600 bg-orange-100 dark:text-orange-400 dark:bg-orange-900/30';
        case 'low':
            return 'text-green-600 bg-green-100 dark:text-green-400 dark:bg-green-900/30';
        default:
            return '';
    }
};
</script>

<template>
    <Head title="Projects" />

    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="mx-auto w-full max-w-7xl">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-xl leading-tight font-semibold text-foreground">
                    Projects
                </h2>
                <Link href="/projects/create">
                    <Button>Create New Project</Button>
                </Link>
            </div>

            <div
                class="overflow-hidden border border-border bg-background shadow-sm sm:rounded-lg"
            >
                <div class="overflow-x-auto">
                    <table
                        class="w-full text-left text-sm whitespace-nowrap text-muted-foreground"
                    >
                        <thead
                            class="border-b border-border bg-muted text-xs text-muted-foreground uppercase"
                        >
                            <tr>
                                <th scope="col" class="px-6 py-3">Project</th>
                                <th scope="col" class="px-6 py-3">Company</th>
                                <th scope="col" class="px-6 py-3">Manager</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Priority</th>
                                <th scope="col" class="px-6 py-3">Timeline</th>
                                <th scope="col" class="px-6 py-3 text-right">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="project in projects.data"
                                :key="project.id"
                                class="border-b border-border bg-background hover:bg-muted/50"
                            >
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span
                                            class="max-w-[200px] truncate font-medium text-foreground"
                                            :title="project.name"
                                        >
                                            {{ project.name }}
                                        </span>
                                        <span
                                            class="font-mono text-xs text-muted-foreground"
                                        >
                                            {{ project.code }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div
                                        class="max-w-[150px] truncate"
                                        :title="project.company?.name"
                                    >
                                        {{ project.company?.name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        v-if="project.manager"
                                        class="max-w-[150px] truncate"
                                    >
                                        {{ project.manager.name }}
                                    </span>
                                    <span
                                        v-else
                                        class="text-xs text-muted-foreground italic"
                                        >Unassigned</span
                                    >
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="rounded-full px-2.5 py-0.5 text-xs font-medium"
                                        :class="getStatusColor(project.status)"
                                    >
                                        {{ getStatusLabel(project.status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="rounded-md px-2.5 py-0.5 text-xs font-semibold capitalize"
                                        :class="
                                            getPriorityColor(project.priority)
                                        "
                                    >
                                        {{ project.priority }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <div class="flex flex-col gap-0.5">
                                        <span v-if="project.starts_at"
                                            >Start:
                                            {{
                                                formatDate(project.starts_at)
                                            }}</span
                                        >
                                        <span v-if="project.ends_at"
                                            >End:
                                            {{
                                                formatDate(project.ends_at)
                                            }}</span
                                        >
                                        <span
                                            v-if="
                                                !project.starts_at &&
                                                !project.ends_at
                                            "
                                            class="text-muted-foreground italic"
                                            >-</span
                                        >
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <Button
                                        variant="outline"
                                        size="sm"
                                        as-child
                                    >
                                        <Link :href="`/projects/${project.id}`">
                                            View
                                        </Link>
                                    </Button>
                                </td>
                            </tr>
                            <tr v-if="projects.data.length === 0">
                                <td
                                    colspan="7"
                                    class="px-6 py-8 text-center text-muted-foreground"
                                >
                                    No projects found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="projects.links && projects.links.length > 3"
                    class="border-t border-border bg-muted/20 px-6 py-4"
                >
                    <div class="flex flex-wrap items-center gap-1">
                        <template v-for="(link, i) in projects.links" :key="i">
                            <div
                                v-if="link.url === null"
                                class="rounded border bg-background px-3 py-1 text-sm text-muted-foreground opacity-50"
                                v-html="link.label"
                            />
                            <Link
                                v-else
                                :href="link.url"
                                class="rounded border px-3 py-1 text-sm focus:border-input focus:ring-2 focus:ring-ring focus:outline-none"
                                :class="[
                                    link.active
                                        ? 'border-primary bg-primary text-primary-foreground'
                                        : 'border-border bg-background text-foreground hover:bg-muted',
                                ]"
                                v-html="link.label"
                            />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

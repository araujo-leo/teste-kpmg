<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Projects',
                href: '/projects',
            },
            {
                title: 'Project Details',
                href: '#',
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

interface Ticket {
    id: number;
    title: string;
    status: string;
    created_at: string;
}

interface Project {
    id: number;
    code: string;
    name: string;
    description?: string;
    status: string;
    priority: string;
    starts_at?: string;
    ends_at?: string;
    created_at: string;
    updated_at: string;
    company?: Company;
    manager?: User;
    tickets?: Ticket[];
}

const props = defineProps<{
    project: Project;
}>();

const formatDate = (dateString?: string, isIso = false) => {
    if (!dateString) return '-';
    const date = isIso
        ? new Date(dateString)
        : new Date(dateString + 'T00:00:00');
    return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    }).format(date);
};

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

const getPriorityColor = (priority: string) => {
    switch (priority) {
        case 'high':
            return 'text-red-600 bg-red-100 border-red-200 dark:border-red-900/50 dark:text-red-400 dark:bg-red-900/30';
        case 'medium':
            return 'text-orange-600 bg-orange-100 border-orange-200 dark:border-orange-900/50 dark:text-orange-400 dark:bg-orange-900/30';
        case 'low':
            return 'text-green-600 bg-green-100 border-green-200 dark:border-green-900/50 dark:text-green-400 dark:bg-green-900/30';
        default:
            return '';
    }
};

const getTicketStatusColor = (status: string) => {
    switch (status) {
        case 'pending':
            return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
        case 'in_progress':
            return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
        case 'resolved':
            return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const deleteProject = () => {
    if (
        confirm(
            'Are you sure you want to delete this project? All associated tickets will be affected.',
        )
    ) {
        router.delete(`/projects/${props.project.id}`);
    }
};
</script>

<template>
    <Head :title="project.name" />

    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="mx-auto w-full max-w-4xl">
            <div class="mb-6 flex items-center justify-between">
                <div class="flex flex-col gap-1">
                    <div class="flex items-center gap-3">
                        <h2
                            class="text-2xl leading-tight font-semibold text-foreground"
                        >
                            {{ project.name }}
                        </h2>
                        <span
                            class="rounded-md border border-border bg-muted px-2 py-0.5 font-mono text-sm text-muted-foreground"
                        >
                            {{ project.code }}
                        </span>
                    </div>
                    <div class="mt-2 flex items-center gap-2">
                        <span
                            class="rounded-full px-2.5 py-0.5 text-xs font-semibold tracking-wide uppercase"
                            :class="getStatusColor(project.status)"
                        >
                            {{ getStatusLabel(project.status) }}
                        </span>
                        <span
                            class="rounded-md border px-2.5 py-0.5 text-xs font-semibold capitalize"
                            :class="getPriorityColor(project.priority)"
                        >
                            {{ project.priority }} Priority
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <Button variant="destructive" @click="deleteProject">
                        Delete
                    </Button>
                    <Button variant="outline" as-child>
                        <Link :href="`/projects/${project.id}/edit`">
                            Edit
                        </Link>
                    </Button>
                    <Button variant="outline" as-child>
                        <Link href="/projects"> Back to List </Link>
                    </Button>
                </div>
            </div>

            <div
                class="overflow-hidden border border-border bg-background shadow-sm sm:rounded-lg"
            >
                <div class="p-6">
                    <h3 class="mb-6 text-xl font-bold text-foreground">
                        Project Overview
                    </h3>

                    <div
                        class="mb-8 grid grid-cols-1 gap-x-8 gap-y-6 md:grid-cols-2"
                    >
                        <div>
                            <p
                                class="text-sm font-medium text-muted-foreground"
                            >
                                Client Company
                            </p>
                            <p class="text-base font-medium text-foreground">
                                {{ project.company?.name }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-sm font-medium text-muted-foreground"
                            >
                                Project Manager
                            </p>
                            <p class="text-base text-foreground">
                                <span v-if="project.manager">{{
                                    project.manager.name
                                }}</span>
                                <span
                                    v-else
                                    class="text-muted-foreground italic"
                                    >Unassigned</span
                                >
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-sm font-medium text-muted-foreground"
                            >
                                Start Date
                            </p>
                            <p class="text-base text-foreground">
                                {{ formatDate(project.starts_at) }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-sm font-medium text-muted-foreground"
                            >
                                Estimated End Date
                            </p>
                            <p class="text-base text-foreground">
                                {{ formatDate(project.ends_at) }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-sm font-medium text-muted-foreground"
                            >
                                Created At
                            </p>
                            <p class="text-base text-foreground">
                                {{ formatDate(project.created_at, true) }}
                            </p>
                        </div>
                    </div>

                    <div class="mb-8">
                        <p
                            class="mb-2 text-sm font-medium text-muted-foreground"
                        >
                            Description
                        </p>
                        <div
                            v-if="project.description"
                            class="rounded-md border border-border bg-muted/30 p-4 whitespace-pre-wrap text-foreground"
                        >
                            {{ project.description }}
                        </div>
                        <div
                            v-else
                            class="rounded-md border border-dashed border-border bg-muted/10 p-4 text-muted-foreground italic"
                        >
                            No description provided for this project.
                        </div>
                    </div>

                    <div class="border-t border-border pt-8">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-foreground">
                                Associated Tickets
                            </h3>
                            <Button variant="secondary" size="sm" as-child>
                                <Link
                                    :href="`/tickets/create?project_id=${project.id}`"
                                >
                                    New Ticket
                                </Link>
                            </Button>
                        </div>

                        <div
                            v-if="project.tickets && project.tickets.length > 0"
                            class="overflow-x-auto rounded-lg border border-border"
                        >
                            <table
                                class="w-full text-left text-sm text-muted-foreground"
                            >
                                <thead
                                    class="border-b border-border bg-muted text-xs text-muted-foreground uppercase"
                                >
                                    <tr>
                                        <th scope="col" class="px-4 py-3">
                                            ID
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Title
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Status
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Created
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-4 py-3 text-right"
                                        >
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="ticket in project.tickets"
                                        :key="ticket.id"
                                        class="border-b border-border bg-background last:border-0 hover:bg-muted/50"
                                    >
                                        <td
                                            class="px-4 py-3 font-medium text-foreground"
                                        >
                                            #{{ ticket.id }}
                                        </td>
                                        <td
                                            class="max-w-[200px] truncate px-4 py-3"
                                            :title="ticket.title"
                                        >
                                            {{ ticket.title }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="rounded-full px-2 py-0.5 text-[10px] font-medium capitalize"
                                                :class="
                                                    getTicketStatusColor(
                                                        ticket.status,
                                                    )
                                                "
                                            >
                                                {{
                                                    ticket.status.replace(
                                                        '_',
                                                        ' ',
                                                    )
                                                }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-xs">
                                            {{
                                                formatDate(
                                                    ticket.created_at,
                                                    true,
                                                )
                                            }}
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <Link
                                                :href="`/tickets/${ticket.id}`"
                                                class="text-xs font-medium text-primary hover:underline"
                                            >
                                                View
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div
                            v-else
                            class="rounded-md border border-dashed border-border bg-muted/10 p-6 text-center text-muted-foreground"
                        >
                            No tickets registered for this project yet.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

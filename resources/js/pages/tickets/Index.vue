<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Tickets',
                href: '/tickets',
            },
        ],
    },
});

interface User {
    id: number;
    name: string;
}

interface Company {
    id: number;
    name: string;
}

interface Project {
    id: number;
    name: string;
    company: Company;
}

interface Ticket {
    id: number;
    title: string;
    status: string;
    created_at: string;
    project: Project;
    user: User;
}

const props = defineProps<{
    tickets: {
        data: Ticket[];
        links: any[];
    };
}>();

const getStatusColor = (status: string) => {
    switch (status) {
        case 'pending':
            return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
        case 'in_progress':
            return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
        case 'resolved':
            return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
        default:
            return 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300';
    }
};

const getStatusLabel = (status: string) => {
    switch (status) {
        case 'pending':
            return 'Pending';
        case 'in_progress':
            return 'In Progress';
        case 'resolved':
            return 'Resolved';
        default:
            return status;
    }
};

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    }).format(date);
};
</script>

<template>
    <Head title="Tickets" />

    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="mx-auto w-full max-w-7xl">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-xl leading-tight font-semibold text-foreground">
                    Tickets
                </h2>
                <Link href="/tickets/create">
                    <Button>Create New Ticket</Button>
                </Link>
            </div>

            <div class="overflow-hidden bg-background shadow-sm sm:rounded-lg border border-border">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left whitespace-nowrap text-muted-foreground">
                        <thead class="text-xs uppercase bg-muted text-muted-foreground border-b border-border">
                            <tr>
                                <th scope="col" class="px-6 py-3">ID</th>
                                <th scope="col" class="px-6 py-3">Title</th>
                                <th scope="col" class="px-6 py-3">Project</th>
                                <th scope="col" class="px-6 py-3">Creator</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Created At</th>
                                <th scope="col" class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="ticket in tickets.data"
                                :key="ticket.id"
                                class="bg-background border-b border-border hover:bg-muted/50"
                            >
                                <td class="px-6 py-4 font-medium text-foreground">
                                    #{{ ticket.id }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-foreground truncate max-w-xs" :title="ticket.title">
                                        {{ ticket.title }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span>{{ ticket.project?.name }}</span>
                                        <span class="text-xs text-muted-foreground">{{ ticket.project?.company?.name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    {{ ticket.user?.name }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        :class="getStatusColor(ticket.status)"
                                    >
                                        {{ getStatusLabel(ticket.status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    {{ formatDate(ticket.created_at) }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <Button variant="outline" size="sm" as-child>
                                        <Link :href="`/tickets/${ticket.id}`">
                                            View
                                        </Link>
                                    </Button>
                                </td>
                            </tr>
                            <tr v-if="tickets.data.length === 0">
                                <td colspan="7" class="px-6 py-8 text-center text-muted-foreground">
                                    No tickets found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="tickets.links && tickets.links.length > 3" class="px-6 py-4 border-t border-border bg-muted/20">
                    <div class="flex flex-wrap items-center gap-1">
                        <template v-for="(link, i) in tickets.links" :key="i">
                            <div
                                v-if="link.url === null"
                                class="px-3 py-1 text-sm border rounded text-muted-foreground opacity-50 bg-background"
                                v-html="link.label"
                            />
                            <Link
                                v-else
                                :href="link.url"
                                class="px-3 py-1 text-sm border rounded focus:outline-none focus:ring-2 focus:ring-ring focus:border-input"
                                :class="[
                                    link.active
                                        ? 'bg-primary text-primary-foreground border-primary'
                                        : 'bg-background hover:bg-muted text-foreground border-border'
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

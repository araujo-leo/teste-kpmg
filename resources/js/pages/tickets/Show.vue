<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Tickets',
                href: '/tickets',
            },
            {
                title: 'Ticket Details',
                href: '#',
            },
        ],
    },
});

interface User {
    id: number;
    name: string;
    email?: string;
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

interface TicketDetail {
    environment?: string;
    module?: string;
}

interface Ticket {
    id: number;
    title: string;
    description: string;
    status: string;
    attachment_path?: string;
    created_at: string;
    updated_at: string;
    project: Project;
    user: User;
    detail?: TicketDetail;
}

const props = defineProps<{
    ticket: Ticket;
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

const deleteTicket = () => {
    if (confirm('Are you sure you want to delete this ticket?')) {
        router.delete(`/tickets/${props.ticket.id}`);
    }
};
</script>

<template>
    <Head :title="`Ticket #${ticket.id}`" />

    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="mx-auto w-full max-w-4xl">
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <h2 class="text-2xl leading-tight font-semibold text-foreground">
                        Ticket #{{ ticket.id }}
                    </h2>
                    <span
                        class="px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide"
                        :class="getStatusColor(ticket.status)"
                    >
                        {{ getStatusLabel(ticket.status) }}
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <Button variant="destructive" @click="deleteTicket">
                        Delete
                    </Button>
                    <Button variant="outline" as-child>
                        <Link :href="`/tickets/${ticket.id}/edit`">
                            Edit
                        </Link>
                    </Button>
                    <Button variant="outline" as-child>
                        <Link href="/tickets">
                            Back to List
                        </Link>
                    </Button>
                </div>
            </div>

            <div class="overflow-hidden bg-background shadow-sm sm:rounded-lg border border-border">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-foreground mb-4">{{ ticket.title }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Project</p>
                            <p class="text-base text-foreground">{{ ticket.project?.name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Company</p>
                            <p class="text-base text-foreground">{{ ticket.project?.company?.name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Creator</p>
                            <p class="text-base text-foreground">{{ ticket.user?.name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Created At</p>
                            <p class="text-base text-foreground">{{ formatDate(ticket.created_at) }}</p>
                        </div>
                        <div v-if="ticket.detail?.environment">
                            <p class="text-sm font-medium text-muted-foreground">Environment</p>
                            <p class="text-base text-foreground">{{ ticket.detail.environment }}</p>
                        </div>
                        <div v-if="ticket.detail?.module">
                            <p class="text-sm font-medium text-muted-foreground">Module</p>
                            <p class="text-base text-foreground">{{ ticket.detail.module }}</p>
                        </div>
                    </div>

                    <div class="mb-8">
                        <p class="text-sm font-medium text-muted-foreground mb-2">Description</p>
                        <div class="p-4 bg-muted/30 rounded-md border border-border text-foreground whitespace-pre-wrap">
                            {{ ticket.description }}
                        </div>
                    </div>

                    <div v-if="ticket.attachment_path">
                        <p class="text-sm font-medium text-muted-foreground mb-2">Attachment</p>
                        <a
                            :href="`/tickets/${ticket.id}/download`"
                            target="_blank"
                            class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" x2="12" y1="15" y2="3"></line></svg>
                            Download File
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

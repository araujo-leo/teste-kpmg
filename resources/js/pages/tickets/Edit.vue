<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Tickets',
                href: '/tickets',
            },
            {
                title: 'Edit Ticket',
                href: '#',
            },
        ],
    },
});

interface Project {
    id: number | string;
    name: string;
}

interface Company {
    id: number | string;
    name: string;
    projects: Project[];
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
    project_id: number;
    project: Project & { company: Company };
    detail?: TicketDetail;
}

const props = defineProps<{
    companies: Company[];
    ticket: Ticket;
}>();

const form = useForm({
    _method: 'PATCH',
    project_id: props.ticket.project_id.toString(),
    title: props.ticket.title,
    description: props.ticket.description,
    status: props.ticket.status,
    environment: props.ticket.detail?.environment || '',
    module: props.ticket.detail?.module || '',
    attachment: null as File | null,
});

const selectedCompanyId = ref(props.ticket.project?.company?.id?.toString() || '');

const availableProjects = computed(() => {
    if (!selectedCompanyId.value) {
        return [];
    }

    const company = props.companies.find(
        (c) => c.id.toString() === selectedCompanyId.value,
    );

    return company ? company.projects : [];
});

const onCompanyChange = () => {
    form.project_id = '';
};

const handleFileUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;

    if (target.files && target.files.length > 0) {
        form.attachment = target.files[0];
    }
};

const submit = () => {
    form.post(`/tickets/${props.ticket.id}`, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Edit Ticket" />

    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="mx-auto w-full max-w-3xl">
            <h2 class="mb-6 text-xl leading-tight font-semibold text-foreground">
                Edit Ticket
            </h2>

            <div class="overflow-hidden bg-background p-6 shadow-sm sm:rounded-lg border border-border">
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid gap-2">
                        <Label for="company">Company</Label>
                        <Select
                            v-model="selectedCompanyId"
                            @update:model-value="onCompanyChange"
                        >
                            <SelectTrigger id="company">
                                <SelectValue placeholder="Select a company..." />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="company in companies"
                                    :key="company.id"
                                    :value="company.id.toString()"
                                >
                                    {{ company.name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <div class="grid gap-2">
                        <Label for="project">Project</Label>
                        <Select
                            v-model="form.project_id"
                            :disabled="!selectedCompanyId"
                        >
                            <SelectTrigger id="project">
                                <SelectValue placeholder="Select a project..." />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="project in availableProjects"
                                    :key="project.id"
                                    :value="project.id.toString()"
                                >
                                    {{ project.name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError class="mt-1" :message="form.errors.project_id" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="title">Issue Title</Label>
                        <Input
                            type="text"
                            id="title"
                            v-model="form.title"
                            placeholder="E.g.: Timeout error during login"
                            required
                        />
                        <InputError class="mt-1" :message="form.errors.title" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="description">Description</Label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Describe the problem or request in detail"
                            rows="4"
                            required
                        ></textarea>
                        <InputError class="mt-1" :message="form.errors.description" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="status">Status</Label>
                        <Select v-model="form.status">
                            <SelectTrigger id="status">
                                <SelectValue placeholder="Select status..." />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="open">Open</SelectItem>
                                <SelectItem value="in_progress">In Progress</SelectItem>
                                <SelectItem value="resolved">Resolved</SelectItem>
                                <SelectItem value="closed">Closed</SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError class="mt-1" :message="form.errors.status" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="environment">Environment</Label>
                        <Select v-model="form.environment">
                            <SelectTrigger id="environment">
                                <SelectValue placeholder="Select the environment..." />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="Production">Production</SelectItem>
                                <SelectItem value="Staging">Staging</SelectItem>
                                <SelectItem value="Development">Development</SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError class="mt-1" :message="form.errors.environment" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="module">Module</Label>
                        <Input
                            type="text"
                            id="module"
                            v-model="form.module"
                            placeholder="E.g.: Financial, SSO, Identity"
                        />
                        <InputError class="mt-1" :message="form.errors.module" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="attachment">Update Technical Attachment (Optional)</Label>
                        <Input
                            type="file"
                            id="attachment"
                            @change="handleFileUpload"
                            accept=".json,.txt,.pdf,.jpg,.jpeg,.png"
                        />
                        <p v-if="ticket.attachment_path" class="text-sm text-muted-foreground mt-1">
                            Current attachment exists. Uploading a new one will replace it.
                        </p>
                        <InputError class="mt-1" :message="form.errors.attachment" />
                    </div>

                    <div class="flex items-center justify-end">
                        <Button
                            type="submit"
                            :disabled="form.processing"
                        >
                            {{ form.processing ? 'Saving...' : 'Update Ticket' }}
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
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
                title: 'Projects',
                href: '/projects',
            },
            {
                title: 'New Project',
                href: '/projects/create',
            },
        ],
    },
});

interface Company {
    id: number | string;
    name: string;
}

interface User {
    id: number | string;
    name: string;
}

const props = defineProps<{
    companies: Company[];
    users: User[];
}>();

const form = useForm({
    company_id: '',
    name: '',
    code: '',
    description: '',
    status: 'not_started',
    priority: 'low',
    starts_at: '',
    ends_at: '',
    manager_id: '',
});

const submit = () => {
    form.post('/projects');
};
</script>

<template>
    <Head title="New Project" />

    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="mx-auto w-full max-w-4xl">
            <h2
                class="mb-6 text-xl leading-tight font-semibold text-foreground"
            >
                Create New Project
            </h2>

            <div
                class="overflow-hidden border border-border bg-background p-6 shadow-sm sm:rounded-lg"
            >
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="company_id"
                                >Company
                                <span class="text-red-500">*</span></Label
                            >
                            <Select v-model="form.company_id">
                                <SelectTrigger id="company_id">
                                    <SelectValue
                                        placeholder="Select a company..."
                                    />
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
                            <InputError
                                class="mt-1"
                                :message="form.errors.company_id"
                            />
                        </div>

                        <div class="grid gap-2">
                            <Label for="manager_id"
                                >Project Manager (Optional)</Label
                            >
                            <Select v-model="form.manager_id">
                                <SelectTrigger id="manager_id">
                                    <SelectValue
                                        placeholder="Select a manager..."
                                    />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="user in users"
                                        :key="user.id"
                                        :value="user.id.toString()"
                                    >
                                        {{ user.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <InputError
                                class="mt-1"
                                :message="form.errors.manager_id"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="name"
                                >Project Name
                                <span class="text-red-500">*</span></Label
                            >
                            <Input
                                type="text"
                                id="name"
                                v-model="form.name"
                                placeholder="E.g.: ERP Implementation"
                                required
                            />
                            <InputError
                                class="mt-1"
                                :message="form.errors.name"
                            />
                        </div>

                        <div class="grid gap-2">
                            <Label for="code"
                                >Project Code
                                <span class="text-red-500">*</span></Label
                            >
                            <Input
                                type="text"
                                id="code"
                                v-model="form.code"
                                placeholder="E.g.: PRJ-2026-01"
                                required
                            />
                            <InputError
                                class="mt-1"
                                :message="form.errors.code"
                            />
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <Label for="description">Description</Label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Detailed description of the project scope"
                            rows="4"
                        ></textarea>
                        <InputError
                            class="mt-1"
                            :message="form.errors.description"
                        />
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="status"
                                >Status
                                <span class="text-red-500">*</span></Label
                            >
                            <Select v-model="form.status">
                                <SelectTrigger id="status">
                                    <SelectValue
                                        placeholder="Select status..."
                                    />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="not_started"
                                        >Not Started</SelectItem
                                    >
                                    <SelectItem value="in_progress"
                                        >In Progress</SelectItem
                                    >
                                    <SelectItem value="paused"
                                        >Paused</SelectItem
                                    >
                                    <SelectItem value="completed"
                                        >Completed</SelectItem
                                    >
                                </SelectContent>
                            </Select>
                            <InputError
                                class="mt-1"
                                :message="form.errors.status"
                            />
                        </div>

                        <div class="grid gap-2">
                            <Label for="priority"
                                >Priority
                                <span class="text-red-500">*</span></Label
                            >
                            <Select v-model="form.priority">
                                <SelectTrigger id="priority">
                                    <SelectValue
                                        placeholder="Select priority..."
                                    />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="low">Low</SelectItem>
                                    <SelectItem value="medium"
                                        >Medium</SelectItem
                                    >
                                    <SelectItem value="high">High</SelectItem>
                                </SelectContent>
                            </Select>
                            <InputError
                                class="mt-1"
                                :message="form.errors.priority"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="starts_at">Start Date (Optional)</Label>
                            <Input
                                type="date"
                                id="starts_at"
                                v-model="form.starts_at"
                            />
                            <InputError
                                class="mt-1"
                                :message="form.errors.starts_at"
                            />
                        </div>

                        <div class="grid gap-2">
                            <Label for="ends_at">End Date (Optional)</Label>
                            <Input
                                type="date"
                                id="ends_at"
                                v-model="form.ends_at"
                                :min="form.starts_at"
                            />
                            <InputError
                                class="mt-1"
                                :message="form.errors.ends_at"
                            />
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-4">
                        <Button type="submit" :disabled="form.processing">
                            {{
                                form.processing ? 'Saving...' : 'Create Project'
                            }}
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

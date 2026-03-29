<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Companies',
                href: '/companies',
            },
            {
                title: 'Company Details',
                href: '#',
            },
        ],
    },
});

interface Project {
    id: number;
    name: string;
    created_at?: string;
}

interface Company {
    id: number;
    cnpj: string;
    name: string;
    corporate_name: string;
    email: string;
    created_at: string;
    updated_at: string;
    projects?: Project[];
}

const props = defineProps<{
    company: Company;
}>();

const formatDate = (dateString: string) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    }).format(date);
};

const formatCnpj = (cnpj: string) => {
    if (!cnpj) return '';
    const cleaned = cnpj.replace(/\D/g, '');
    if (cleaned.length === 14) {
        return cleaned.replace(
            /^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/,
            '$1.$2.$3/$4-$5',
        );
    }
    return cnpj;
};

const deleteCompany = () => {
    if (
        confirm(
            'Are you sure you want to delete this company? All associated projects and tickets might be affected.',
        )
    ) {
        router.delete(`/companies/${props.company.id}`);
    }
};
</script>

<template>
    <Head :title="company.name" />

    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="mx-auto w-full max-w-4xl">
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <h2
                        class="text-2xl leading-tight font-semibold text-foreground"
                    >
                        {{ company.name }}
                    </h2>
                </div>
                <div class="flex items-center gap-2">
                    <Button variant="destructive" @click="deleteCompany">
                        Delete
                    </Button>
                    <Button variant="outline" as-child>
                        <Link :href="`/companies/${company.id}/edit`">
                            Edit
                        </Link>
                    </Button>
                    <Button variant="outline" as-child>
                        <Link href="/companies"> Back to List </Link>
                    </Button>
                </div>
            </div>

            <div
                class="overflow-hidden border border-border bg-background shadow-sm sm:rounded-lg"
            >
                <div class="p-6">
                    <h3 class="mb-6 text-xl font-bold text-foreground">
                        Company Details
                    </h3>

                    <div
                        class="mb-8 grid grid-cols-1 gap-6 border-b border-border pb-8 md:grid-cols-2"
                    >
                        <div>
                            <p
                                class="text-sm font-medium text-muted-foreground"
                            >
                                CNPJ
                            </p>
                            <p class="font-mono text-base text-foreground">
                                {{ formatCnpj(company.cnpj) }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-sm font-medium text-muted-foreground"
                            >
                                Corporate Name
                            </p>
                            <p class="text-base text-foreground">
                                {{ company.corporate_name }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-sm font-medium text-muted-foreground"
                            >
                                Contact Email
                            </p>
                            <p class="text-base text-foreground">
                                {{ company.email }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-sm font-medium text-muted-foreground"
                            >
                                Registered At
                            </p>
                            <p class="text-base text-foreground">
                                {{ formatDate(company.created_at) }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <div class="mb-4 flex items-center justify-between">
                            <p class="text-lg font-semibold text-foreground">
                                Associated Projects
                            </p>
                            <Button variant="secondary" size="sm" as-child>
                                <Link
                                    :href="`/projects/create?company_id=${company.id}`"
                                >
                                    Add Project
                                </Link>
                            </Button>
                        </div>

                        <div
                            v-if="
                                company.projects && company.projects.length > 0
                            "
                            class="grid grid-cols-1 gap-4 sm:grid-cols-2"
                        >
                            <div
                                v-for="project in company.projects"
                                :key="project.id"
                                class="rounded-md border border-border bg-muted/30 p-4"
                            >
                                <p class="font-medium text-foreground">
                                    {{ project.name }}
                                </p>
                                <p
                                    v-if="project.created_at"
                                    class="mt-1 text-xs text-muted-foreground"
                                >
                                    Created:
                                    {{ formatDate(project.created_at) }}
                                </p>
                            </div>
                        </div>

                        <div
                            v-else
                            class="rounded-md border border-dashed border-border bg-muted/10 p-4 text-center text-muted-foreground"
                        >
                            No projects linked to this company yet.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

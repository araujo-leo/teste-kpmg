<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Companies',
                href: '/companies',
            },
        ],
    },
});

interface Company {
    id: number;
    cnpj: string;
    name: string;
    corporate_name: string;
    email: string;
    created_at: string;
}

const props = defineProps<{
    companies: {
        data: Company[];
        links: any[];
    };
}>();

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

// Função para formatar o CNPJ na exibição caso ele venha sem máscara do banco
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
</script>

<template>
    <Head title="Companies" />

    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="mx-auto w-full max-w-7xl">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-xl leading-tight font-semibold text-foreground">
                    Companies
                </h2>
                <Link href="/companies/create">
                    <Button>Create New Company</Button>
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
                                <th scope="col" class="px-6 py-3">ID</th>
                                <th scope="col" class="px-6 py-3">CNPJ</th>
                                <th scope="col" class="px-6 py-3">
                                    Trading Name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Corporate Name
                                </th>
                                <th scope="col" class="px-6 py-3">Email</th>
                                <th scope="col" class="px-6 py-3">
                                    Created At
                                </th>
                                <th scope="col" class="px-6 py-3 text-right">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="company in companies.data"
                                :key="company.id"
                                class="border-b border-border bg-background hover:bg-muted/50"
                            >
                                <td
                                    class="px-6 py-4 font-medium text-foreground"
                                >
                                    #{{ company.id }}
                                </td>
                                <td class="px-6 py-4 font-mono text-xs">
                                    {{ formatCnpj(company.cnpj) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div
                                        class="max-w-xs truncate font-medium text-foreground"
                                        :title="company.name"
                                    >
                                        {{ company.name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div
                                        class="max-w-xs truncate"
                                        :title="company.corporate_name"
                                    >
                                        {{ company.corporate_name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    {{ company.email }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ formatDate(company.created_at) }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <Button
                                        variant="outline"
                                        size="sm"
                                        as-child
                                    >
                                        <Link
                                            :href="`/companies/${company.id}`"
                                        >
                                            View
                                        </Link>
                                    </Button>
                                </td>
                            </tr>
                            <tr v-if="companies.data.length === 0">
                                <td
                                    colspan="7"
                                    class="px-6 py-8 text-center text-muted-foreground"
                                >
                                    No companies found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="companies.links && companies.links.length > 3"
                    class="border-t border-border bg-muted/20 px-6 py-4"
                >
                    <div class="flex flex-wrap items-center gap-1">
                        <template v-for="(link, i) in companies.links" :key="i">
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

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import VueApexCharts from 'vue3-apexcharts';
import breadcrumbs from '@/components/Breadcrumbs.vue';

const props = defineProps({
    pendingTickets: Number,
    activeProjects: Number,
    totalCompanies: Number,
    recentTickets: Array,
    statusData: Object,
});

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: '/dashboard',
            },
        ],
    },
});

const chartOptions = {
    chart: { type: 'donut', background: 'transparent' },
    labels: Object.keys(props.statusData || {}).map((status) =>
        status.toUpperCase(),
    ),
    colors: ['#F59E0B', '#3B82F6', '#10B981'],
    plotOptions: {
        pie: { donut: { size: '65%' } },
    },
    dataLabels: { enabled: false },
    theme: {
        mode: 'light',
    },
};
const chartSeries = Object.values(props.statusData || {});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div
                    class="overflow-hidden rounded-xl border border-l-4 border-border border-l-yellow-500 bg-background p-6 shadow-sm"
                >
                    <div class="text-sm font-medium text-muted-foreground">
                        Pending Tickets
                    </div>
                    <div class="mt-1 text-3xl font-semibold">
                        {{ pendingTickets }}
                    </div>
                </div>
                <div
                    class="overflow-hidden rounded-xl border border-l-4 border-border border-l-blue-500 bg-background p-6 shadow-sm"
                >
                    <div class="text-sm font-medium text-muted-foreground">
                        Active Projects
                    </div>
                    <div class="mt-1 text-3xl font-semibold">
                        {{ activeProjects }}
                    </div>
                </div>
                <div
                    class="overflow-hidden rounded-xl border border-l-4 border-border border-l-green-500 bg-background p-6 shadow-sm"
                >
                    <div class="text-sm font-medium text-muted-foreground">
                        Registered Companies
                    </div>
                    <div class="mt-1 text-3xl font-semibold">
                        {{ totalCompanies }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div
                    class="rounded-xl border border-border bg-background p-6 shadow-sm"
                >
                    <h3 class="mb-4 text-lg font-medium">Tickets by Status</h3>
                    <div class="flex justify-center">
                        <VueApexCharts
                            v-if="chartSeries.length > 0"
                            type="donut"
                            width="100%"
                            height="280"
                            :options="chartOptions"
                            :series="chartSeries"
                        />
                        <p v-else class="py-10 text-muted-foreground italic">
                            No tickets registered.
                        </p>
                    </div>
                </div>

                <div
                    class="rounded-xl border border-border bg-background p-6 shadow-sm lg:col-span-2"
                >
                    <h3 class="mb-4 text-lg font-medium">
                        Latest Open Tickets
                    </h3>
                    <div class="overflow-x-auto">
                        <table
                            class="min-w-full text-left text-sm whitespace-nowrap"
                        >
                            <thead
                                class="border-b border-border tracking-wider text-muted-foreground uppercase"
                            >
                                <tr>
                                    <th scope="col" class="px-4 py-3">Title</th>
                                    <th scope="col" class="px-4 py-3">
                                        Project
                                    </th>
                                    <th scope="col" class="px-4 py-3">
                                        Status
                                    </th>
                                    <th scope="col" class="px-4 py-3">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="ticket in recentTickets"
                                    :key="ticket.id"
                                    class="border-b border-border transition-colors hover:bg-muted/50"
                                >
                                    <td class="px-4 py-3 font-medium">
                                        {{ ticket.title }}
                                    </td>
                                    <td class="px-4 py-3 text-muted-foreground">
                                        {{ ticket.project?.name }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="rounded-full px-2 py-1 text-xs font-semibold"
                                            :class="{
                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-500':
                                                    ticket.status === 'pending',
                                                'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-500':
                                                    ticket.status ===
                                                    'in_progress',
                                                'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-500':
                                                    ticket.status ===
                                                    'resolved',
                                            }"
                                        >
                                            {{ ticket.status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-muted-foreground">
                                        {{
                                            new Date(
                                                ticket.created_at,
                                            ).toLocaleDateString('en-US')
                                        }}
                                    </td>
                                </tr>
                                <tr v-if="recentTickets?.length === 0">
                                    <td
                                        colspan="4"
                                        class="px-4 py-6 text-center text-muted-foreground italic"
                                    >
                                        No tickets found.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

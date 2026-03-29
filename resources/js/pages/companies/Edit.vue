<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { mask as vMask } from 'vue-the-mask';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

interface Company {
    id: number;
    cnpj: string;
    name: string;
    corporate_name: string;
    email: string;
}

const props = defineProps<{
    company: Company;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Companies',
                href: '/companies',
            },
            {
                title: 'Edit Company',
                href: '#',
            },
        ],
    },
});

const form = useForm({
    cnpj: props.company.cnpj,
    name: props.company.name,
    corporate_name: props.company.corporate_name,
    email: props.company.email,
});

const submit = () => {
    form.put(`/companies/${props.company.id}`);
};
</script>

<template>
    <Head :title="`Edit ${company.name}`" />

    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="mx-auto w-full max-w-3xl">
            <h2
                class="mb-6 text-xl leading-tight font-semibold text-foreground"
            >
                Edit Company: {{ company.name }}
            </h2>

            <div
                class="overflow-hidden border border-border bg-background p-6 shadow-sm sm:rounded-lg"
            >
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid gap-2">
                        <Label for="cnpj">CNPJ</Label>
                        <Input
                            type="text"
                            id="cnpj"
                            v-model="form.cnpj"
                            placeholder="00.000.000/0001-00"
                            v-mask="'##.###.###/####-##'"
                            required
                        />
                        <InputError class="mt-1" :message="form.errors.cnpj" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="name">Trading Name (Nome Fantasia)</Label>
                        <Input
                            type="text"
                            id="name"
                            v-model="form.name"
                            required
                        />
                        <InputError class="mt-1" :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="corporate_name"
                            >Corporate Name (Razão Social)</Label
                        >
                        <Input
                            type="text"
                            id="corporate_name"
                            v-model="form.corporate_name"
                            required
                        />
                        <InputError
                            class="mt-1"
                            :message="form.errors.corporate_name"
                        />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Contact Email</Label>
                        <Input
                            type="email"
                            id="email"
                            v-model="form.email"
                            required
                        />
                        <InputError class="mt-1" :message="form.errors.email" />
                    </div>

                    <div class="flex items-center justify-between pt-4">
                        <Button
                            type="button"
                            variant="outline"
                            @click="form.reset()"
                            :disabled="form.processing"
                        >
                            Discard Changes
                        </Button>
                        <Button
                            type="submit"
                            :disabled="form.processing || !form.isDirty"
                        >
                            {{ form.processing ? 'Saving...' : 'Save Changes' }}
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

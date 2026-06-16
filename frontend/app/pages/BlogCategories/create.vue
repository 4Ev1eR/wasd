<template>
    <div class="flex justify-center py-10">
        <UCard class="w-full max-w-2xl">
            <h1 class="text-xl font-bold mb-4">Додати категорію</h1>

            <UForm :schema="schema" :state="state" class="space-y-4" @submit="onSubmit">
                <UFormField label="Назва" name="title">
                    <UInput v-model="state.title" class="w-full" />
                    <template #error="{ error }">
                        <span class="text-red-500 text-sm">{{ error }}</span>
                    </template>
                </UFormField>

                <UFormField label="Slug" name="slug">
                    <UInput v-model="state.slug" class="w-full" />
                    <template #error="{ error }">
                        <span class="text-red-500 text-sm">{{ error }}</span>
                    </template>
                </UFormField>

                <UFormField label="Батьківська категорія (ID)" name="parent_id">
                    <UInput v-model="state.parent_id" type="number" min="1" />
                </UFormField>

                <div class="flex gap-4">
                    <UButton type="submit" color="primary">Зберегти</UButton>
                    <UButton to="/BlogCategories" color="neutral" variant="soft">Скасувати</UButton>
                </div>
            </UForm>
        </UCard>
    </div>
</template>

<script setup lang="ts">
import { z } from 'zod'

const schema = z.object({
    title: z.string().min(2, 'Мінімум 2 символи'),
    slug: z.string().optional(),
    parent_id: z.number().min(1).nullable().optional()
})

const state = reactive({
    title: '',
    slug: '',
    parent_id: null as number | null
})

const onSubmit = async () => {
    try {
        await $fetch('http://localhost/api/admin/blog/categories', {
            method: 'POST',
            body: state
        })
        navigateTo('/BlogCategories')
    } catch (error: any) {
        console.error('Деталі помилки:', error.data?.errors || error)
    }
}
</script>
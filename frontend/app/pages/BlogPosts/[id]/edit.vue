<template>
    <div class="flex justify-center py-10">
        <UCard class="w-full max-w-2xl">
            <h1 class="text-xl font-bold mb-4">Редагувати статтю</h1>

            <UForm :schema="schema" :state="state" class="space-y-4" @submit="onSubmit">
                <UFormField label="Назва" name="title">
                    <UInput v-model="state.title" class="w-full" />
                </UFormField>

                <UFormField label="Slug" name="slug">
                    <UInput v-model="state.slug" class="w-full" />
                </UFormField>

                <UFormField label="Контент (raw)" name="content_raw">
                    <UTextarea v-model="state.content_raw" :rows="12" class="w-full" />
                </UFormField>

                <UFormField label="ID Категорії" name="category_id">
                    <UInput v-model="state.category_id" type="number" />
                </UFormField>

                <UFormField label="ID Автора" name="user_id">
                    <UInput v-model="state.user_id" type="number" />
                </UFormField>

                <UFormField label="Статус публікації" name="is_published">
                    <div class="flex items-center gap-3">
                        <UCheckbox v-model="state.is_published" />
                        <span class="text-sm text-gray-400">
                            {{ state.is_published ? 'Опубліковано' : 'Чернетка' }}
                        </span>
                    </div>
                </UFormField>

                <div class="flex gap-4">
                    <UButton type="submit" color="primary">Оновити</UButton>
                    <UButton to="/BlogPostsUi" color="neutral" variant="soft">Скасувати</UButton>
                </div>
            </UForm>
        </UCard>
    </div>
</template>

<script setup lang="ts">
import { z } from 'zod'

const route = useRoute()
const postId = route.params.id

const schema = z.object({
    title: z.string().min(5, 'Мінімум 5 символів'),
    slug: z.string().min(3, 'Мінімум 3 символи'),
    content_raw: z.string().min(10, 'Контент має бути довшим за 10 символів')
})

const state = reactive({
    title: '',
    slug: '',
    content_raw: '',
    category_id: null,
    user_id: 1,
    is_published: false
})

onMounted(async () => {
    const response = await $fetch<{ data: any }>(`http://localhost/api/blog/posts/${postId}`)
    state.title        = response.data.title
    state.slug         = response.data.slug
    state.content_raw  = response.data.content_raw ?? ''
    state.category_id  = response.data.category?.id ?? null
    state.user_id      = response.data.user?.id ?? 1
    state.is_published = response.data.is_published ?? false
})

const onSubmit = async () => {
    await $fetch(`http://localhost/api/admin/blog/posts/${postId}`, {
        method: 'PUT',
        body: state
    })
    navigateTo('/BlogPostsUi')
}
</script>
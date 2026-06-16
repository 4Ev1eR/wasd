<template>
    <div class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Список постів (Nuxt UI)</h1>
            <UButton to="/BlogPosts/create" icon="i-lucide-plus" color="primary">
                Додати статтю
            </UButton>
        </div>

        <UTable :data="posts" :columns="columns" class="mb-4">
            <template #title-cell="{ row }">
                <NuxtLink :to="`/BlogPosts/${row.original.id}`" class="text-blue-500 hover:underline font-medium">
                    {{ row.original.title }}
                </NuxtLink>
            </template>

            <template #actions-cell="{ row }">
                <UDropdownMenu :items="getActions(row.original)">
                    <UButton icon="i-lucide-ellipsis-vertical" color="neutral" variant="ghost" />
                </UDropdownMenu>
            </template>
        </UTable>

        <div class="flex justify-center">
            <UPagination
                :page="page"
                :total="total"
                :items-per-page="perPage"
                @update:page="(p) => { page = p; getPosts() }"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import type { DropdownMenuItem } from '@nuxt/ui'

interface BlogPost {
    id: number
    title: string
    published_at: string | null
    user: { name: string }
    category: { title: string }
}

interface BlogPostsResponse {
    data: BlogPost[]
    meta: {
        total: number
        per_page: number
        current_page: number
        last_page: number
    }
}

const posts = ref<BlogPost[]>([])
const page = ref(1)
const perPage = ref(10)
const total = ref(0)

const columns = [
    { accessorKey: 'id', header: '#' },
    { accessorKey: 'user.name', header: 'Автор' },
    { accessorKey: 'category.title', header: 'Категорія' },
    { accessorKey: 'title', header: 'Заголовок' },
    { accessorKey: 'published_at', header: 'Дата публікації' },
    { accessorKey: 'user_id', header: 'Автор' },
    { id: 'actions', header: 'Дії' }
]

const getActions = (post: BlogPost): DropdownMenuItem[] => [
    {
        label: 'Редагувати',
        icon: 'i-lucide-pencil',
        onSelect: () => navigateTo(`/BlogPosts/${post.id}/edit`)
    },
    {
        label: 'Видалити',
        icon: 'i-lucide-trash',
        color: 'error',
        onSelect: () => deletePost(post.id)
    }
]

const deletePost = async (id: number) => {
    if (!confirm('Видалити статтю?')) return
    await $fetch(`http://localhost/api/admin/blog/posts/${id}`, { method: 'DELETE' })
    getPosts()
}

const getPosts = () => {
    $fetch<BlogPostsResponse>('http://localhost/api/blog/posts', {
        query: { page: page.value }
    })
        .then((response) => {
            posts.value = response.data
            total.value = response.meta.total
            perPage.value = response.meta.per_page
        })
        .catch((error) => {
            console.error(error)
        })
}

getPosts()
</script>

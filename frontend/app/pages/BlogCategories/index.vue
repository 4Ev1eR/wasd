<template>
    <div class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Категорії блогу</h1>
            <UButton to="/BlogCategories/create" icon="i-lucide-plus" color="primary">
                Додати категорію
            </UButton>
        </div>

        <UTable :data="categories" :columns="columns" class="mb-4">
            <template #title-cell="{ row }">
                <NuxtLink :to="`/BlogCategories/${row.original.id}`" class="text-blue-500 hover:underline font-medium">
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
                v-model:page="page"
                :total="total"
                :items-per-page="perPage"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
interface BlogCategory {
    id: number
    title: string
    slug: string
    parent_id: number | null
}

interface BlogCategoriesResponse {
    data: BlogCategory[]
    meta: {
        total: number
        per_page: number
        current_page: number
    }
}

const categories = ref<BlogCategory[]>([])
const page = ref(1)
const perPage = ref(15)
const total = ref(0)

const columns = [
    { accessorKey: 'id', header: '#' },
    { accessorKey: 'title', header: 'Назва' },
    { accessorKey: 'slug', header: 'Slug' },
    { accessorKey: 'parent_id', header: 'Батьківська категорія' },
    { id: 'actions', header: 'Дії' }
]

import type { DropdownMenuItem } from '@nuxt/ui'

const getActions = (category: BlogCategory): DropdownMenuItem[] => {
    return [
        {
            label: 'Редагувати',
            icon: 'i-lucide-pencil',
            onSelect: () => {
                navigateTo(`/BlogCategories/${category.id}/edit`)
            }
        },
        {
            label: 'Видалити',
            icon: 'i-lucide-trash',
            color: 'error',
            onSelect: () => {
                deleteCategory(category.id)
            }
        }
    ]
}

const deleteCategory = (id: number) => {
    $fetch(`http://localhost/api/admin/blog/categories/${id}`, {
        method: 'DELETE'
    }).then(() => {
        getCategories()
    }).catch((error) => {
        console.error(error)
    })
}

const getCategories = () => {
    $fetch<BlogCategoriesResponse>('http://localhost/api/admin/blog/categories', {
        query: { page: page.value }
    }).then((response) => {
        categories.value = response.data
        total.value = response.meta.total
        perPage.value = response.meta.per_page
    }).catch((error) => {
        console.error(error)
    })
}

watch(page, () => {
    getCategories()
})

getCategories()
</script>
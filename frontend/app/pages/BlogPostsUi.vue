<template>
    <div class="p-4">
        <h1 class="text-2xl font-bold mb-4">Список постів (Nuxt UI)</h1>

        <UTable :data="posts" :columns="columns" class="mb-4" />

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
interface BlogPost {
    id: number
    title: string
    published_at: string | null
    user: { name: string }
    category: { title: string }
}

interface BlogPostsResponse {
    data: BlogPost[]
    total: number
    per_page: number
    current_page: number
    last_page: number
}

const posts = ref<BlogPost[]>([])
const page = ref(1)
const perPage = ref(10)
const total = ref(0)

const columns = [
    { accessorKey: 'id', header: '#' },
    {
        accessorKey: 'user.name',
        header: 'Автор',
        cell: ({ row }: { row: { original: BlogPost } }) => row.original.user?.name
    },
    {
        accessorKey: 'category.title',
        header: 'Категорія',
        cell: ({ row }: { row: { original: BlogPost } }) => row.original.category?.title
    },
    { accessorKey: 'title', header: 'Заголовок' },
    { accessorKey: 'published_at', header: 'Дата публікації' }
]

const getPosts = () => {
    $fetch<BlogPostsResponse>('http://localhost/api/blog/posts', {
        query: { page: page.value }
    }).then((response) => {
        console.log(response)
        posts.value = response.data
        total.value = response.total
        perPage.value = response.per_page
    }).catch((error) => {
        console.error(error)
    })
}

watch(page, () => {
    getPosts()
})

getPosts()
</script>

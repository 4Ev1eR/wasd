<template>
    <div class="container mx-auto p-6 max-w-3xl">
        <div v-if="post">
            <h1 class="text-3xl font-bold mb-4">{{ post.title }}</h1>

            <div class="flex items-center gap-4 text-gray-500 mb-6 pb-4 border-b">
                <span>Автор: <b class="text-gray-700">{{ post.user.name }}</b></span>
                <span>Категорія: <b class="text-gray-700">{{ post.category.title }}</b></span>
                <span>Дата: {{ post.published_at }}</span>
            </div>

            <div class="prose max-w-none" v-html="post.content_html"></div>

            <div class="mt-8 pt-4 border-t">
                <NuxtLink to="/BlogPostsUi" class="text-primary-500 hover:underline">
                    &larr; Повернутися до списку
                </NuxtLink>
            </div>
        </div>

        <div v-else class="text-center py-10 text-gray-500">
            <p>Завантаження поста...</p>
        </div>
    </div>
</template>

<script setup lang="ts">
interface BlogPostDetail {
    id: number
    title: string
    content_html: string
    published_at: string | null
    user: { name: string }
    category: { title: string }
}

const route = useRoute()
const post = ref<BlogPostDetail | null>(null)

const getPost = () => {
    $fetch<{ data: BlogPostDetail }>(`http://localhost/api/blog/posts/${route.params.id}`)
        .then((response) => {
            post.value = response.data
        })
        .catch((error) => {
            console.error('Помилка завантаження:', error)
        })
}

getPost()
</script>

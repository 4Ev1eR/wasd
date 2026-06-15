<template>
    <div class="container p-4">
        <div v-if="post">
            <h1 class="text-2xl font-bold mb-2">{{ post.title }}</h1>
            <p class="text-gray-500 mb-2">
                Автор: {{ post.user.name }} | Категорія: {{ post.category.title }}
            </p>
            <p class="text-gray-400 mb-4">{{ post.published_at }}</p>
            <div v-html="post.content_html"></div>
        </div>
        <div v-else>
            <p>Завантаження...</p>
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
    $fetch<BlogPostDetail>(`http://localhost/api/blog/posts/${route.params.id}`)
        .then((response) => {
            console.log(response)
            post.value = response
        })
        .catch((error) => {
            console.error(error)
        })
}

getPost()
</script>

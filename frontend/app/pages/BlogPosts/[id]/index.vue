<template>
    <div class="flex justify-center py-10">
        <UCard class="w-full max-w-2xl">
            <div v-if="pending" class="text-center py-10">
                <p class="text-gray-500">Завантаження...</p>
            </div>

            <div v-else-if="error" class="text-center py-10">
                <p class="text-red-500">Помилка завантаження поста.</p>
                <p class="text-sm text-gray-400 mt-2">{{ error.message }}</p>
                <UButton to="/BlogPostsUi" color="neutral" variant="soft" class="mt-4">Назад до списку</UButton>
            </div>

            <div v-else-if="post">
                <!-- Шапка: категорія, статус, дати -->
                <div class="flex flex-wrap gap-4 text-sm text-gray-400 mb-4">
                    <span>Категорія: <strong>{{ post.category?.title ?? 'Без категорії' }}</strong></span>
                    <span>Статус: <strong :class="post.is_published ? 'text-green-400' : 'text-yellow-400'">{{ post.is_published ? 'Опубліковано' : 'Чернетка' }}</strong></span>
                    <span v-if="post.published_at">Дата: <strong>{{ post.published_at }}</strong></span>
                </div>

                <!-- Заголовок -->
                <h1 class="text-3xl font-bold mb-6">{{ post.title }}</h1>

                <!-- Автор -->
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-white font-bold text-sm">
                        {{ post.user?.name?.[0]?.toUpperCase() ?? 'A' }}
                    </div>
                    <span class="text-sm text-gray-400">Автор публікації: <strong class="text-gray-200">{{ post.user?.name ?? 'Невідомо' }}</strong></span>
                </div>

                <UDivider class="mb-6" />

                <!-- Контент -->
                <div class="prose prose-invert max-w-none leading-relaxed" v-html="post.content_html" />

                <UDivider class="my-6" />

                <!-- Кнопки -->
                <div class="flex gap-4">
                    <UButton to="/BlogPostsUi" color="neutral" variant="soft">Назад до списку</UButton>
                    <UButton :to="`/BlogPosts/${post.id}/edit`" color="primary">Редагувати</UButton>
                </div>
            </div>
        </UCard>
    </div>
</template>

<script setup lang="ts">
const route = useRoute()
const postId = route.params.id

const { data, error, pending } = await useFetch(`http://localhost/api/blog/posts/${postId}`)

const post = computed(() => data.value?.data || data.value)
</script>
<template>
    <div class="flex justify-center py-10">
        <UCard class="w-full max-w-2xl">
            <div v-if="pending" class="text-center py-10">
                <p class="text-gray-500">Завантаження...</p>
            </div>

            <div v-else-if="error" class="text-center py-10">
                <p class="text-red-500">Помилка завантаження категорії.</p>
                <p class="text-sm text-gray-400 mt-2">{{ error.message }}</p>
                <UButton to="/BlogCategories" color="neutral" variant="soft" class="mt-4">Назад до списку</UButton>
            </div>

            <div v-else-if="category">
                <!-- Шапка: ID та батьківська категорія -->
                <div class="flex flex-wrap gap-4 text-sm text-gray-400 mb-4">
                    <span>ID: <strong class="text-gray-200">{{ category.id }}</strong></span>
                    <span v-if="category.parent_id">
                        Батьківська категорія ID: <strong class="text-gray-200">{{ category.parent_id }}</strong>
                    </span>
                    <span v-else class="text-gray-500">Категорія верхнього рівня</span>
                </div>

                <!-- Заголовок -->
                <h1 class="text-3xl font-bold mb-4">{{ category.title }}</h1>

                <!-- Slug -->
                <div v-if="category.slug" class="flex items-center gap-2 mb-6">
                    <span class="text-sm text-gray-400">Slug:</span>
                    <code class="text-sm bg-gray-800 px-2 py-1 rounded text-primary">{{ category.slug }}</code>
                </div>

                <UDivider class="my-6" />

                <!-- Кнопки -->
                <div class="flex gap-4">
                    <UButton to="/BlogCategories" color="neutral" variant="soft">Назад до списку</UButton>
                    <UButton :to="`/BlogCategories/${category.id}/edit`" color="primary">Редагувати</UButton>
                    <UButton color="error" variant="soft" @click="deleteCategory">Видалити</UButton>
                </div>
            </div>
        </UCard>
    </div>
</template>

<script setup lang="ts">
const route = useRoute()
const categoryId = route.params.id

const { data, error, pending } = await useFetch<{ data: any }>(`http://localhost/api/admin/blog/categories/${categoryId}`)

const category = computed(() => data.value?.data || data.value)

const deleteCategory = async () => {
    if (!confirm('Ви впевнені, що хочете видалити цю категорію?')) return

    await $fetch(`http://localhost/api/admin/blog/categories/${categoryId}`, {
        method: 'DELETE'
    })
    navigateTo('/BlogCategories')
}
</script>
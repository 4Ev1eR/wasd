<?php

namespace App\Http\Resources\Api\Blog\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Трансформація ресурсу в масив.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'slug'           => $this->slug,
            'is_published'   => (bool) $this->is_published,
            'content_html'   => $this->content_html,
            'content_raw'    => $this->content_raw,

            // Форматуємо дату для зручності фронтенду
            'published_at' => $this->published_at ? \Carbon\Carbon::parse($this->published_at)->format('Y-m-d H:i:s') : null,

            // Зв'язки (щоб Nuxt не зламався)
            'user'           => [
                'id'   => $this->user?->id,
                'name' => $this->user?->name,
            ],
            'category'       => [
                'id'    => $this->category?->id,
                'title' => $this->category?->title,
            ],
        ];
    }
}

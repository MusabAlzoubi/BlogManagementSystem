<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use App\Notifications\PostPendingApproval;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Notification;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['author_id'] = auth()->id();
        $post = new Post($data);

        if ($post->author && $post->needsApproval()) {
            $data['status'] = 'pending';
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->record->author && $this->record->needsApproval()) {
            $admins = User::role('Admin')->get();

            if ($admins->isNotEmpty()) {
                Notification::send($admins, new PostPendingApproval($this->record));
            }
        }
    }
}

<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
{
    $post = new Post($data);

    if ($post->needsApproval()) {
        $data['status'] = 'pending';
    }

    return $data;
}
protected function afterCreate(): void
{
    if ($this->record->needsApproval()) {
        $admins = \App\Models\User::role('Admin')->get();
        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\PostPendingApproval($this->record));
    }
}

}

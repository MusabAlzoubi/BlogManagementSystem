<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeSave(array $data): array
{
    if ($this->record->needsApproval() && $data['status'] === 'published') {
        $data['status'] = 'pending';
    }

    return $data;
}
protected function afterSave(): void
{
    if ($this->record->needsApproval() && $this->record->status === 'pending') {
        $admins = \App\Models\User::role('Admin')->get();
        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\PostPendingApproval($this->record));
    }
}

}

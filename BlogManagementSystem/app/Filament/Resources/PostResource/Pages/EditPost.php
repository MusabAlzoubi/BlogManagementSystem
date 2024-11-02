<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Models\User;
use App\Notifications\PostPendingApproval;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Notification;

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
        if ($this->record->needsApproval() && !auth()->user()->hasRole('Admin') && isset($data['status']) && $data['status'] === 'published') {
            $data['status'] = 'pending';
        }

        return $data;
    }

  
    protected function afterSave(): void
{
    if ($this->record->needsApproval() && $this->record->status === 'pending') {
        $admins = User::role('Admin')->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new PostPendingApproval($this->record));
        }
    }
    if ($this->record->wasChanged('status') && $this->record->status === 'published') {
        $this->record->author->notify(new \App\Notifications\PostApprovalStatus($this->record, 'approved'));
    } elseif ($this->record->wasChanged('status') && $this->record->status === 'rejected') {
        $this->record->author->notify(new \App\Notifications\PostApprovalStatus($this->record, 'rejected'));
    }
}

}

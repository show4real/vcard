<?php

namespace App\Http\Livewire;

use App\Models\ContactUs;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ContactUsTable extends LivewireTableComponent
{
    protected $model = ContactUs::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setPageName('contact_us-table');
        $this->setDefaultSort('created_at');
        $this->setEmptyMessage('No results found');
        $this->setColumnSelectStatus(false);
        $this->setPerPage(10);

        $this->setThAttributes(function (Column $column) {
            if ($column->isField('id')) {
                return [
                    'class' => 'd-flex justify-content-center',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.common.name'), 'name')
                ->sortable()->searchable(),
            Column::make(__('messages.common.email'), 'email')
                ->sortable()->searchable(),
            Column::make(__('messages.common.subject'), 'subject')
                ->sortable()->searchable(),
            Column::make(__('messages.common.message'), 'message')
                ->sortable(),

        ];
    }

    public function builder(): Builder
    {
        return ContactUs::query();
    }
}

<?php

namespace App\Http\Livewire;

use App\Models\Vcard;
use Illuminate\Database\Eloquent\Builder;
use Stancl\Tenancy\Database\Models\Tenant;
use App\Http\Livewire\LivewireTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;



class VcardTable extends LivewireTableComponent
{
    protected $model = Vcard::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setPageName('Vcard-table');
        $this->setDefaultSort('created_at', 'desc');
        $this->setColumnSelectStatus(false);
        $this->setPerPage(10);
    }
    public function columns(): array
    {
        return [
            Column::make(__('messages.vcard.vcard_name'), 'name')->sortable()->searchable()
                ->view('sadmin.vcards.columns.name'),
            Column::make(__('messages.vcard.user_name'), 'tenant.tenant_username')->sortable(function (
                Builder $query,
                $direction
            ) {
                return $query->orderBy(
                    Tenant::select('tenant_username')->whereColumn('tenants.id', 'vcards.tenant_id'),
                    $direction
                );
            })
                ->searchable(),
            Column::make(__('messages.vcard.preview_url'), 'url_alias')
                ->hideIf('url_alias')
                ->searchable(),
            Column::make(__('messages.vcard.preview_url'), 'url_alias')->sortable()->view('sadmin.vcards.columns.preview'),
            Column::make(__('messages.vcard.stats'), 'id')
                ->view('sadmin.vcards.columns.stats'),
            Column::make(__('messages.vcard.created_at'), 'created_at')->sortable()
                ->view('sadmin.vcards.columns.created_at'),
            Column::make(__('messages.vcard.status'), 'status')->sortable()
                ->view('sadmin.vcards.columns.status'),

        ];
    }

    public function builder(): Builder
    {
        return Vcard::with('template', 'tenant')->where('tenant_id', '!=', getLogInTenantId())->select('vcards.*');
    }
}

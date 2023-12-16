<?php

namespace App\Http\Livewire;

use App\Models\ProductTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ProductTransactionsTable extends LivewireTableComponent
{
    protected $model = ProductTransaction::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setPageName('product-transactions-table');
        $this->setDefaultSort('created_at', 'desc');
        $this->setSortingPillsStatus(false);
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
            Column::make(__('messages.vcard.product_name'), 'product.name')->searchable()->sortable(),
            Column::make(__('messages.common.name'), 'name')->sortable()->searchable(),
            Column::make(__('messages.vcard.order_at'), 'created_at')->sortable()->searchable(),
            Column::make(__('messages.payment_type'), 'type')->view('product_transactions.column.type')->sortable()->searchable(),
            Column::make(__('messages.subscription.amount'), 'amount')->view('product_transactions.column.amount')->sortable()->searchable(),
            Column::make(__('messages.common.action'), 'id')->view('product_transactions.column.action'),
        ];
    }

    public function builder(): Builder
    {
        $tenantId = Auth::user()->tenant_id;
        $query = ProductTransaction::whereHas('product.vcard', function($q) use ($tenantId){
            $q->whereTenantId($tenantId);
        });

        return $query->select('product_transactions.*');
    }

    public function resetPageTable($pageName = 'product-transactions-table')
    {
        $rowsPropertyData = $this->getRows()->toArray();
        $prevPageNum = $rowsPropertyData['current_page'] - 1;
        $prevPageNum = $prevPageNum > 0 ? $prevPageNum : 1;
        $pageNum = count($rowsPropertyData['data']) > 0 ? $rowsPropertyData['current_page'] : $prevPageNum;

        $this->setPage($pageNum, $pageName);
    }
}

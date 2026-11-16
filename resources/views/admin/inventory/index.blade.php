@extends('layouts.admin')

@section('title', 'Inventory')

@section('content')
    <section class="page-heading">
        <div>
            <h1>Inventory</h1>
            <p>Register products, record stock movements, and monitor available quantities.</p>
        </div>
        <a class="admin-primary-button" href="{{ route('admin.modules.create', 'inventory') }}">Record stock movement <span aria-hidden="true">+</span></a>
    </section>

    <section class="inventory-workflow" aria-labelledby="inventory-workflow-title">
        <h2 id="inventory-workflow-title">Recommended inventory workflow</h2>
        <a class="inventory-workflow-step" href="{{ route('admin.modules.index', 'products') }}">
            <span class="inventory-workflow-icon" aria-hidden="true">◇</span>
            <strong>Products</strong>
            <span>Register products in the system</span>
            <span class="inventory-workflow-action">{{ $products->count() }} registered · View products</span>
        </a>
        <span class="inventory-workflow-arrow" aria-hidden="true">↓</span>
        <a class="inventory-workflow-step" href="{{ route('admin.modules.create', 'inventory') }}">
            <span class="inventory-workflow-icon" aria-hidden="true">▤</span>
            <strong>Stock Movement</strong>
            <span>Record stock in, stock out, and adjustments</span>
            <span class="inventory-workflow-action">Record movement</span>
        </a>
        <span class="inventory-workflow-arrow" aria-hidden="true">↓</span>
        <a class="inventory-workflow-step" href="#stock-balance">
            <span class="inventory-workflow-icon" aria-hidden="true">▦</span>
            <strong>Stock Balance</strong>
            <span>Available quantities update from product stock and movements</span>
            <span class="inventory-workflow-action">View balances</span>
        </a>
        <span class="inventory-workflow-arrow" aria-hidden="true">↓</span>
        <a class="inventory-workflow-step" href="#inventory-reports">
            <span class="inventory-workflow-icon" aria-hidden="true">▥</span>
            <strong>Inventory Reports</strong>
            <span>Monitor stock levels and transaction history</span>
            <span class="inventory-workflow-action">View reports</span>
        </a>
    </section>

    <section class="inventory-summary-grid" aria-label="Inventory summary">
        <article class="summary-card">
            <div class="summary-card-top"><span>Products</span></div>
            <strong>{{ $products->count() }}</strong>
            <small>Products registered</small>
        </article>
        <article class="summary-card">
            <div class="summary-card-top"><span>Available stock</span></div>
            <strong>{{ number_format($totalStock, 2) }}</strong>
            <small>Total quantity across products</small>
        </article>
        <article class="summary-card">
            <div class="summary-card-top"><span>Stock movements</span></div>
            <strong>{{ $movementCount }}</strong>
            <small>Transactions recorded</small>
        </article>
    </section>

    <section id="stock-balance" class="data-card inventory-report-card" aria-labelledby="stock-balance-title">
        <div class="data-card-heading">
            <div>
                <h2 id="stock-balance-title">Stock Balance</h2>
                <p>Starting quantity from each product plus its recorded stock movements.</p>
            </div>
            <a class="text-action" href="{{ route('admin.modules.create', 'products') }}">Add product</a>
        </div>
        @if ($balances->isEmpty())
            <div class="table-empty-state">
                <h2>No products registered</h2>
                <p>Add products first to see available stock balances.</p>
                <a class="admin-primary-button" href="{{ route('admin.modules.create', 'products') }}">Add first product</a>
            </div>
        @else
            <div class="table-scroll">
                <table class="admin-table">
                    <thead><tr><th>Product</th><th>SKU</th><th>Starting stock</th><th>Movement change</th><th>Available quantity</th></tr></thead>
                    <tbody>
                        @foreach ($balances as $item)
                            <tr>
                                <td class="record-title">{{ $item['product']->label }}</td>
                                <td>{{ $item['product']->data['sku'] ?? '—' }}</td>
                                <td>{{ number_format($item['opening'], 2) }}</td>
                                <td>{{ $item['change'] > 0 ? '+' : '' }}{{ number_format($item['change'], 2) }}</td>
                                <td class="record-title">{{ number_format($item['balance'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <section id="inventory-reports" class="data-card inventory-report-card" aria-labelledby="inventory-reports-title">
        <div class="data-card-heading">
            <div>
                <h2 id="inventory-reports-title">Inventory Reports</h2>
                <p>Latest stock movement transactions.</p>
            </div>
            <div class="inventory-report-actions">
                <a class="text-action" href="{{ route('admin.modules.import.form', 'inventory') }}">Import opening stock</a>
                <a class="text-action" href="{{ route('admin.modules.index', 'stock-adjustments') }}">Stock adjustments</a>
            </div>
        </div>
        @if ($recentMovements->isEmpty())
            <div class="table-empty-state">
                <h2>No stock movements yet</h2>
                <p>Record a stock movement to begin tracking transaction history.</p>
                <a class="admin-primary-button" href="{{ route('admin.modules.create', 'inventory') }}">Record first movement</a>
            </div>
        @else
            <div class="table-scroll">
                <table class="admin-table">
                    <thead><tr><th>Movement</th><th>Product</th><th>Type</th><th>Quantity</th><th>Date</th><th>Notes</th></tr></thead>
                    <tbody>
                        @foreach ($recentMovements as $movement)
                            <tr>
                                <td class="record-title">{{ $movement->label }}</td>
                                <td>{{ $movement->data['product'] ?? '—' }}</td>
                                <td>{{ $movement->data['movement_type'] ?? '—' }}</td>
                                <td>{{ number_format((float) ($movement->data['quantity'] ?? 0), 2) }}</td>
                                <td>{{ $movement->data['movement_date'] ?? $movement->created_at->format('Y-m-d') }}</td>
                                <td>{{ \Illuminate\Support\Str::limit((string) ($movement->data['notes'] ?? '—'), 70) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection

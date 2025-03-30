@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="d-flex justify-content-between align-items-center">
        Мои заказы
        <form id="delete-form" action="{{ route('orders.remove') }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-lg" title="Удалить все заказы" onclick="return confirm('Вы уверены, что хотите удалить все заказы?');">
                &#10006;
            </button>
        </form>
    </h2>

    @if($orders->isEmpty())
        <p>У вас пока нет заказов.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>№ заказа</th>
                    <th>Дата</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                    <th>Товары</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->number }}</td>
                    <td>{{ $order->date }}</td>
                    <td>{{ number_format($order->total_price, 2) }} ₽</td>
                    <td>{{ $order->payment_status ? 'Оплачен' : 'Не оплачен' }}</td>
                    <td>
                        <ul>
                            @foreach($order->items as $item)
                                <li>{{ $item->product->name }} (x{{ $item->quantity }}) — {{ number_format($item->price, 2) }} ₽</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

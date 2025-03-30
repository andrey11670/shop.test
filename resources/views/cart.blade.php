@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Корзина</h2>

        @if($cartItems->isEmpty())
            <p>Ваша корзина пуста.</p>
        @else
            <table class="table">
                <thead>
                <tr>
                    <th>Товар</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Сумма</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($cartItems as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ number_format($item->price, 2) }} ₽</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price * $item->quantity, 2) }} ₽</td>
                        <td>
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <p><strong>Итого:</strong> {{ number_format($cartItems->sum(fn($item) => $item->price * $item->quantity), 2) }} ₽</p>
            <form action="{{ route('order.create') }}" method="POST">
                @csrf
                <input type="hidden" name="items" value="{{ json_encode($cartItems) }}">
                <button type="submit" class="btn btn-success">Оформить заказ</button>
            </form>
        @endif
    </div>
@endsection

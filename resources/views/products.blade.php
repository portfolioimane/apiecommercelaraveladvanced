<!DOCTYPE html>
<html>
<head>
    <title>{{ $product->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <h1>{{ $product->name }}</h1>
    <p>{{ $product->description }}</p>
    <p>{{ number_format($product->price, 2) }} MAD</p>
    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid">

    <form action="{{ route('cart.add', $product->id) }}" method="POST">
        @csrf
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="1" min="1" class="form-control mb-3">
        
        <button type="submit" class="btn btn-primary">Add to Cart</button>
    </form>

    <a href="{{ route('product.index') }}" class="btn btn-secondary">Back to Products</a>
</body>
</html>

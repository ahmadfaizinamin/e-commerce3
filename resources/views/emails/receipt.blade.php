<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembelian</title>
</head>
<body>
    <h1>Struk Pembelian E-commerce</h1>
    <h3>Berikut rinciannya: </h3>

    <div>
        <p><strong>Order ID: </strong>{{ $orderData['order_id'] }}</p>
        <p><strong>Total Pembayaran: </strong>{{ $orderData['total_price'] }}</p>
        <p><strong>Status: </strong>Menuggu pembayaran</p>
    </div>

    <p>Segera selesaikan pembayaran!</p>
</body>
</html>
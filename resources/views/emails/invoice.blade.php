<!DOCTYPE html>
<html>

<head>
    <title>Your Monthly Invoice</title>
</head>

<body>
    <p>Dear {{ $invoice->customer->name }},</p>
    <p>Please find your invoice attached.</p>
    <p><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</p>
    <p><strong>Amount:</strong> ${{ number_format($invoice->amount, 2) }}</p>
</body>

</html>
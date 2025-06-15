<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
        }

        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .invoice-details div {
            flex: 1;
        }

        .invoice-details .customer {
            text-align: left;
        }

        .invoice-details .meta {
            text-align: right;
        }

        .invoice-details p {
            margin: 5px 0;
            font-size: 16px;
        }

        .amount {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }

        @media print {
            body {
                background: none;
            }

            .container {
                box-shadow: none;
                margin: 0;
                width: 100%;
            }
        }

        @media (max-width: 600px) {
            .invoice-details {
                flex-direction: column;
            }

            .invoice-details .meta {
                text-align: left;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Invoice</h1>
        </div>
        <div class="invoice-details">
            <div class="customer">
                <p><strong>Customer:</strong> {{ $invoice['customer']['name'] }}</p>
            </div>
            <div class="meta">
                <p><strong>Date:</strong> {{ $invoice['date'] }}</p>
                <p class="amount"><strong>Amount:</strong> {{ $invoice['amount'] }}</p>
            </div>
        </div>
        <div class="footer">
            <p>Thank you for your business!</p>
        </div>
    </div>
</body>

</html>
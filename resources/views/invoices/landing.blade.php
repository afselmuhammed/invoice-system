<!DOCTYPE html>
<html>

<head>
    <title>Invoice Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
        }

        .message {
            text-align: center;
            color: green;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        .pending {
            color: orange;
            font-weight: bold;
        }

        .sent {
            color: green;
            font-weight: bold;
        }

        .failed {
            color: red;
            font-weight: bold;
        }

        .actions {
            margin: 20px 0;
            text-align: center;
        }

        button {
            padding: 10px 15px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h1>Invoice Dashboard</h1>

    {{-- Flash Message --}}
    @if (session('message'))
    <div class="message">
        {{ session('message') }}
    </div>
    @endif

    {{-- Generate Invoices Form --}}
    <div class="actions">
        <form action="{{ route('invoices.generate') }}" method="POST">
            @csrf
            <button type="submit">Generate Invoices</button>
        </form>
    </div>

    {{-- Invoices Table --}}
    <table>
        <thead>
            <tr>
                <th>Customer</th>
                <th>Email</th>
                <th>Invoice Number</th>
                <th>Status</th>
                <th>PDF</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
            @php $invoice = $customer->latestInvoice; @endphp
            <tr>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $invoice?->invoice_number ?? '-' }}</td>
                <td class="{{ $invoice?->status ?? '' }}">{{ $invoice?->status ?? 'N/A' }}</td>
                <td>
                    @if ($invoice?->pdf_path)
                    <a href="{{ asset('storage/' . $invoice->pdf_path) }}" target="_blank">View PDF</a>
                    @else
                    -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
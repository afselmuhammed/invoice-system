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
        <tbody id="invoice-table">
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

    <script>
        function loadInvoices() {
            fetch("{{ route('invoices.statuses') }}")
                .then(res => res.json())
                .then(response => {
                    const table = document.getElementById('invoice-table');
                    table.innerHTML = '';

                    response.data.forEach(customer => {
                        const invoice = customer.latest_invoice;
                        const statusClass = invoice?.status || '';
                        const row = `
                            <tr>
                                <td>${customer.name}</td>
                                <td>${customer.email}</td>
                                <td>${invoice?.invoice_number ?? '-'}</td>
                                <td class="${statusClass}">${invoice?.status ?? 'N/A'}</td>
                                <td>${invoice?.pdf_path ? `<a href="/storage/${invoice.pdf_path}" target="_blank">View PDF</a>` : '-'}</td>
                            </tr>
                        `;
                        table.insertAdjacentHTML('beforeend', row);
                    });
                });
        }
    </script>
</body>

</html>
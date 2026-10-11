<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Packing Sticker — {{ $order->order_number }}</title>
    <style>
        @page { size: A4 portrait; margin: 8mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: "Poppins", DejaVu Sans, Arial, Helvetica, sans-serif;
            background: #f4f5f7;
            color: #000;
            padding: 24px 12px;
            font-size: 13px;
        }
        table.page { width: 100%; border-collapse: collapse; }
        table.page td.center { text-align: center; vertical-align: top; }
        .sticker-wrap { display: inline-block; text-align: left; width: 97mm; }
        .slip { border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; background: #fff; font-size: 9pt; }
        table.head { width: 100%; background: #000; color: #fff; border-collapse: collapse; }
        table.head td { padding: 3mm; vertical-align: middle; }
        .brand-icon {
            width: 30px; height: 30px;
            background: #fff; color: #000;
            font-size: 15pt; font-weight: bold; text-align: center;
            border-radius: 6px;
        }
        .head h1 { font-size: 12pt; }
        .head small { color: #a1a1aa; font-size: 7pt; letter-spacing: 1px; }
        .slip-body { padding: 3mm; }
        .order-no { font-size: 14pt; font-weight: bold; }
        .meta { font-size: 8pt; color: #52525b; }
        .status-pill {
            display: inline-block;
            font-size: 7pt; font-weight: bold;
            background: #000; color: #fff;
            border-radius: 8px;
            padding: 1mm 3mm;
            margin-top: 1mm;
        }
        table.info { width: 100%; border-collapse: collapse; margin: 2mm 0; font-size: 8pt; }
        table.info td { vertical-align: top; padding-right: 2mm; }
        table.info h4 { font-size: 7pt; color: #71717a; letter-spacing: 1px; margin-bottom: 1mm; }
        table.items { width: 100%; border-collapse: collapse; font-size: 8pt; }
        table.items th {
            text-align: left; font-size: 7pt; color: #71717a;
            border-bottom: 2px solid #000; padding: 1mm 0;
        }
        table.items td { border-bottom: 1px solid #e5e7eb; padding: 1mm 0; vertical-align: top; }
        table.items td.c { text-align: center; }
        table.items td.r { text-align: right; white-space: nowrap; }
        table.totals { width: 62%; margin-left: 38%; border-collapse: collapse; font-size: 8pt; margin-top: 1mm; }
        table.totals td { padding: 0.5mm 0; }
        table.totals td.r { text-align: right; }
        table.totals tr.grand td { font-size: 10pt; font-weight: bold; border-top: 2px solid #000; padding-top: 1mm; }
        .notes { font-size: 8pt; background: #f4f4f5; border-radius: 4px; padding: 1.5mm 2mm; margin-top: 2mm; }
        table.pack { width: 100%; border-collapse: collapse; margin-top: 2mm; border-top: 1px dashed #a1a1aa; font-size: 8pt; }
        table.pack td { padding-top: 1mm; }
        .thanks { text-align: center; font-size: 7pt; color: #71717a; margin-top: 1mm; }
        @media print {
            body { background: #fff; padding: 0; }
        }
    </style>
</head>
<body>
    <table class="page">
        <tr>
            <td class="center">
                <div class="sticker-wrap">
                    @include('admin.order._sticker', ['order' => $order])
                </div>
            </td>
        </tr>
    </table>
</body>
</html>

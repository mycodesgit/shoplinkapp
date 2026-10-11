<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>No Accepted Orders</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: "Poppins", Arial, Helvetica, sans-serif;
            background: #f4f5f7;
            color: #000;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .empty {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 40px 48px;
            text-align: center;
            max-width: 420px;
        }
        .empty .icon {
            width: 56px; height: 56px;
            margin: 0 auto 16px;
            border-radius: 50%;
            background: #000;
            color: #fff;
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .empty h2 { font-size: 18px; letter-spacing: -0.02em; margin-bottom: 6px; }
        .empty p { font-size: 13px; color: #71717a; }
    </style>
</head>
<body>
    <div class="empty">
        <div class="icon">!</div>
        <h2>No Accepted Order to Print</h2>
        <p>There are no accepted orders right now. Accept pending orders first, then come back to print their packing stickers.</p>
    </div>
</body>
</html>

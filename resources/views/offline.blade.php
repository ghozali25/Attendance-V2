<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline - Ali</title>
    <style>
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f3f4f6;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            color: #374151;
            text-align: center;
        }
        .icon {
            width: 80px;
            height: 80px;
            margin-bottom: 24px;
            color: #9ca3af;
        }
        h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        p {
            margin-bottom: 24px;
            color: #6b7280;
        }
        .btn {
            background-color: #4f46e5;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.2s;
        }
        .btn:hover {
            background-color: #4338ca;
        }
    </style>
</head>
<body>
    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18v.01M16 18v.01M8 18v.01M12 21v.01"></path>
    </svg>
    <h1>Anda Sedang Offline</h1>
    <p>Koneksi internet Anda terputus. Mohon periksa jaringan Anda.</p>
    <a href="/" class="btn" onclick="window.location.reload(); return false;">Coba Lagi</a>
</body>
</html>

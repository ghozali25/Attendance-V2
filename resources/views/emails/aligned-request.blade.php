@php
    $companyName = \App\Models\Setting::getValue('app.company_name', config('app.name', 'Ali'));
    $appUrl = config('app.url');
    // Pastikan path logo benar.
    $logoUrl = url('images/icons/favicon-96x96.png'); 
    
    // Warna tema (Bisa diubah di sini)
    $colorBg = '#f3f4f6';      // Abu-abu terang modern
    $colorCard = '#ffffff';    // Putih bersih
    $colorTextMain = '#111827'; // Hampir hitam (Gray-900)
    $colorTextMuted = '#6b7280'; // Abu-abu sedang (Gray-500)
    $colorButton = '#10b981';   // Emerald Green (Modern Green)
@endphp
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>{{ $companyName }}</title>
<style type="text/css">
    /* Reset */
    body { margin: 0; padding: 0; min-width: 100%; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; background-color: {{ $colorBg }}; }
    table { border-spacing: 0; border-collapse: collapse; }
    td { word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; }
    
    /* Elements */
    .wrapper { width: 100%; table-layout: fixed; background-color: {{ $colorBg }}; padding-bottom: 40px; }
    .main-card { background-color: {{ $colorCard }}; margin: 0 auto; width: 100%; max-width: 480px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); overflow: hidden; }
    .card-content { padding: 40px 40px 30px 40px; text-align: left; }
    
    /* Typography */
    h1 { font-size: 20px; font-weight: 700; color: {{ $colorTextMain }}; margin: 0 0 16px 0; letter-spacing: -0.025em; }
    p { font-size: 14px; font-weight: 400; color: {{ $colorTextMuted }}; line-height: 1.6; margin: 0 0 12px 0; }
    
    /* Data Table Styles */
    .dt-row td { padding-bottom: 8px; vertical-align: baseline; }
    .dt-label { width: 100px; font-size: 13px; color: #9ca3af; font-weight: 500; white-space: nowrap; } /* Label lebih soft */
    .dt-sep { width: 15px; font-size: 13px; color: #d1d5db; text-align: center; }
    .dt-value { font-size: 14px; color: {{ $colorTextMain }}; font-weight: 600; } /* Value tebal & gelap */

    /* Button */
    .btn-wrap { text-align: center; margin-top: 24px; margin-bottom: 24px; }
    .btn { display: inline-block; padding: 12px 28px; background-color: {{ $colorButton }}; color: #ffffff !important; text-decoration: none; font-size: 13px; font-weight: 600; border-radius: 8px; letter-spacing: 0.5px; text-transform: uppercase; transition: background-color 0.2s; }
    
    /* Footer */
    .footer { text-align: center; padding-top: 24px; }
    .footer-text { font-size: 12px; color: #9ca3af; }
    .subcopy { border-top: 1px solid #f3f4f6; margin-top: 24px; padding-top: 16px; }
    .subcopy p { font-size: 11px; line-height: 1.4; color: #9ca3af; margin-bottom: 4px; }
    .subcopy a { color: {{ $colorButton }}; text-decoration: none; word-break: break-all; }

    /* Mobile */
    @media only screen and (max-width: 600px) {
        .card-content { padding: 30px 24px; }
        .main-card { width: 95% !important; }
    }
</style>
</head>
<body>

<table class="wrapper" role="presentation">
    
    <tr><td height="40" style="font-size: 40px; line-height: 40px;">&nbsp;</td></tr>

    <tr>
        <td align="center" style="padding-bottom: 24px;">
            <a href="{{ $appUrl }}" style="text-decoration: none; display: inline-block;">
                <table role="presentation">
                    <tr>
                        @if(!empty($logoUrl))
                        <td style="padding-right: 10px;">
                            <img src="{{ $logoUrl }}" width="32" height="32" alt="Logo" style="display: block; border-radius: 50%;">
                        </td>
                        @endif
                        <td style="font-size: 16px; font-weight: 700; color: {{ $colorTextMain }}; letter-spacing: -0.025em;">
                            {{ $companyName }}
                        </td>
                    </tr>
                </table>
            </a>
        </td>
    </tr>

    <tr>
        <td align="center">
            <table class="main-card" role="presentation">
                <tr>
                    <td class="card-content">
                        
                        <h1>{{ $greeting ?? 'Hello, Admin!' }}</h1>

                        @foreach (($introLines ?? []) as $line)
                            <p>{!! \Illuminate\Support\Str::markdown($line) !!}</p>
                        @endforeach

                        @if (!empty($details))
                        <table width="100%" role="presentation" style="margin-top: 20px; margin-bottom: 10px;">
                            @foreach($details as $label => $value)
                            <tr class="dt-row">
                                <td class="dt-label">{{ $label }}</td>
                                <td class="dt-sep">:</td>
                                <td class="dt-value">{{ $value }}</td>
                            </tr>
                            @endforeach
                        </table>
                        @endif

                        @if (!empty($actionText) && !empty($actionUrl))
                        <div class="btn-wrap">
                            <a href="{{ $actionUrl }}" class="btn" target="_blank">{{ $actionText }}</a>
                        </div>
                        @endif

                        @foreach (($outroLines ?? []) as $line)
                            <p>{{ $line }}</p>
                        @endforeach

                        @if (!empty($actionText) && !empty($actionUrl))
                        <div class="subcopy">
                            <p>@lang('If you\'re having trouble clicking the ":actionText" button, copy and paste the URL below into your web browser:', ['actionText' => $actionText])</p>
                            <p><a href="{{ $actionUrl }}">{{ $actionUrl }}</a></p>
                        </div>
                        @endif

                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td class="footer">
            <p class="footer-text">
                &copy; {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
            </p>
        </td>
    </tr>
    
    <tr><td height="40" style="font-size: 40px; line-height: 40px;">&nbsp;</td></tr>

</table>

</body>
</html>
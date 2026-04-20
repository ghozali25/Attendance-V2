@props(['url'])
<tr>
<td class="header">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td class="header-content" align="center" style="text-align: center;">
                <a href="{{ $url }}" style="text-decoration: none;">
                    <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation">
                        <tr>
                            @if (trim($slot) === 'Laravel')
                                <td>
                                    <img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo" style="height: 32px; width: auto; vertical-align: middle; margin-right: 12px;">
                                </td>
                            @else
                                {{-- Logo: Revert to URL for Stability --}}
                                @php
                                    // Fallback to standard URL to ensure delivery
                                    $logoSrc = url('images/icons/favicon-96x96.png'); 
                                @endphp
                                <td style="vertical-align: middle; padding-right: 14px;">
                                    <img src="{{ $logoSrc }}" class="logo" alt="Ali" style="height: 32px; width: 32px; border-radius: 50%; vertical-align: middle; display: block;">
                                </td>
                                <td style="vertical-align: middle; text-align: left;">
                                    <span class="header-title" style="font-size: 19px; font-weight: 800; color: #1f2937; font-family: 'Inter', sans-serif; line-height: 1.2; display: block;">
                                        {!! $slot !!}
                                    </span>
                                </td>
                            @endif
                        </tr>
                    </table>
                </a>
            </td>
        </tr>
    </table>
</td>
</tr>

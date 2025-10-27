@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'IntelliTower')
<div style="font-size: 28px; font-weight: bold; color: #667eea; text-decoration: none; font-family: 'Arial', sans-serif;">
🏢 IntelliTower
</div>
<div style="font-size: 12px; color: #666; margin-top: 5px;">
Sistema de Administración de Condominios
</div>
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>

@props(['url'])
<tr>
<td class="header">
<a href="http://ancovabd.com/" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://ss4.ancovabd.com/images/ancovabr.png" class="logo" alt="Laravel Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>

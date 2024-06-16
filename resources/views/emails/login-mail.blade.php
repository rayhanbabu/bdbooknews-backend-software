<x-mail::message>


{{$details['body']}}

<x-mail::panel>
{{$details['otp_code']}}
</x-mail::panel>


Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

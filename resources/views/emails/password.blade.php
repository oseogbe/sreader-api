<x-mail::message>
<p><strong>Hello!</strong></p>
<p>You are receiving this email because we received a request to reset the password for your account.</p>
Your six-digit PIN is <h4 style="display: inline;">{{$pin}}</h4>
<p>This password reset pin will expire in 60 minutes.</p>
<p>If you did not request a password reset, no further action is required.</p>

Regards,<br>
{{ config('app.name') }}
</x-mail::message>

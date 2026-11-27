<x-mail::message>
# Reset your password

Hi {{ $name }}, use this code in the Panobooking app or on the website to choose a new password:

<x-mail::panel>
<div style="font-size: 28px; letter-spacing: 6px; font-weight: 700; text-align: center;">{{ $code }}</div>
</x-mail::panel>

The code expires in {{ $minutes }} minutes. If you did not ask to reset your password, you can ignore this email; your password stays the same.

Thanks,<br>
The Panobooking team
</x-mail::message>

<x-mail::message>
# Welcome to Panobooking, {{ $name }}

Enter this code in the Panobooking app or on the website to verify your email address:

<x-mail::panel>
<div style="font-size: 28px; letter-spacing: 6px; font-weight: 700; text-align: center;">{{ $code }}</div>
</x-mail::panel>

Or verify with one click:

<x-mail::button :url="$link">
Verify my email
</x-mail::button>

The code and link expire in {{ $minutes }} minutes. If you did not create an account, you can ignore this email.

Thanks,<br>
The Panobooking team
</x-mail::message>

@component('mail::message')

**Essential Life Community**

Hi {{ $user['name'] }},

We are pleased to inform you that the {{ strtolower($type) }} you submitted has been approved. 

It may take a couple of hours for your entry to be displayed in the app.

Thank you!  
The Essential Life App Team

@component('mail::footer')
@endcomponent
@endcomponent
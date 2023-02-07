@component('mail::message')

**Essential Life Community**

Hi {{ $user['name'] }},

We would like to advise you that the question you submitted to the Essential Life App has not been approved. The Moderation Team have deemed the content unsuitable for the All Questions section.

Thank you,  
The Essential Life App Team

@component('mail::footer')
@endcomponent
@endcomponent
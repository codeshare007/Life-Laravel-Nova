@component('mail::message')

**Essential Life Community**

Hi {{ $user['name'] }},

Thanks for taking the time to create the {{ strtolower($type) }}.

'{{ $name }}' has been sent to the Essential Life Community for review.

We are building a strong community where everybody can share their oil knowledge.
We will be in contact in due course.


Thank you!  
The Essential Life App Team

@component('mail::footer')
@endcomponent
@endcomponent
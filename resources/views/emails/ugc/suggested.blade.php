@component('mail::message')

**Essential Life Community**

Hi {{ $user['name'] }},

Thank you so much for taking the time to suggest the {{ strtolower($type) }}.
"{{ $name }}" has been shared with the Essential Life Community for review.

Researching the relevant content for {{ strtolower(str_plural($type)) }} is a lengthy process. Your suggestion will be taken into consideration.

Thank you!  
The Essential Life App Team

@component('mail::footer')
@endcomponent
@endcomponent
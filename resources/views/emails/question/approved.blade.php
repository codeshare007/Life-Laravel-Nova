@component('mail::message')

**Essential Life Community**

Hi {{ $user['name'] }},

We are pleased to inform you that the question you submitted to the Essential Life App has been approved. Your question [**{{ $question['title'] }}**]({{ $url }}) will appear in the All Questions section.

Thanks again for contributing to our vibrant Community!  
The Essential Life App Team

@component('mail::footer')
@endcomponent
@endcomponent
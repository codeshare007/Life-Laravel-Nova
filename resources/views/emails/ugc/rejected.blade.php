@component('mail::message')

**Essential Life Community**

Hi {{ $user['name'] }},

We are sorry to say that the content you submitted has been rejected.

Please see the information below outlining the reason for this decision.
The next step is to amend the content and then re-submit your {{ strtolower($type) }}.

**{{ $rejection_reason_subject }}**

{{ $rejection_reason_description }}

Thank you!  
The Essential Life App Team

@component('mail::footer')
@endcomponent
@endcomponent
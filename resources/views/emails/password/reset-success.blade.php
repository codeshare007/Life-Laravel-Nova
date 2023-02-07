@component('mail::message')
<p>
<img src="{{ asset('images/email/padlock.png') }}" width="182">
</p>

# Password reset confirmation

Your password has been changed for your account.  
If you did change password, no further action is required.  
If you did not change password, protect your account.  
You can find help [here](https://app.essentiallife.com/status.html)

Thank you!  
The Essential Life App Team

@component('mail::footer')
@endcomponent
@endcomponent
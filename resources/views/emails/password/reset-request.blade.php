@component('mail::message')
<p>
<img src="{{ asset('images/email/padlock.png') }}" width="182">
</p>

# Password reset

In order to change your password, you must tap the button below on your **iPhone or Android device ONLY**.

Once you have verified your email on your  
**iPhone or Android**,  
you will be taken to the Essential Life app  
where you can create a new password.

@component('mail::button', ['url' => $url])
Verify email
@endcomponent

Thank you!  
The Essential Life team

@component('mail::footer')
If you are having trouble tapping the ‘Verify email’ button, copy and paste the URL below into your phone’s web browser
<br>
<br>
<span class="break">[{{ $url }}]({{ $url }})</span>
@endcomponent
@endcomponent
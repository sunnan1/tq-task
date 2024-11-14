@component('mail::message')
    <p>
        Your OTP code is: <b>{{ $user->otp }}</b>
    </p>
    <br>
    Thank you for sharing your content with us!

    Regards,
    {{ config('app.name') }}
@endcomponent

<p>Hi {{ $first_name }},</p>

<p>
    Please use this OTP to reset your password and it will be valid only for an hour.
</p>

<p>Password Reset Code: <strong>{{ $otp }}</strong></p>

<p>
    If you have any questions, just reply to this email—we're always happy to help out.
</p>

<p>
    Cheers,
    <br />
    {{ config('constants.app_name') }}
</p>
<x-mail::message>
# Hello {{ $user->name }},

@if($isSuspended)
Your account has been **suspended** by the administrator.

**Reason for suspension:**
{{ $reason }}

If you believe this is a mistake, please contact our support team.
@else
Great news! Your account has been **unsuspended**. You can now log in and use our services again.

**Note from admin:**
{{ $reason }}
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

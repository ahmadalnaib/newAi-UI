<x-mail::message>
# Hello, {{ $userName }}

Thank you for subscribing to our {{ $planDetails['name'] }} plan!

Your subscription details:
- Plan: {{ $planDetails['name'] }}
- Amount: {{ strtoupper($planDetails['currency']) }} {{ $planDetails['amount'] }}


<x-mail::button :url="$invoiceUrl">
Download Invoice
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
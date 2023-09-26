<x-mail::message>
{{$subject}}

{{$message}}

<x-mail::button :url="''">
Visitez notre plateforme
</x-mail::button>

Coordialement,<br>
{{ config('app.name') }}
</x-mail::message>

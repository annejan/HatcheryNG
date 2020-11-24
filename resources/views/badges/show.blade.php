<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hatchery NG: Badges</title>
</head>
<body>
    <h1>Badges</h1>
    <p>
        Badge added: {{ $badge->created_at }}
    </p>
    @if($badge->constraints)
    <p>
        Constraints:
        <pre>{{ $badge->constraints }}</pre>
    </p>
    @endif
    @if($badge->commands)
    <p>
        Constraints:
        <pre>{{ $badge->commands }}</pre>
    </p>
    @endif
    @if($projects->total() > 0)
    <h2>Projects:</h2>
    @include('partials.projects')
    @endif
</body>

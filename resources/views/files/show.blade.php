<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hatchery NG: Badges</title>
</head>
<body>
    <h1>File: {{ $file->name }}</h1>
    @if($file->editable)
    <pre>
        {{ $file->content }}
    </pre>
    @elseif($file->viewable)
        @if ($file->viewable === 'image')
            <img src="{{ route('files.download', $file) }}" alt="{{ $file->name }}" />
        @elseif ($file->viewable === 'audio')
            <audio controls>
                <source src="{{ route('files.download', $file) }}" type="{{ $file->mime }}">
            </audio>
        @else
            This type needs a player!!
        @endif
    @else
        <table>
            <thead>
                <tr>
                    <th>This file is unfortunately not viewable.</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>File size:</td>
                    <td>{{ \App\Support\Helpers::formatBytes($file->size_of_content) }}</td>
                </tr>
                <tr>
                    <td>Mime:</td>
                    <td>{{ $file->mime }}</td>
                </tr>
                <tr>
                    <td>Download:</td>
                    <td><a href="{{ route('files.download', $file)  }}">{{ $file->name }}</a></td>
                </tr>
            </tbody>
        </table>
    @endif
</body>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hatchery NG: Badges</title>
</head>
<body>
    <script type="application/ld+json">
    {
      "@context" : "https://schema.org",
      "@type" : "SoftwareApplication",
      "name" : "{{ $project->name }}",
      "url" : "{{ route('projects.show', ['project' => $project->slug]) }}",
      "author" : {
        "@type" : "Person",
        "name" : "{{ $project->user->name }}"
      },
    @if($project->versions()->published()->exists())
      "downloadUrl" : "{{ url($project->versions()->published()->get()->last()->zip) }}",
    @endif
      "operatingSystem" : "MicroPython",
      "requirements" : "badge.team firmware",
      "softwareVersion" : "{{ $project->revision }}",
      "applicationCategory" : "{{ $project->category }}",
    @if($project->votes->count() > 0)
      "aggregateRating" : {
        "@type": "AggregateRating",
        "ratingValue": "{{ $project->score }}",
        "reviewCount": "{{ $project->votes->count() }}",
        "bestRating": 1,
        "worstRating": 0
      },
    @endif
      "offers": {
        "@type": "Offer",
        "price": "0.00",
        "priceCurrency": "EUR"
      }
    }
    </script>
    <h1>{{ $project->name }}</h1>
    @if($project->git)
        <img src="{{ asset('img/git.svg') }}" alt="Git revision: {{ $project->git_commit_id}}" class="collab-icon" />
    @endif
    @if(!$project->collaborators->isEmpty())
        <img src="{{ asset('img/collab.svg') }}" alt="{{ $project->collaborators()->count() . ' ' . \Illuminate\Support\Str::plural('collaborator', $project->collaborators()->count()) }}" class="collab-icon" />
    @endif
    <strong>{{ $project->name }}</strong> rev. {{ $project->revision }} (by {{ $project->user->name }})

    <p>
        {!! $project->descriptionHtml !!}
    </p>
    <strong>Category: {{ $project->category }}</strong>
    <hr>
    <strong>Status: {{ $project->status }}</strong>
    <hr>
    @if($project->min_firmware)
    Minimal firmware version: {{ $project->min_firmware }}
    @endif
    @if($project->min_firmware && $project->max_firmware)
    <br>
    @endif
    @if($project->max_firmware)
    Maximum firmware version: {{ $project->max_firmware }}
    @endif
    @if($project->min_firmware || $project->max_firmware)
    <hr>
    @endif

    @if($project->versions()->published()->count() > 0)
    <div>
        <a href="{{ url($project->versions()->published()->get()->last()->zip) }}" class="btn btn-default">Download latest egg (tar.gz)</a>
    </div>
    @endif
    <p>
    @include('projects.partials.show-files')
    </p>
</body>

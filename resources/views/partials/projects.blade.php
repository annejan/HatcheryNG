<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Rev</th>
        <th>Egg</th>
        <th>Content</th>
        <th>Cat</th>
        <th>Collab</th>
        <th>Last release</th>
    </tr>
    </thead>
    <tbody>
    @forelse($projects as $project)
        <tr>
            <td>
                <a href="{{ route('projects.show', ['project' => $project->slug]) }}">{{ $project->name }}</a>
            </td>
            <td>{{ $project->versions()->published()->exists() ? $project->versions()->published()->get()->last()->revision : 'unreleased' }}</td>
            <td>{{ $project->size_of_zip_formatted }}</td>
            <td>{{ $project->size_of_content_formatted }}</td>
            <td>{{ $project->category }}</td>
            <td>
            @if($project->git)
                <img src="{{ asset('img/git.svg') }}" alt="Git revision: {{ $project->git_commit_id}}" class="collab-icon" />
            @endif
            @if(!$project->collaborators->isEmpty())
                <img src="{{ asset('img/collab.svg') }}" alt="{{ $project->collaborators()->count() . ' ' . \Illuminate\Support\Str::plural('collaborator', $project->collaborators()->count()) }}" class="collab-icon" />
            @endif
            </td>
            <td>{{ $project->versions()->published()->exists() ? $project->versions()->published()->get()->last()->updated_at : '-' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="7">No Eggs published yet</td>
        </tr>
    @endforelse
    </tbody>
</table>
{{ $projects }}

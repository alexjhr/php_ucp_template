<code 
    class="bg-dark p-1 rounded {{ isset($hidden) ? 'text-spoiler' : '' }}"
    role="button"
    data-toggle="tooltip"
    data-placement="top"
    data-trigger="click focus"
    data-title="Copied!"
    data-clipboard-text="{{ $text }}"
>{{ $text }}</code>
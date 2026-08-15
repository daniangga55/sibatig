@php
    $primary = \App\Models\WebsiteSetting::themeColor('primary_color', '#1769d2');
    $accent = \App\Models\WebsiteSetting::themeColor('accent_color', '#f3b73f');
    $sidebar = \App\Models\WebsiteSetting::themeColor('sidebar_color', '#061b3b');
    $canvas = \App\Models\WebsiteSetting::themeColor('canvas_color', '#f4f7fb');
@endphp

<meta name="theme-color" content="{{ $sidebar }}">
<style id="sibatig-theme-variables">
    :root {
        --sibatig-primary: {{ $primary }};
        --sibatig-accent: {{ $accent }};
        --sibatig-sidebar: {{ $sidebar }};
        --sibatig-canvas: {{ $canvas }};
    }
</style>

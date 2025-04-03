@if ($componentName)
    <x-dynamic-component class="mt-4" :component="$componentName" :data="new Illuminate\View\ComponentAttributeBag($data)" />
@endif
@if ($content)
    {!! nl2br($content) !!}
@endif

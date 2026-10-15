<div class="text-center py-5">
    <div class="empty-state-icon mb-3"><i class="bi {{ $icon ?? 'bi-inbox' }}"></i></div>
    <p class="text-muted mb-0">{{ $message }}</p>
    @isset($action)
        <div class="mt-3">{!! $action !!}</div>
    @endisset
</div>

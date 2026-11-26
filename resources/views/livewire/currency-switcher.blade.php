<div>
    <label class="visually-hidden" for="currency-switcher">Currency</label>
    <select id="currency-switcher" class="form-select currency-select" wire:model.live="code" title="Display prices in">
        @foreach ($codes as $c)
            <option value="{{ $c }}">{{ $c }}</option>
        @endforeach
    </select>
</div>

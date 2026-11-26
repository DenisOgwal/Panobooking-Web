{{-- Photo upload with preview for the "list with us" forms (component uses RegistersListing). --}}
<div class="mb-3">
    <label class="form-label" for="f-formFile">Main photo</label>
    <div class="d-flex flex-column flex-sm-row gap-3 align-items-sm-center">
        <div class="rounded-3 border bg-canvas overflow-hidden flex-shrink-0 d-flex photo-preview">
            @if ($formFile && ! $errors->has('formFile') && method_exists($formFile, 'temporaryUrl'))
                <img src="{{ $formFile->temporaryUrl() }}" alt="Preview" class="w-100 h-100 object-fit-cover">
            @else
                <i class="bi bi-image fs-2 text-muted m-auto"></i>
            @endif
        </div>
        <div class="flex-grow-1">
            <input id="f-formFile" type="file" accept="image/jpeg,image/png,image/webp" wire:model="formFile"
                   @class(['form-control', 'is-invalid' => $errors->has('formFile')])>
            <div class="form-text" wire:loading.remove wire:target="formFile">JPEG, PNG or WebP, up to 5 MB. A bright, landscape photo works best.</div>
            <div class="form-text text-primary" wire:loading wire:target="formFile"><span class="spinner-border spinner-border-sm me-1"></span>Uploading…</div>
            @error('formFile')<div class="form-error">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

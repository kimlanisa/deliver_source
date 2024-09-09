<div class="block block-rounded">
    <div class="block-header block-header-default">
        <div>
            <h3 class="block-title">
                DETAIL {{ $custom_menu->name }}
            </h3>
        </div>
        <div data-bs-dismiss="modal" style="cursor: pointer">
            <i class="fas fa-times"></i>
        </div>
    </div>

    <div class="block-content block-content-full">
        <div class="row g-4">
            @foreach ($custom_menu->label_data as $item)
            <div class="{{ $item['type_input'] === 'textarea' ? 'col-6' : 'col-4' }}">
                <label class="form-label">{{ $item['label'] }}</label>
                @switch($item['type_input'])
                    @case('date')
                        <input type="date" value="{{ old($item['name'], $data[$item['name']] ?? '') }}"
                            class="form-control @error($item['name']) is-invalid @enderror" name="{{ $item['name'] }}"
                        readonly disabled />
                    @break
                    @case('number')
                        <input type="number" value="{{ old($item['name'], $data[$item['name']] ?? '') }}"
                            class="form-control @error($item['name']) is-invalid @enderror" name="{{ $item['name'] }}"
                         readonly disabled/>
                    @break
                    @case('textarea')
                        <textarea
                            class="form-control @error($item['name']) is-invalid @enderror" name="{{ $item['name'] }}"
                        readonly disabled>{{ old($item['name'], $data[$item['name']] ?? '') }}</textarea>
                    @break
                    @case('select')
                        <select name="{{ $item['name'] }}"
                            class="form-control @error($item['name']) is-invalid @enderror" readonly disabled>
                            <option value="">Pilih {{ $item['label'] }}</option>
                            @foreach ($item['select_option'] as $option)
                                <option value="{{ $option['label'] }}"
                                    {{ old($item['name'], $data[$item['name']] ?? '') == $option['label'] ? 'selected' : '' }}>
                                    {{ $option['label'] }}
                                </option>
                            @endforeach
                        </select>
                    @break
                    @default
                    <input type="text" value="{{ old($item['name'], $data[$item['name']] ?? '') }}"
                    class="form-control @error($item['name']) is-invalid @enderror" name="{{ $item['name'] }}"
                readonly disabled />
                @endswitch
                @error($item['name'])
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            @endforeach
        </div>
    </div>
</div>

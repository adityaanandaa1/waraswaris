@props([
    'name' => 'tanggal',
    'placeholder' => 'Pilih tanggal',
])

<div class="datepicker-wrapper">
    <div class="datepicker-box">
        <input type="text" 
            class="datepicker-input"
            id="{{ $name }}Input"
            value="{{ request($name)
                    ? \Carbon\Carbon::parse(request($name))->translatedFormat('d F Y')
                    : \Carbon\Carbon::today()->translatedFormat('d F Y')
                }}"
            placeholder="{{ $placeholder }}"
            readonly>

        <span class="datepicker-icon">
            {{ $icon ?? '' }}
        </span>
    </div>
    <div class="datepicker hidden">
        <div class="datepicker-header">
            <button type="button" class="datepicker-prevBtn">&lt;</button>
            <div class="datepicker-monthYear"></div>
            <button type="button" class="datepicker-nextBtn">&gt;</button>
        </div>

        <div class="datepicker-days">
            <div class="datepicker-day">Senin</div>
            <div class="datepicker-day">Selasa</div>
            <div class="datepicker-day">Rabu</div>
            <div class="datepicker-day">Kamis</div>
            <div class="datepicker-day">Jumat</div>
            <div class="datepicker-day">Sabtu</div>
            <div class="datepicker-day">Minggu</div>
        </div>

        <div class="datepicker-dates"></div>
        <div class="datepicker-month hidden"></div>
        <div class="datepicker-year hidden"></div>
    </div>

    
    <input type="hidden"
        class="datepicker-hidden"
        name="{{ $name }}"
        value="{{ request($name) ?? date('Y-m-d') }}">
</div>
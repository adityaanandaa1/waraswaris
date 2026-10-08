<div class="monthpicker-wrapper">
  <div class="monthpicker-box">
    <input type="text" 
           class="monthpicker-input"
           id="monthpickerInput"
           value="{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}"
           readonly>
    <span class="monthpicker-icon">{{ $icon ?? '' }}</span>
  </div>

  <div class="monthpicker" id="monthpickerPanel">
    <div class="monthpicker-header">
      <button id="prevBtn"><i class="ri-arrow-left-s-line"></i></button>
      <div class="monthpicker-monthYear" id="monthYear"></div>
      <button id="nextBtn"><i class="ri-arrow-right-s-line"></i></button>
    </div>

    <div class="monthpicker-months">
      <div class="monthpicker-month">Januari</div>
      <div class="monthpicker-month">Februari</div>
      <div class="monthpicker-month">Maret</div>
      <div class="monthpicker-month">April</div>
      <div class="monthpicker-month">Mei</div>
      <div class="monthpicker-month">Juni</div>
      <div class="monthpicker-month">Juli</div>
      <div class="monthpicker-month">Agustus</div>
      <div class="monthpicker-month">September</div>
      <div class="monthpicker-month">Oktober</div>
      <div class="monthpicker-month">November</div>
      <div class="monthpicker-month">Desember</div>
    </div>

    <div id="year" class="monthpicker-year hidden"></div>
  </div>
</div>
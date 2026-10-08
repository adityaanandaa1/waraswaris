@props(['pasien', 'size' => 'md'])

@php
$sizeMap = [
    'sm' => ['dim' => 32, 'font' => 12],
    'md' => ['dim' => 48, 'font' => 16],
    'lg' => ['dim' => 64, 'font' => 20],
    'xl' => ['dim' => 96, 'font' => 32],
];

$sizeConf = $sizeMap[$size] ?? $sizeMap['md'];
$dim      = $sizeConf['dim'];
$fontSize = $sizeConf['font'];

$fotoPath   = $pasien->foto_path ?? null;
$isUrl      = $fotoPath && filter_var($fotoPath, FILTER_VALIDATE_URL);
$fileExists = $fotoPath && !$isUrl && file_exists(public_path($fotoPath));
$hasFoto    = (bool) ($isUrl || $fileExists);
$inisial    = $pasien ? strtoupper(mb_substr($pasien->nama_pasien, 0, 1)) : '?';

$fallbackHtml = "<div style=\"width:100%;height:100%;background:linear-gradient(135deg,#60a5fa,#2563eb);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:{$fontSize}px;\">{$inisial}</div>";
$fallbackHtmlAttr = str_replace('"', '&quot;', $fallbackHtml);
@endphp

<div style="width:{{ $dim }}px;height:{{ $dim }}px;border-radius:9999px;overflow:hidden;" class="{{ $attributes->get('class') }}">
    @if($hasFoto)
        <img 
            src="{{ $isUrl ? $fotoPath : asset($fotoPath) }}" 
            alt="{{ $pasien->nama_pasien }}"
            style="width:100%;height:100%;object-fit:cover;"
            onerror="this.onerror=null; this.parentElement.innerHTML='{!! $fallbackHtmlAttr !!}';"
        >
    @else
        {!! $fallbackHtml !!}
    @endif
</div>

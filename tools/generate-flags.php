<?php
declare(strict_types=1);

// One-off generator for the flag/visa-area icon set used by the destinations directory.
// Run once locally (php tools/generate-flags.php) then delete; assets are committed, not regenerated at runtime.

$outDir = __DIR__ . '/../assets/images/flags';
if (!is_dir($outDir)) mkdir($outDir, 0777, true);

function starPts(float $cx, float $cy, float $rOuter, float $rInner, int $points = 5, float $rotationDeg = -90): string {
    $pts = [];
    $step = 180 / $points;
    for ($i = 0; $i < $points * 2; $i++) {
        $r = ($i % 2 === 0) ? $rOuter : $rInner;
        $angle = deg2rad($rotationDeg + $i * $step);
        $pts[] = round($cx + $r * cos($angle), 2) . ',' . round($cy + $r * sin($angle), 2);
    }
    return implode(' ', $pts);
}

function star(float $cx, float $cy, float $rOuter, string $fill, int $points = 5, float $rotationDeg = -90, ?float $rInnerRatio = null): string {
    $ratio = $rInnerRatio ?? 0.381966; // classic 5-point star proportion
    $rInner = $rOuter * $ratio;
    return '<polygon points="' . starPts($cx, $cy, $rOuter, $rInner, $points, $rotationDeg) . '" fill="' . $fill . '"/>';
}

function crescent(float $cx, float $cy, float $r, float $offsetFrac, string $moonColor, string $bgColor): string {
    $offset = $r * $offsetFrac;
    return '<circle cx="' . $cx . '" cy="' . $cy . '" r="' . $r . '" fill="' . $moonColor . '"/>'
         . '<circle cx="' . ($cx + $offset) . '" cy="' . $cy . '" r="' . ($r * 0.82) . '" fill="' . $bgColor . '"/>';
}

function rays(float $cx, float $cy, float $rInner, float $rOuter, int $count, string $fill): string {
    $out = '';
    for ($i = 0; $i < $count; $i++) {
        $a1 = deg2rad(($i / $count) * 360 - 90 - (180 / $count) * 0.42);
        $a2 = deg2rad(($i / $count) * 360 - 90 + (180 / $count) * 0.42);
        $x1 = round($cx + $rInner * cos($a1), 2); $y1 = round($cy + $rInner * sin($a1), 2);
        $x2 = round($cx + $rOuter * cos(deg2rad(($i / $count) * 360 - 90)), 2); $y2 = round($cy + $rOuter * sin(deg2rad(($i / $count) * 360 - 90)), 2);
        $x3 = round($cx + $rInner * cos($a2), 2); $y3 = round($cy + $rInner * sin($a2), 2);
        $out .= "<polygon points=\"$x1,$y1 $x2,$y2 $x3,$y3\" fill=\"$fill\"/>";
    }
    return $out;
}

function svg(string $viewBox, string $inner): string {
    return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="' . $viewBox . '" preserveAspectRatio="xMidYMid meet">' . $inner . '</svg>' . "\n";
}

function write(string $dir, string $name, string $content): void {
    file_put_contents($dir . '/' . $name . '.svg', $content);
    echo "wrote $name.svg\n";
}

// ---- 3:2 flags (viewBox 0 0 300 200) unless noted ----

// Turkiye
write($outDir, 'tr', svg('0 0 300 200', '<rect width="300" height="200" fill="#E30A17"/>' . crescent(122, 100, 34, 0.32, '#fff', '#E30A17') . star(172, 100, 13, '#fff')));

// Azerbaijan
write($outDir, 'az', svg('0 0 300 200', '<rect width="300" height="66.7" fill="#00B5E2"/><rect y="66.7" width="300" height="66.6" fill="#EF3340"/><rect y="133.3" width="300" height="66.7" fill="#009639"/>' . crescent(150, 100, 22, 0.3, '#fff', '#EF3340') . star(188, 100, 9, '#fff', 8)));

// Georgia (Five Cross flag, simplified crosses)
$geCorner = '';
foreach ([[52.5,50],[247.5,50],[52.5,150],[247.5,150]] as $c) {
    [$cx,$cy]=$c;
    $geCorner .= "<rect x=\"".($cx-16)."\" y=\"".($cy-4)."\" width=\"32\" height=\"8\" fill=\"#FF0000\"/><rect x=\"".($cx-4)."\" y=\"".($cy-16)."\" width=\"8\" height=\"32\" fill=\"#FF0000\"/>";
}
write($outDir, 'ge', svg('0 0 300 200', '<rect width="300" height="200" fill="#fff"/><rect x="128" width="44" height="200" fill="#FF0000"/><rect y="78" width="300" height="44" fill="#FF0000"/>' . $geCorner));

// Egypt (eagle simplified to a gold roundel + wing marks)
write($outDir, 'eg', svg('0 0 300 200', '<rect width="300" height="66.7" fill="#CE1126"/><rect y="66.7" width="300" height="66.6" fill="#fff"/><rect y="133.3" width="300" height="66.7" fill="#000"/>' . '<g fill="#C09300"><ellipse cx="150" cy="100" rx="22" ry="16"/><path d="M128 96c-20-4-34-2-44 6 14 6 28 6 40 2z"/><path d="M172 96c20-4 34-2 44 6-14 6-28 6-40 2z"/><rect x="142" y="112" width="16" height="10"/></g>'));

// Bahrain (serration simplified to 5 white triangles)
$bhTeeth = '';
for ($i = 0; $i < 5; $i++) { $y0 = $i * 40; $bhTeeth .= "<polygon points=\"80,$y0 110,".($y0+20)." 80,".($y0+40)."\" fill=\"#CE1126\"/>"; }
write($outDir, 'bh', svg('0 0 300 200', '<rect width="300" height="200" fill="#CE1126"/><rect width="80" height="200" fill="#fff"/>' . $bhTeeth));

// Qatar (maroon + serrated white band, simplified)
$qaTeeth = '';
for ($i = 0; $i < 6; $i++) { $y0 = $i * 33.3; $qaTeeth .= "<polygon points=\"70,$y0 100,".($y0+16.65)." 70,".($y0+33.3)."\" fill=\"#8D1B3D\"/>"; }
write($outDir, 'qa', svg('0 0 300 200', '<rect width="300" height="200" fill="#8D1B3D"/><rect width="70" height="200" fill="#fff"/>' . $qaTeeth));

// Oman (simplified khanjar omitted, red hoist band + white/red/green)
write($outDir, 'om', svg('0 0 300 200', '<rect x="90" width="210" height="66.7" fill="#fff"/><rect x="90" y="66.7" width="210" height="66.6" fill="#DB161B"/><rect x="90" y="133.3" width="210" height="66.7" fill="#008000"/><rect width="90" height="200" fill="#DB161B"/><path d="M55 90l10-14 10 14v10l-10 8-10-8z" fill="#fff"/>'));

// Kuwait (trapezoid simplified)
write($outDir, 'kw', svg('0 0 300 200', '<rect width="300" height="66.7" fill="#007A3D"/><rect y="66.7" width="300" height="66.6" fill="#fff"/><rect y="133.3" width="300" height="66.7" fill="#CE1126"/><polygon points="0,0 70,0 30,100 70,200 0,200" fill="#000"/>'));

// UAE
write($outDir, 'ae', svg('0 0 300 200', '<rect x="75" width="225" height="66.7" fill="#00732F"/><rect x="75" y="66.7" width="225" height="66.6" fill="#fff"/><rect x="75" y="133.3" width="225" height="66.7" fill="#000"/><rect width="75" height="200" fill="#FF0000"/>'));

// Pakistan
write($outDir, 'pk', svg('0 0 300 200', '<rect width="300" height="200" fill="#01411C"/><rect width="75" height="200" fill="#fff"/>' . crescent(190, 100, 32, 0.28, '#fff', '#01411C') . star(235, 78, 13, '#fff')));

// Uzbekistan
write($outDir, 'uz', svg('0 0 300 200', '<rect width="300" height="200" fill="#0099B5"/><rect y="62" width="300" height="8" fill="#CE1126"/><rect y="70" width="300" height="60" fill="#fff"/><rect y="130" width="300" height="8" fill="#CE1126"/><rect y="138" width="300" height="62" fill="#1EB53A"/>' . crescent(60, 38, 16, 0.32, '#fff', '#0099B5') . star(100, 22, 7, '#fff') . star(122, 22, 7, '#fff') . star(144, 22, 7, '#fff') . star(100, 46, 7, '#fff') . star(122, 46, 7, '#fff') . star(144, 46, 7, '#fff')));

// Tajikistan
write($outDir, 'tj', svg('0 0 300 200', '<rect width="300" height="50" fill="#CC0000"/><rect y="50" width="300" height="100" fill="#fff"/><rect y="150" width="300" height="50" fill="#006600"/><g fill="#F8C300"><path d="M150 82l-8 10h16z"/><rect x="138" y="92" width="24" height="6" rx="2"/>' . star(122, 76, 6, '#F8C300') . star(138, 64, 6, '#F8C300') . star(162, 64, 6, '#F8C300') . star(178, 76, 6, '#F8C300') . star(150, 58, 6, '#F8C300') . star(130, 88, 6, '#F8C300') . star(170, 88, 6, '#F8C300') . '</g>'));

// Kyrgyzstan (sun simplified, 20 rays + crossing lines)
write($outDir, 'kg', svg('0 0 300 200', '<rect width="300" height="200" fill="#E8112D"/>' . rays(150, 100, 26, 46, 20, '#FFD700') . '<circle cx="150" cy="100" r="26" fill="none" stroke="#FFD700" stroke-width="4"/><g stroke="#FFD700" stroke-width="3"><line x1="128" y1="80" x2="172" y2="120"/><line x1="172" y1="80" x2="128" y2="120"/><line x1="128" y1="100" x2="172" y2="100"/><line x1="150" y1="78" x2="150" y2="122"/></g>'));

// Kazakhstan (sun + simplified eagle silhouette)
write($outDir, 'kz', svg('0 0 300 200', '<rect width="300" height="200" fill="#00AFCA"/>' . rays(150, 78, 16, 34, 24, '#FEC50C') . '<circle cx="150" cy="78" r="16" fill="#FEC50C"/><path d="M110 125c14-10 30-12 40-6 10-6 26-4 40 6-8 14-22 20-40 16-18 4-32-2-40-16z" fill="#FEC50C"/>'));

// India (Ashoka Chakra, 24 spokes)
$chakra = '<circle cx="150" cy="100" r="24" fill="none" stroke="#000080" stroke-width="4"/><circle cx="150" cy="100" r="3" fill="#000080"/>';
for ($i = 0; $i < 24; $i++) { $a = deg2rad($i * 15); $x = round(150 + 24 * cos($a), 2); $y = round(100 + 24 * sin($a), 2); $chakra .= "<line x1=\"150\" y1=\"100\" x2=\"$x\" y2=\"$y\" stroke=\"#000080\" stroke-width=\"1.6\"/>"; }
write($outDir, 'in', svg('0 0 300 200', '<rect width="300" height="66.7" fill="#FF9933"/><rect y="66.7" width="300" height="66.6" fill="#fff"/><rect y="133.3" width="300" height="66.7" fill="#138808"/>' . $chakra));

// Sri Lanka (heavily simplified: hoist bands + maroon field + gold disc emblem)
write($outDir, 'lk', svg('0 0 300 200', '<rect width="300" height="200" fill="#8D153A"/><rect width="20" height="200" fill="#FFB700"/><rect x="20" width="24" height="100" fill="#00534E"/><rect x="20" y="100" width="24" height="100" fill="#FF8200"/><rect x="20" width="4" height="200" fill="#FFB700"/><circle cx="185" cy="100" r="34" fill="none" stroke="#FFB700" stroke-width="5"/><circle cx="185" cy="100" r="16" fill="#FFB700"/>'));

// Malaysia (14 stripes, simplified 14-point star as generated polygon)
$myStripes = '';
for ($i = 0; $i < 14; $i++) { $h = 200/14; $myStripes .= '<rect y="'.round($i*$h,2).'" width="300" height="'.round($h,2).'" fill="'.($i%2===0?'#CC0001':'#fff').'"/>'; }
write($outDir, 'my', svg('0 0 300 200', $myStripes . '<rect width="150" height="107.14" fill="#010066"/>' . crescent(60, 53.5, 26, 0.32, '#FFCC00', '#010066') . star(105, 53.5, 20, '#FFCC00', 14, -90, 0.55)));

// Singapore
write($outDir, 'sg', svg('0 0 300 200', '<rect width="300" height="100" fill="#EF3340"/><rect y="100" width="300" height="100" fill="#fff"/>' . crescent(70, 50, 24, 0.34, '#fff', '#EF3340') . star(122, 30, 9, '#fff') . star(140, 46, 9, '#fff') . star(134, 68, 9, '#fff') . star(110, 68, 9, '#fff') . star(104, 46, 9, '#fff')));

// Indonesia
write($outDir, 'id', svg('0 0 300 200', '<rect width="300" height="100" fill="#CE1126"/><rect y="100" width="300" height="100" fill="#fff"/>'));

// Thailand
write($outDir, 'th', svg('0 0 300 200', '<rect width="300" height="200" fill="#fff"/><rect y="33.3" width="300" height="33.3" fill="#00247D"/><rect y="66.7" width="300" height="66.6" fill="#F4F5F8"/><rect y="66.7" width="300" height="66.6" fill="#241D4F" opacity="0"/><rect y="0" width="300" height="33.3" fill="#A51931"/><rect y="166.7" width="300" height="33.3" fill="#A51931"/><rect y="66.7" width="300" height="66.6" fill="#241D4F"/>'));

// China (five stars)
$cnSmall = star(88,30,7,'#FFDE00',5,-58) . star(104,46,7,'#FFDE00',5,-30) . star(104,70,7,'#FFDE00',5,30) . star(88,86,7,'#FFDE00',5,60);
write($outDir, 'cn', svg('0 0 300 200', '<rect width="300" height="200" fill="#DE2910"/>' . star(58, 58, 20, '#FFDE00') . $cnSmall));

// Japan
write($outDir, 'jp', svg('0 0 300 200', '<rect width="300" height="200" fill="#fff"/><circle cx="150" cy="100" r="42" fill="#BC002D"/>'));

// South Korea (simplified taegeuk + 4 trigrams)
write($outDir, 'kr', svg('0 0 300 200', '<rect width="300" height="200" fill="#fff"/><path d="M150 64a36 36 0 010 72 18 18 0 010-36 18 18 0 000-36z" fill="#CD2E3A"/><path d="M150 64a36 36 0 000 72 18 18 0 000-36 18 18 0 010-36z" fill="#0047A0"/>' . '<g fill="#000"><rect x="50" y="46" width="30" height="4"/><rect x="50" y="54" width="30" height="4"/><rect x="50" y="62" width="30" height="4"/><rect x="50" y="138" width="13" height="4"/><rect x="67" y="138" width="13" height="4"/><rect x="50" y="146" width="30" height="4"/><rect x="50" y="154" width="13" height="4"/><rect x="67" y="154" width="13" height="4"/><rect x="220" y="46" width="13" height="4"/><rect x="237" y="46" width="13" height="4"/><rect x="220" y="54" width="30" height="4"/><rect x="220" y="62" width="13" height="4"/><rect x="237" y="62" width="13" height="4"/><rect x="220" y="138" width="30" height="4"/><rect x="220" y="146" width="30" height="4"/><rect x="220" y="154" width="30" height="4"/></g>'));

// Philippines
write($outDir, 'ph', svg('0 0 300 200', '<rect width="300" height="100" fill="#0038A8"/><rect y="100" width="300" height="100" fill="#CE1126"/><polygon points="0,0 150,100 0,200" fill="#fff"/>' . rays(45, 100, 10, 24, 8, '#FCD116') . star(20, 45, 8, '#FCD116') . star(20, 155, 8, '#FCD116') . star(82, 100, 8, '#FCD116')));

// Vietnam
write($outDir, 'vn', svg('0 0 300 200', '<rect width="300" height="200" fill="#DA251D"/>' . star(150, 100, 34, '#FFCD00')));

// Morocco (pentagram outline)
write($outDir, 'ma', svg('0 0 300 200', '<rect width="300" height="200" fill="#C1272D"/><polygon points="' . starPts(150, 100, 34, 13, 5, -90) . '" fill="none" stroke="#006233" stroke-width="5"/>'));

// South Africa (simplified pall)
write($outDir, 'za', svg('0 0 300 200', '<rect width="300" height="200" fill="#fff"/><polygon points="0,0 150,0 60,100 150,200 0,200" fill="#DE3831"/><polygon points="0,0 120,0 45,100 120,200 0,200" fill="#002395"/><polygon points="0,26 95,100 0,174" fill="#000"/><polygon points="0,40 78,100 0,160" fill="#FFB81C"/><polygon points="0,54 62,100 0,146" fill="#007847"/>'));

// Kenya (shield simplified)
write($outDir, 'ke', svg('0 0 300 200', '<rect width="300" height="66.7" fill="#000"/><rect y="66.7" width="300" height="10" fill="#fff"/><rect y="76.7" width="300" height="46.6" fill="#BE3A34"/><rect y="123.3" width="300" height="10" fill="#fff"/><rect y="133.3" width="300" height="66.7" fill="#006600"/><path d="M150 76l24 12v24c0 18-12 28-24 32-12-4-24-14-24-32v-24z" fill="#BE3A34" stroke="#fff" stroke-width="3"/><line x1="110" y1="66" x2="190" y2="134" stroke="#fff" stroke-width="5"/><line x1="190" y1="66" x2="110" y2="134" stroke="#fff" stroke-width="5"/>'));

// Tanzania
write($outDir, 'tz', svg('0 0 300 200', '<polygon points="0,0 300,0 0,200" fill="#1EB53A"/><polygon points="300,0 300,200 0,200" fill="#00A3DD"/><polygon points="0,178 300,0 300,22 22,200 0,200" fill="#FFD100"/><polygon points="0,186 300,8 300,14 6,192 0,192" fill="#000"/>'));

// Uganda (crane simplified)
$ugStripes = '';
$colors = ['#000','#FCDC04','#D90000','#000','#FCDC04','#D90000'];
for ($i = 0; $i < 6; $i++) { $h = 200/6; $ugStripes .= '<rect y="'.round($i*$h,2).'" width="300" height="'.round($h,2).'" fill="'.$colors[$i].'"/>'; }
write($outDir, 'ug', svg('0 0 300 200', $ugStripes . '<circle cx="150" cy="100" r="30" fill="#fff"/><path d="M135 115v-22l15-14 15 14v22z" fill="#7a5230"/><path d="M150 79l-6-10 6-2 6 2z" fill="#D90000"/>'));

// Russia
write($outDir, 'ru', svg('0 0 300 200', '<rect width="300" height="66.7" fill="#fff"/><rect y="66.7" width="300" height="66.6" fill="#0039A6"/><rect y="133.3" width="300" height="66.7" fill="#D52B1E"/>'));

// Albania (double-headed eagle silhouette, simplified)
$eagle = '<g fill="#000">'
    . '<path d="M150 60c-4 8-4 16 0 22-14 2-26 10-34 22 10-2 18-2 26 2-8 6-14 14-16 24 8-6 16-10 24-10-2 8 0 16 6 22 6-6 8-14 6-22 8 0 16 4 24 10-2-10-8-18-16-24 8-4 16-4 26-2-8-12-20-20-34-22 4-6 4-14 0-22z"/>'
    . '<circle cx="140" cy="58" r="6"/><circle cx="160" cy="58" r="6"/>'
    . '</g>';
write($outDir, 'al', svg('0 0 300 200', '<rect width="300" height="200" fill="#E41E20"/>' . $eagle));

// Bosnia and Herzegovina (triangle + diagonal stars, clipped at edges)
$baStars = '';
for ($i = -1; $i <= 6; $i++) { $cx = 60 + $i * 24; $cy = 10 + $i * 24; if ($cy > -10 && $cy < 210) $baStars .= star($cx, $cy, 9, '#fff'); }
write($outDir, 'ba', svg('0 0 300 200', '<rect width="300" height="200" fill="#002395"/><polygon points="0,0 220,0 0,200" fill="#FECB00"/>' . '<clipPath id="baClip"><rect width="300" height="200"/></clipPath><g clip-path="url(#baClip)">' . $baStars . '</g>'));

// Serbia (tricolor + simplified eagle emblem)
$rsEagle = '<g fill="#C6363C" stroke="#fff" stroke-width="1.5"><rect x="80" y="70" width="40" height="50" rx="4"/></g><circle cx="100" cy="66" r="10" fill="#FFC726"/>';
write($outDir, 'rs', svg('0 0 300 200', '<rect width="300" height="66.7" fill="#C6363C"/><rect y="66.7" width="300" height="66.6" fill="#0C4076"/><rect y="133.3" width="300" height="66.7" fill="#fff"/>' . $rsEagle));

// Schengen (neutral travel/map symbol, explicitly not a flag)
write($outDir, 'schengen', svg('0 0 300 200', '<rect width="300" height="200" fill="#EAF3FC"/><circle cx="150" cy="95" r="46" fill="none" stroke="#0867E8" stroke-width="6"/><path d="M150 49v92M104 95h92" stroke="#0867E8" stroke-width="3" opacity=".55"/><path d="M150 61a38 38 0 010 68" stroke="#0867E8" stroke-width="4" fill="none"/><circle cx="150" cy="95" r="6" fill="#0867E8"/><path d="M120 158l30-18 30 18-8 24h-44z" fill="#0867E8"/>'));

// United Kingdom (2:1) Union Jack
$gbInner = '<rect width="300" height="150" fill="#00247D"/>'
    . '<polygon points="0,0 30,0 300,140 300,150 270,150 0,10" fill="#fff"/>'
    . '<polygon points="270,0 300,0 300,10 30,150 0,150 0,140" fill="#fff"/>'
    . '<polygon points="0,0 20,0 300,146 300,150 280,150 0,4" fill="#CF142B"/>'
    . '<polygon points="280,0 300,0 300,4 20,150 0,150 0,146" fill="#CF142B"/>'
    . '<rect x="120" width="60" height="150" fill="#fff"/><rect y="60" width="300" height="30" fill="#fff"/>'
    . '<rect x="132" width="36" height="150" fill="#CF142B"/><rect y="69" width="300" height="12" fill="#CF142B"/>';
write($outDir, 'gb', svg('0 0 300 150', $gbInner));

// United States (1.9:1) stripes + 50 stars
$usStripes = '';
for ($i = 0; $i < 13; $i++) { $h = 200/13; $usStripes .= '<rect y="'.round($i*$h,2).'" width="380" height="'.round($h,2).'" fill="'.($i%2===0?'#B22234':'#fff').'"/>'; }
$cantonH = 200 * 7/13;
$usStars = '';
for ($row = 0; $row < 9; $row++) {
    $cols = ($row % 2 === 0) ? 6 : 5;
    $y = 14 + $row * (($cantonH - 28) / 8);
    for ($col = 0; $col < $cols; $col++) {
        $x = ($row % 2 === 0) ? 17 + $col * 30 : 32 + $col * 30;
        $usStars .= star($x, $y, 6, '#fff');
    }
}
write($outDir, 'us', svg('0 0 380 200', $usStripes . '<rect width="152" height="'.round($cantonH,2).'" fill="#3C3B6E"/>' . $usStars));

// Canada (1:2) simplified maple leaf
$leaf = '<path d="M150 46l6 18 18-10-4 18 16 4-14 12 10 14-18-2 2 18-12-12-12 12 2-18-18 2 10-14-14-12 16-4-4-18 18 10z" fill="#FF0000"/><rect x="146" y="112" width="8" height="18" fill="#FF0000"/>';
write($outDir, 'ca', svg('0 0 300 150', '<rect width="75" height="150" fill="#FF0000"/><rect x="75" width="150" height="150" fill="#fff"/><rect x="225" width="75" height="150" fill="#FF0000"/>' . $leaf));

// Mexico (simplified central emblem)
write($outDir, 'mx', svg('0 0 300 200', '<rect width="100" height="200" fill="#006847"/><rect x="100" width="100" height="200" fill="#fff"/><rect x="200" width="100" height="200" fill="#CE1126"/><circle cx="150" cy="100" r="22" fill="none" stroke="#8B5E3C" stroke-width="3"/><path d="M150 84c8 0 14 6 14 14s-6 14-14 14-14-6-14-14 6-14 14-14z" fill="#8B5E3C"/>'));

echo "done\n";

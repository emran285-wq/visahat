<?php
declare(strict_types=1);

// Central destination directory dataset. Drives /visa/destinations, its filters,
// the destination detail routes, and the consultation form's destination options.
//
// contentStatus/verifiedContent are honest flags: every destination currently ships
// as an enquiry-only page (no government-sourced visa specifics have been verified
// yet), so all entries are noindex and excluded from the sitemap until the business
// supplies reviewed sourceLinks + reviewedAt for a destination.

const DESTINATION_REGIONS = [
    'middle-east' => 'Middle East',
    'europe-caucasus' => 'Europe & Caucasus',
    'central-asia' => 'Central Asia',
    'south-asia' => 'South Asia',
    'east-southeast-asia' => 'East & Southeast Asia',
    'africa' => 'Africa',
    'north-america' => 'North America',
];

function destination_url(string $slug): string { return '/visa/destinations/' . rawurlencode($slug); }

// Case-insensitive, diacritic-tolerant normalization for search matching.
function destination_normalize(string $value): string {
    $map = ['á'=>'a','à'=>'a','â'=>'a','ä'=>'a','ã'=>'a','å'=>'a','ç'=>'c','é'=>'e','è'=>'e','ê'=>'e','ë'=>'e','í'=>'i','ì'=>'i','î'=>'i','ï'=>'i','ñ'=>'n','ó'=>'o','ò'=>'o','ô'=>'o','ö'=>'o','õ'=>'o','ø'=>'o','ú'=>'u','ù'=>'u','û'=>'u','ü'=>'u','ý'=>'y','ÿ'=>'y','ş'=>'s','ğ'=>'g','ı'=>'i','ß'=>'ss','&'=>'and'];
    $value = mb_strtolower($value, 'UTF-8');
    $value = strtr($value, $map);
    return trim(preg_replace('/[^a-z0-9]+/', ' ', $value));
}

// True if a destination matches a free-text query across its name, aliases, and region.
// Matches at word boundaries (not mid-word substrings) so short aliases like "us" or "uk"
// don't false-positive inside unrelated words (e.g. "us" inside "Russia").
function destination_matches(array $destination, string $query): bool {
    $needle = destination_normalize($query);
    if ($needle === '') return true;
    $haystack = [$destination['displayName'], $destination['regionLabel'], ...$destination['aliases']];
    foreach ($haystack as $value) {
        $hay = destination_normalize((string) $value);
        if (preg_match('/(?:^|\s)' . preg_quote($needle, '/') . '/', $hay)) return true;
    }
    return false;
}

$rows = [
    'turkiye' => ['displayName' => 'Türkiye', 'aliases' => ['turkey', 'turkiye', 'türkiye'], 'region' => 'europe-caucasus', 'flagAsset' => '/assets/images/flags/tr.svg'],
    'azerbaijan' => ['displayName' => 'Azerbaijan', 'aliases' => ['azerbaijan'], 'region' => 'europe-caucasus', 'flagAsset' => '/assets/images/flags/az.svg'],
    'georgia' => ['displayName' => 'Georgia', 'aliases' => ['georgia'], 'region' => 'europe-caucasus', 'flagAsset' => '/assets/images/flags/ge.svg'],
    'egypt' => ['displayName' => 'Egypt', 'aliases' => ['egypt'], 'region' => 'africa', 'flagAsset' => '/assets/images/flags/eg.svg'],
    'bahrain' => ['displayName' => 'Bahrain', 'aliases' => ['bahrain'], 'region' => 'middle-east', 'flagAsset' => '/assets/images/flags/bh.svg'],
    'qatar' => ['displayName' => 'Qatar', 'aliases' => ['qatar'], 'region' => 'middle-east', 'flagAsset' => '/assets/images/flags/qa.svg'],
    'oman' => ['displayName' => 'Oman', 'aliases' => ['oman'], 'region' => 'middle-east', 'flagAsset' => '/assets/images/flags/om.svg'],
    'kuwait' => ['displayName' => 'Kuwait', 'aliases' => ['kuwait'], 'region' => 'middle-east', 'flagAsset' => '/assets/images/flags/kw.svg'],
    'united-arab-emirates' => ['displayName' => 'United Arab Emirates (UAE)', 'aliases' => ['uae', 'united arab emirates', 'emirates'], 'region' => 'middle-east', 'flagAsset' => '/assets/images/flags/ae.svg'],
    'pakistan' => ['displayName' => 'Pakistan', 'aliases' => ['pakistan'], 'region' => 'south-asia', 'flagAsset' => '/assets/images/flags/pk.svg'],
    'uzbekistan' => ['displayName' => 'Uzbekistan', 'aliases' => ['uzbekistan'], 'region' => 'central-asia', 'flagAsset' => '/assets/images/flags/uz.svg'],
    'tajikistan' => ['displayName' => 'Tajikistan', 'aliases' => ['tajikistan'], 'region' => 'central-asia', 'flagAsset' => '/assets/images/flags/tj.svg'],
    'kyrgyzstan' => ['displayName' => 'Kyrgyzstan', 'aliases' => ['kyrgyzstan', 'kyrgizstan', 'kirghizstan'], 'region' => 'central-asia', 'flagAsset' => '/assets/images/flags/kg.svg'],
    'kazakhstan' => ['displayName' => 'Kazakhstan', 'aliases' => ['kazakhstan'], 'region' => 'central-asia', 'flagAsset' => '/assets/images/flags/kz.svg'],
    'india' => ['displayName' => 'India', 'aliases' => ['india'], 'region' => 'south-asia', 'flagAsset' => '/assets/images/flags/in.svg'],
    'sri-lanka' => ['displayName' => 'Sri Lanka', 'aliases' => ['sri lanka', 'srilanka', 'ceylon'], 'region' => 'south-asia', 'flagAsset' => '/assets/images/flags/lk.svg'],
    'malaysia' => ['displayName' => 'Malaysia', 'aliases' => ['malaysia'], 'region' => 'east-southeast-asia', 'flagAsset' => '/assets/images/flags/my.svg'],
    'singapore' => ['displayName' => 'Singapore', 'aliases' => ['singapore'], 'region' => 'east-southeast-asia', 'flagAsset' => '/assets/images/flags/sg.svg'],
    'indonesia' => ['displayName' => 'Indonesia', 'aliases' => ['indonesia'], 'region' => 'east-southeast-asia', 'flagAsset' => '/assets/images/flags/id.svg'],
    'thailand' => ['displayName' => 'Thailand', 'aliases' => ['thailand', 'siam'], 'region' => 'east-southeast-asia', 'flagAsset' => '/assets/images/flags/th.svg'],
    'china' => ['displayName' => 'China', 'aliases' => ['china', 'prc'], 'region' => 'east-southeast-asia', 'flagAsset' => '/assets/images/flags/cn.svg'],
    'japan' => ['displayName' => 'Japan', 'aliases' => ['japan'], 'region' => 'east-southeast-asia', 'flagAsset' => '/assets/images/flags/jp.svg'],
    'south-korea' => ['displayName' => 'South Korea', 'aliases' => ['south korea', 'korea', 'republic of korea'], 'region' => 'east-southeast-asia', 'flagAsset' => '/assets/images/flags/kr.svg'],
    'philippines' => ['displayName' => 'Philippines', 'aliases' => ['philippines', 'the philippines'], 'region' => 'east-southeast-asia', 'flagAsset' => '/assets/images/flags/ph.svg'],
    'vietnam' => ['displayName' => 'Vietnam', 'aliases' => ['vietnam', 'viet nam'], 'region' => 'east-southeast-asia', 'flagAsset' => '/assets/images/flags/vn.svg'],
    'morocco' => ['displayName' => 'Morocco', 'aliases' => ['morocco'], 'region' => 'africa', 'flagAsset' => '/assets/images/flags/ma.svg'],
    'south-africa' => ['displayName' => 'South Africa', 'aliases' => ['south africa'], 'region' => 'africa', 'flagAsset' => '/assets/images/flags/za.svg'],
    'kenya' => ['displayName' => 'Kenya', 'aliases' => ['kenya'], 'region' => 'africa', 'flagAsset' => '/assets/images/flags/ke.svg'],
    'tanzania' => ['displayName' => 'Tanzania', 'aliases' => ['tanzania'], 'region' => 'africa', 'flagAsset' => '/assets/images/flags/tz.svg'],
    'uganda' => ['displayName' => 'Uganda', 'aliases' => ['uganda'], 'region' => 'africa', 'flagAsset' => '/assets/images/flags/ug.svg'],
    'russia' => ['displayName' => 'Russia', 'aliases' => ['russia', 'russian federation'], 'region' => 'europe-caucasus', 'flagAsset' => '/assets/images/flags/ru.svg'],
    'albania' => ['displayName' => 'Albania', 'aliases' => ['albania'], 'region' => 'europe-caucasus', 'flagAsset' => '/assets/images/flags/al.svg'],
    'bosnia-and-herzegovina' => ['displayName' => 'Bosnia and Herzegovina', 'aliases' => ['bosnia', 'bosnia and herzegovina', 'bosnia & herzegovina', 'herzegovina'], 'region' => 'europe-caucasus', 'flagAsset' => '/assets/images/flags/ba.svg'],
    'serbia' => ['displayName' => 'Serbia', 'aliases' => ['serbia'], 'region' => 'europe-caucasus', 'flagAsset' => '/assets/images/flags/rs.svg'],
    'schengen' => ['displayName' => 'Schengen Area', 'aliases' => ['schengen', 'schengen area', 'schengen zone', 'europe schengen'], 'region' => 'europe-caucasus', 'flagAsset' => '/assets/images/flags/schengen.svg', 'destinationType' => 'visa-area'],
    'united-kingdom' => ['displayName' => 'United Kingdom (UK)', 'aliases' => ['uk', 'united kingdom', 'britain', 'great britain'], 'region' => 'europe-caucasus', 'flagAsset' => '/assets/images/flags/gb.svg'],
    'united-states' => ['displayName' => 'United States (USA)', 'aliases' => ['usa', 'us', 'united states', 'united states of america', 'america'], 'region' => 'north-america', 'flagAsset' => '/assets/images/flags/us.svg'],
    'canada' => ['displayName' => 'Canada', 'aliases' => ['canada'], 'region' => 'north-america', 'flagAsset' => '/assets/images/flags/ca.svg'],
    'mexico' => ['displayName' => 'Mexico', 'aliases' => ['mexico', 'méxico'], 'region' => 'north-america', 'flagAsset' => '/assets/images/flags/mx.svg'],
];

// Every destination normalizes to this full schema. No sourceLinks/reviewedAt exist yet
// anywhere in the dataset, so contentStatus stays 'enquiry-only' and verifiedContent false
// for all 39 entries until the business supplies and the team records a reviewed source.
$defaults = [
    'destinationType' => 'country',
    'contentStatus' => 'enquiry-only',
    'verifiedContent' => false,
    'sourceLinks' => [],
    'reviewedAt' => null,
];

$destinations = [];
foreach ($rows as $slug => $row) {
    $record = array_merge($defaults, $row);
    $record['id'] = $slug;
    $record['slug'] = $slug;
    $record['regionLabel'] = DESTINATION_REGIONS[$record['region']] ?? $record['region'];
    $record['seo'] = $record['seo'] ?? [
        'title' => $record['displayName'] . ' Visa Enquiries | Visa Hat',
        'description' => 'Enquire about ' . $record['displayName'] . ' with Visa Hat. We will confirm what guidance and support we can currently offer for your circumstances.',
    ];
    $destinations[$slug] = $record;
}

return $destinations;

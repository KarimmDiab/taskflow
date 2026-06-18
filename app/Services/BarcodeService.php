<?php

namespace App\Services;

use App\Models\ProductVariant;
use Illuminate\Support\Str;
use RuntimeException;

class BarcodeService
{
    private const CODE39 = [
        '0' => '101001101101',
        '1' => '110100101011',
        '2' => '101100101011',
        '3' => '110110010101',
        '4' => '101001101011',
        '5' => '110100110101',
        '6' => '101100110101',
        '7' => '101001011011',
        '8' => '110100101101',
        '9' => '101100101101',
        'A' => '110101001011',
        'B' => '101101001011',
        'C' => '110110100101',
        'D' => '101011001011',
        'E' => '110101100101',
        'F' => '101101100101',
        'G' => '101010011011',
        'H' => '110101001101',
        'I' => '101101001101',
        'J' => '101011001101',
        'K' => '110101010011',
        'L' => '101101010011',
        'M' => '110110101001',
        'N' => '101011010011',
        'O' => '110101101001',
        'P' => '101101101001',
        'Q' => '101010110011',
        'R' => '110101011001',
        'S' => '101101011001',
        'T' => '101011011001',
        'U' => '110010101011',
        'V' => '100110101011',
        'W' => '110011010101',
        'X' => '100101101011',
        'Y' => '110010110101',
        'Z' => '100110110101',
        '-' => '100101011011',
        '.' => '110010101101',
        ' ' => '100110101101',
        '$' => '100100100101',
        '/' => '100100101001',
        '+' => '100101001001',
        '%' => '101001001001',
        '*' => '100101101101',
    ];

    public function generateForVariant(ProductVariant $variant, bool $force = false): string
    {
        if ($variant->barcode && ! $force) {
            return $variant->barcode;
        }

        $base = $this->normalize($variant->sku ?: 'RYO'.$variant->id);
        $barcode = $this->uniqueBarcode($base, $variant->id);

        $variant->update(['barcode' => $barcode]);

        return $barcode;
    }

    public function generateMissing(): int
    {
        $count = 0;

        ProductVariant::query()
            ->where(fn ($query) => $query->whereNull('barcode')->orWhere('barcode', ''))
            ->orderBy('id')
            ->chunkById(200, function ($variants) use (&$count): void {
                foreach ($variants as $variant) {
                    $this->generateForVariant($variant);
                    $count++;
                }
            });

        return $count;
    }

    public function regenerate(ProductVariant $variant): string
    {
        return $this->generateForVariant($variant, true);
    }

    public function svg(string $barcode, int $height = 58, int $scale = 2): string
    {
        $barcode = $this->normalize($barcode);

        if ($barcode === '') {
            throw new RuntimeException('Barcode cannot be empty.');
        }

        $encoded = '*'.$barcode.'*';
        $x = 0;
        $bars = '';

        foreach (str_split($encoded) as $char) {
            $pattern = self::CODE39[$char] ?? null;

            if (! $pattern) {
                continue;
            }

            foreach (str_split($pattern) as $bit) {
                if ($bit === '1') {
                    $bars .= '<rect x="'.$x.'" y="0" width="'.$scale.'" height="'.$height.'" fill="#111827"/>';
                }
                $x += $scale;
            }

            $x += $scale;
        }

        $width = max($x, 1);

        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 '.$width.' '.($height + 16).'" width="100%" height="'.($height + 16).'" role="img" aria-label="Barcode '.$barcode.'">'
            .'<rect width="'.$width.'" height="'.($height + 16).'" fill="#ffffff"/>'
            .$bars
            .'<text x="'.($width / 2).'" y="'.($height + 13).'" text-anchor="middle" font-family="Arial, sans-serif" font-size="10" fill="#111827">'.$barcode.'</text>'
            .'</svg>';
    }

    public function normalize(?string $value): string
    {
        $value = Str::upper(trim((string) $value));
        $value = preg_replace('/[^A-Z0-9\-. $\/+%]/', '', $value) ?? '';

        return trim($value);
    }

    private function uniqueBarcode(string $base, int $variantId): string
    {
        $base = $base !== '' ? $base : 'RYO'.$variantId;
        $candidate = $base;
        $suffix = 1;

        while (ProductVariant::query()
            ->where('barcode', $candidate)
            ->whereKeyNot($variantId)
            ->exists()) {
            $candidate = $base.'-'.$variantId.'-'.$suffix;
            $suffix++;
        }

        return $candidate;
    }
}

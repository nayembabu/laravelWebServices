<?php

namespace App\Services;

use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Output\Destination;

class PdfService
{
    public function makeFromHtml(string $html, string $filename = 'document.pdf', string $mode = 'D')
    {
        // Default mPDF config
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'tempDir' => storage_path('app/mpdf-temp'),

            // Add custom font directory
            'fontDir' => array_merge($fontDirs, [
                public_path('fonts'),
            ]),

            // Register SolaimanLipi font
            'fontdata' => $fontData + [
                'solaimanlipi' => [
                    'R' => 'SolaimanLipi.ttf',
                    'useOTL' => 0xFF,
                ],
            ],

            // Set default font
            'default_font' => 'solaimanlipi',

            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $mpdf->WriteHTML($html);

        $dest = match ($mode) {
            'I' => Destination::INLINE,
            'F' => Destination::FILE,
            default => Destination::DOWNLOAD,
        };

        return $mpdf->Output($filename, $dest);
    }
}

<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

use App\Models\Service;
use App\Models\UserServiceOrder;
use App\Models\UserBalanceAdd;
use App\Models\UserBalanceCut;
use App\Models\PaymentMethod;
use App\Models\UserRecharge;
use App\Models\SaveRowJsonData;
use App\Models\Voter;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\PdfService;

use BigFish\PDF417\PDF417;
use BigFish\PDF417\Renderers\ImageRenderer;
use BigFish\PDF417\Renderers\SvgRenderer;

class DownloadController extends Controller
{

    public function download_nid_copy(PdfService $pdf, $voter_id)
    {
        $voter = Voter::findOrFail($voter_id);

		// Genarate Barcode for This NID Card
		// barcode Content
		$string_for_barcode = "<pin>".$voter->pin."</pin><name> ".$voter->namnameEnglisheEn." </name><DOB>".date('d M Y', strtotime($voter->dateOfBirth))."</DOB><FP></FP><F>Right Index</F><TYPE>A</TYPE><V>2.0</V><ds>302c0214733766837d7afc3514acc6b182cde5a8a8225dba02143ca6d1a777859b362102c2cda54407834ee0c7f2</ds>";
		// barcode Content

		$pdf417 = new PDF417();
		$data = $pdf417->encode($string_for_barcode);

		// Create a URL image, for barcode
		$renderer = new ImageRenderer([
			'format' => 'data-url',
			'color' => '#000000',
			'bgColor' => '#FFFFFF',
			'scale' => 20,
			'quality' => 90
		]);
		$img = $renderer->render($data);

		$pdf417_barcode = $renderer->render($data);
		// Genarate Barcode for This NID Card

        $html = view('download.nid-download', [
            'pdf417_barcode' => $pdf417_barcode,
            'services_infos' => $voter,
        ])->render();

        return $pdf->makeFromHtml($html, 'nid-'.$voter->nid.'.pdf', 'D');

    }

    public function download(PdfService $pdf)
    {

		// Genarate Barcode for This NID Card
		// barcode Content
		// $string_for_barcode = "<pin>".$object['nid_no_type']."</pin><name> ".$object['voter_info']->voter->nameEn." </name><DOB>".date('d M Y', strtotime($object['voter_info']->voter->dob))."</DOB><FP></FP><F>Right Index</F><TYPE>A</TYPE><V>2.0</V><ds>302c0214733766837d7afc3514acc6b182cde5a8a8225dba02143ca6d1a777859b362102c2cda54407834ee0c7f2</ds>";
		// barcode Content


		$string_for_barcode = "<pin>123456789</pin><name> Kalam Bodda </name><DOB>01 01 1991</DOB><FP></FP><F>Right Index</F><TYPE>A</TYPE><V>2.0</V><ds>302c0214733766837d7afc3514acc6b182cde5a8a8225dba02143ca6d1a777859b362102c2cda54407834ee0c7f2</ds>";

		$pdf417 = new PDF417();
		$data = $pdf417->encode($string_for_barcode);

		// Create a URL image, for barcode
		$renderer = new ImageRenderer([
			'format' => 'data-url',
			'color' => '#000000',
			'bgColor' => '#FFFFFF',
			'scale' => 20,
			'quality' => 90
		]);
		$img = $renderer->render($data);

		$pdf417_barcode = $renderer->render($data);
		// Genarate Barcode for This NID Card


        $html = view('download.invoice', [
            'title' => 'Invoice #1001',
            'pdf417_barcode' => $pdf417_barcode,
        ])->render();

        return $pdf->makeFromHtml($html, 'invoice.pdf', 'D');
    }

    public function inline(PdfService $pdf)
    {
        $html = view('download.invoice')->render();
        return $pdf->makeFromHtml($html, 'invoice.pdf', 'I');
    }



    public function generatePdf() {
        $data = ['title' => 'My PDF'];
        $pdf = Pdf::loadView('download.invoice', $data);
        return $pdf->download('invoice.pdf');
    }
}

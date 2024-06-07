<?php

namespace Article;

use Sulfur\View;
use Sulfur\Response;
use Sulfur\Cache;
use Article\Model as Model;
use Mpdf\Mpdf as myPDF;
use Mpdf\Config\ConfigVariables as ConfigVars;
use Mpdf\Config\FontVariables as FontVars;
use Mpdf\MpdfException as Exception;

class Pdf
{
	public function __construct(
		ConfigVars $configvars,
		FontVars $fontvars
	)
	{
		$this->configvars = $configvars;
		$this->fontvars = $fontvars;
	}

	public function mpdfConfig($html, $title)
	{
		// Fonts & Settings
		$defaultConfig = $this->configvars->getDefaults();
		$fontDirs = $defaultConfig['fontDir'];
		$defaultFontConfig = $this->fontvars->getDefaults();
		$fontData = $defaultFontConfig['fontdata'];

		$stylesheet = '
			h1, h2, h3, h4 {
				font-family: "roboto";
			}
			h1 {
				font-size: 2.5rem;
				line-height: 1em;
				margin-bottom: 12px;
			}
			h2 {
				font-size: 1.5rem;
			}
			';

		try {
			$mpdf = new myPDF([
				'mode' => 'utf-8',
				'orientation' => 'P',
				'mode' => 's',
				'tempDir' => '/data/share/temp',
				'fontDir' => array_merge($fontDirs, [
					dirname(__DIR__, 2) . '/public/font/roboto',
					dirname(__DIR__, 2) . '/public/font/merriweather'
				]),
				'fontdata' => $fontData + [ // lowercase letters only in font key
						'roboto' => [
							'R' => 'Roboto-Regular.ttf',
							'B' => 'Roboto-Bold.ttf'
						],
						'merriweather' => [
							'R' => 'Merriweather-Regular.ttf'
						]
				],
				'default_font' => 'merriweather'
			]);
			$mpdf->SetTitle($title);
			$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
			$mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
			return $mpdf->Output();
		} catch (Exception $e) {
			echo 'Creating an mPDF object failed with ' . $e->getMessage();
		}
	}

	public function articlepdf(
		$slug,
		Model $model,
		View $view,
		Response $response
	)
	{
		$response->header('Cache-Control', 'max-age=300, 300, public');
		if ($item = $model->slug($slug)) {
			$html = $view->render('article/item_pdf', [
				'item' => $model->one($item->id),
			]);
			return $this->mpdfConfig($html, $item->title);
			
		} else {
			return $view->render('page/404');
		}
	}

}
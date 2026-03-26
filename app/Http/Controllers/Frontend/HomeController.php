<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Admin\Advertise;
use App\Models\Admin\Blog;
use App\Models\Admin\Brand;
use App\Models\Admin\CompanyStory;
use App\Models\Admin\ImageGallery;
use App\Models\Admin\Product;
use App\Models\Admin\Slider;
use App\Models\Admin\Testimonial;
use App\Models\Banner;
use App\Models\Currency;
use App\Models\Language;
use App\Models\SeoSetting;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class HomeController extends Controller
{

    public function index()
    {
        if (file_exists(storage_path('installed'))) {
            if (!Session::has('currency')) {
                Session::put('currency', Setting::where('slug', 'default_currency')->first()->value ?? 'USD');
            }
            $data['sliders'] = Slider::latest()->get();
            $data['promotion'] = Advertise::latest()->get();

            $all_products = Product::with([
                'brand',
                'category',
                'colors',
                'sizes',
                'additions' => function ($query) {
                    $query->where('status', 1);
                },
                'product_tags'
            ])->where('Status', ACTIVE)->orderBy('fr_Product_Name', 'asc');

            $data['featured_products'] = $all_products->clone()->where('Featured_Product', ACTIVE)->paginate(10);
            $data['on_sales'] = $all_products->clone()->where('Discount', "!=", '0')->paginate(10);
            $data['best_selling'] = $all_products->clone()->where('Best_Selling', ACTIVE)->paginate(10);
            $data['new_arrivals'] = $all_products->clone()->where('New_Arrival', ACTIVE)->latest()->paginate(10);

            $seo = SeoSetting::where('slug', 'home')->first();
            $data['title'] = $seo->title;
            $data['description'] = $seo->description;
            $data['keywords'] = $seo->keywords;

            return view('front.index', $data);
        } else {
            return redirect()->to('/install');
        }
    }

    public function theme_set(Request $request)
    {
        if (env('APP_DEMO') == true) {
            session(['theme' => $request->theme]);
        }
        return redirect()->route('front');
    }
    public function localeSwitch($locale)
    {
        $lang = Language::where('locale', $locale)->first();

        // Preserve the legacy DB locale (e.g. 'fr') in APP_LOCALE so existing code
        // that expects the DB's locale continues to work. At the same time, set
        // a separate session key `HTML_LANG` and `lang_dir` so the rendered HTML
        // tag can use the true language code (e.g. 'ar') and correct text direction.
        $dbLocale = $locale;
        $dir = $lang->direction ?? 'ltr';

        // If the language row is RTL, prefer rendering HTML as 'ar' (Arabic)
        // while keeping DB locale unchanged so code still reads fr_ columns.
        $htmlLang = $dbLocale;
        if ($dir === 'rtl') {
            $htmlLang = 'ar';
        }

        session([
            'APP_LOCALE' => $dbLocale,
            'HTML_LANG' => $htmlLang,
            'lang_dir' => $dir,
        ]);

        // Keep the Laravel locale set to the DB locale to avoid breaking checks
        // that still look for 'fr' in legacy installations.
        App::setLocale($dbLocale);

        return redirect()->back();
    }
    public function currencySwitch($currency)
    {
        Session::put('currency', $currency);
        return redirect()->back();
    }

    public function deliveryChargeReport()
    {
        $reports = DB::table('orders')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(delivery_charge) as total_delivery_charge'),
                DB::raw('COUNT(*) as total_orders')
            )
            ->where("Payment_Status" , '=', PAYMENT_SUCCESS)
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('admin.reports.delivery_charge', compact('reports'));
    }

    public function exportDeliveryChargeReport(Request $request)
    {
        // Build the database query based on date filters (if provided)
        $query = DB::table('orders')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(delivery_charge) as total_delivery_charge'),
                DB::raw('COUNT(*) as total_orders')
            )
            ->where("Payment_Status", '=', PAYMENT_SUCCESS);

        // Apply date filters if they exist
        if ($request->has('start_date') && $request->start_date && $request->has('end_date') && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $reports = $query->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Create new spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Sharaa System')
            ->setLastModifiedBy('Sharaa System')
            ->setTitle(__('Delivery Charge Report'))
            ->setSubject(__('Monthly Delivery Charge Report'))
            ->setDescription(__('Delivery Charge Report generated from Sharaa System'))
            ->setKeywords('delivery charge report excel')
            ->setCategory('Reports');

        // Add header styling
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        // Set header row
        $sheet->setCellValue('A1', __('Year'));
        $sheet->setCellValue('B1', __('Month'));
        $sheet->setCellValue('C1', __('Total Orders'));
        $sheet->setCellValue('D1', __('Total Delivery Charge'));
        $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);

        // Add data rows
        $row = 2;
        $totalOrders = 0;
        $totalDeliveryCharge = 0;

        foreach ($reports as $report) {
            $sheet->setCellValue('A' . $row, $report->year);
            $sheet->setCellValue('B' . $row, date('F', mktime(0, 0, 0, $report->month, 1)));
            $sheet->setCellValue('C' . $row, $report->total_orders);
            $sheet->setCellValue('D' . $row, number_format($report->total_delivery_charge, 2));

            $totalOrders += $report->total_orders;
            $totalDeliveryCharge += $report->total_delivery_charge;
            $row++;
        }

        // Add total row
        $sheet->setCellValue('A' . $row, __('Grand Total:'));
        $sheet->mergeCells('A' . $row . ':B' . $row);
        $sheet->setCellValue('C' . $row, $totalOrders);
        $sheet->setCellValue('D' . $row, number_format($totalDeliveryCharge, 2));

        // Style the total row
        $totalRowStyle = [
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9E1F2'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A' . $row . ':D' . $row)->applyFromArray($totalRowStyle);

        // Auto size columns
        foreach(range('A','D') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Set filename
        $fileName = 'delivery_charge_report_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Create writer and prepare response
        $writer = new Xlsx($spreadsheet);

        // Redirect output to a client's web browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        // Save to output
        $writer->save('php://output');
        exit;
    }
}

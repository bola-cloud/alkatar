<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Admin\ImageGallery;
use App\Models\Admin\Testimonial;
use App\Models\Admin\SiteContent\AboutUsPage;
use App\Models\SeoSetting;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    public function aboutUs()
    {
        // $data['image_gallery'] = ImageGallery::latest()->get();
        $data['testimonials'] = Testimonial::latest()->get();
        $seo = SeoSetting::where('slug', 'about-us')->first();
        $data['title'] = $seo->title ?? __('About Us');
        $data['description'] = $seo->description ?? '';
        $data['keywords'] = $seo->keywords ?? '';

        // Load admin-managed about page content (Location='about_us') so front view stays dynamic
        $data['about'] = AboutUsPage::where('Location', 'about_us')->first();

        // Use the newdesign blade which reads $about and $testimonials
        return view('front.pages.aboutus.newdesign', $data);
    }
}

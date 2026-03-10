<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\SeoSetting;
use Illuminate\Http\Request;

class ServiceCustomerController extends Controller
{
   public function termsConditions(){
       $seo = SeoSetting::where('slug', 'terms-conditions')->first();
       $data['title'] = $seo->title;
       $data['description'] = $seo->description;
       $data['keywords'] = $seo->keywords;
       return view('front.pages.customer_services.terms_conditions', $data);
   }

   public function termsConditionsNewDesign(){
       $seo = SeoSetting::where('slug', 'terms-conditions')->first();
       $data['title'] = $seo->title ?? __('Term & Conditions');
       $data['description'] = $seo->description ?? '';
       $data['keywords'] = $seo->keywords ?? '';
       return view('front.pages.customer_services.terms_conditions_newdesign', $data);
   }
   public function privacyPolicy(){
       $seo = SeoSetting::where('slug', 'privacy-policy')->first();
       $data['title'] = $seo->title;
       $data['description'] = $seo->description;
       $data['keywords'] = $seo->keywords;
       return view('front.pages.customer_services.privacy_policy', $data);
   }

   public function privacyPolicyNewDesign(){
       $seo = SeoSetting::where('slug', 'privacy-policy')->first();
       $data['title'] = $seo->title ?? __('Privacy Policy');
       $data['description'] = $seo->description ?? '';
       $data['keywords'] = $seo->keywords ?? '';
       return view('front.pages.customer_services.privacy_policy_newdesign', $data);
   }
   public function shippingReturn(){
       $seo = SeoSetting::where('slug', 'shipping-return')->first();
       $data['title'] = $seo->title;
       $data['description'] = $seo->description;
       $data['keywords'] = $seo->keywords;
       return view('front.pages.customer_services.shipping_return', $data);
   }
   public function shippingReturnNewDesign(){
       $seo = SeoSetting::where('slug', 'shipping-return')->first();
       $data['title'] = $seo->title ?? __('Shipping & Return');
       $data['description'] = $seo->description ?? '';
       $data['keywords'] = $seo->keywords ?? '';
       return view('front.pages.customer_services.shipping_return_newdesign', $data);
   }
   public function Faq(){
       $data['faqs'] = Faq::latest()->get();
       $seo = SeoSetting::where('slug', 'faq')->first();
       $data['title'] = $seo->title ?? __('FAQ');
       $data['description'] = $seo->description ?? '';
       $data['keywords'] = $seo->keywords ?? '';

       // Return the new-design FAQ view which renders admin-managed FAQ entries
       return view('front.pages.customer_services.faq_newdesign', $data);
   }
   public function refundPolicy(){
       $seo = SeoSetting::where('slug', 'refund-policy')->first();
       $data['title'] = $seo->title ?? __('Return Policy');
       $data['description'] = $seo->description ?? '';
       $data['keywords'] = $seo->keywords ?? '';
       return view('front.pages.customer_services.return_policy_newdesign', $data);
   }
}

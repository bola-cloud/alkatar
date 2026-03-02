<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactUsRequest;
use App\Models\Front\Contactus;
use App\Models\SeoSetting;

class ContactUsController extends Controller
{
    public function contactUs()
    {
        $seo = SeoSetting::where('slug', 'contact')->first();
        $data['title'] = $seo->title;
        $data['description'] = $seo->description;
        $data['keywords'] = $seo->keywords;
        // prefer the newdesign contact page
        return view('front.pages.contact.newdesign', $data);
    }

    public function contactUsStore(ContactUsRequest $request)
    {
        if(!empty($request->spam_field)) {
            dd("Spam detected!");
        }
        Contactus::create([
            'FirstName' => $request->firstname,
            'Email' => $request->email,
            'Message' => $request->message ?? '',
        ]);
        return redirect()->back()->with('success', __('Successfully Sent Message!'));
    }
}

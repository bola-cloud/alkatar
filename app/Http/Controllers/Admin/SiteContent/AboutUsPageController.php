<?php

namespace App\Http\Controllers\Admin\SiteContent;

use App\Http\Controllers\Controller;
use App\Models\Admin\SiteContent\AboutUsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class AboutUsPageController extends Controller
{
    public function aboutPage(Request $request)
    {
        $about = AboutUsPage::where('Location', 'about_us')->first();
        if (!$about) {
            $about = AboutUsPage::create([
                'Location' => 'about_us'
            ]);
        }
        $data['title'] = __('Edit About Page Content');
        $data['edit'] = $about;
        return view('admin.pages.site_content.about_us.edit', $data);
    }
    
    public function aboutPageEdit($id)
    {
        return redirect()->route('admin.about.page.site.content');
    }
    
    public function aboutPageUpdate(Request $request)
    {
        $id = $request->id;
        $about = AboutUsPage::where('id', $id)->first();
        
        if (!empty($request->image)) {
            $image = fileUpload($request['image'], aboutUsPage());
        } else {
            $image = $about->Image;
        }

        if (!empty($request->icon_one)) {
            $icon_one = fileUpload($request['icon_one'], aboutUsPage());
        } else {
            $icon_one = $about->Icon_One;
        }
        if (!empty($request->icon_two)) {
            $icon_two = fileUpload($request['icon_two'], aboutUsPage());
        } else {
            $icon_two = $about->Icon_Two;
        }
        if (!empty($request->icon_three)) {
            $icon_three = fileUpload($request['icon_three'], aboutUsPage());
        } else {
            $icon_three = $about->Icon_Three;
        }
        if (!empty($request->icon_four)) {
            $icon_four = fileUpload($request['icon_four'], aboutUsPage());
        } else {
            $icon_four = $about->Icon_Four;
        }

        if (!empty($request->why_image_one)) {
            $why_image_one = fileUpload($request['why_image_one'], aboutUsPage());
        } else {
            $why_image_one = $about->why_image_one;
        }
        if (!empty($request->why_image_two)) {
            $why_image_two = fileUpload($request['why_image_two'], aboutUsPage());
        } else {
            $why_image_two = $about->why_image_two;
        }

        $general = $about->update([
            'en_Title' => $request->en_Title,
            'en_Subtitle' => $request->en_subtitle,
            'Image' => $image,
            'Icon_One' => $icon_one,
            'Icon_Two' => $icon_two,
            'Icon_Three' => $icon_three,
            'Icon_Four' => $icon_four,
            'en_Title_One' => $request->en_title_one,
            'en_Title_Two' => $request->en_title_two,
            'en_Title_Three' => $request->en_title_three,
            'en_Title_Four' => $request->en_title_four,
            'en_Description_One' => $request->en_description_one,
            'en_Description_Two' => $request->en_description_two,
            'en_Description_Three' => $request->en_description_three,
            'en_Description_Four' => $request->en_description_four,
            'fr_Title_One' => $request->fr_title_one,
            'fr_Title_Two' => $request->fr_title_two,
            'fr_Title_Three' => $request->fr_title_three,
            'fr_Title_Four' => $request->fr_title_four,
            'fr_Title' => $request->fr_Title,
            'fr_Subtitle' => $request->fr_subtitle,
            'fr_Description_One' => $request->fr_description_one,
            'fr_Description_Two' => $request->fr_description_two,
            'fr_Description_Three' => $request->fr_description_three,
            'fr_Description_Four' => $request->fr_description_four,
            
            // New fields
            'experience_years' => $request->experience_years,
            'en_experience_text' => $request->en_experience_text,
            'fr_experience_text' => $request->fr_experience_text,
            'en_vision_label' => $request->en_vision_label,
            'fr_vision_label' => $request->fr_vision_label,
            'en_mission_label' => $request->en_mission_label,
            'fr_mission_label' => $request->fr_mission_label,
            'en_values_title' => $request->en_values_title,
            'fr_values_title' => $request->fr_values_title,
            'en_values_subtitle' => $request->en_values_subtitle,
            'fr_values_subtitle' => $request->fr_values_subtitle,
            
            'en_value_one_title' => $request->en_value_one_title,
            'fr_value_one_title' => $request->fr_value_one_title,
            'en_value_one_description' => $request->en_value_one_description,
            'fr_value_one_description' => $request->fr_value_one_description,
            
            'en_value_two_title' => $request->en_value_two_title,
            'fr_value_two_title' => $request->fr_value_two_title,
            'en_value_two_description' => $request->en_value_two_description,
            'fr_value_two_description' => $request->fr_value_two_description,
            
            'en_value_three_title' => $request->en_value_three_title,
            'fr_value_three_title' => $request->fr_value_three_title,
            'en_value_three_description' => $request->en_value_three_description,
            'fr_value_three_description' => $request->fr_value_three_description,
            
            'en_value_four_title' => $request->en_value_four_title,
            'fr_value_four_title' => $request->fr_value_four_title,
            'en_value_four_description' => $request->en_value_four_description,
            'fr_value_four_description' => $request->fr_value_four_description,
            
            'en_why_title' => $request->en_why_title,
            'fr_why_title' => $request->fr_why_title,
            'en_why_subtitle' => $request->en_why_subtitle,
            'fr_why_subtitle' => $request->fr_why_subtitle,
            
            'en_why_item_one' => $request->en_why_item_one,
            'fr_why_item_one' => $request->fr_why_item_one,
            'en_why_item_two' => $request->en_why_item_two,
            'fr_why_item_two' => $request->fr_why_item_two,
            'en_why_item_three' => $request->en_why_item_three,
            'fr_why_item_three' => $request->fr_why_item_three,
            
            'en_cta_title' => $request->en_cta_title,
            'fr_cta_title' => $request->fr_cta_title,
            'en_cta_btn_crops' => $request->en_cta_btn_crops,
            'fr_cta_btn_crops' => $request->fr_cta_btn_crops,
            'en_cta_btn_expert' => $request->en_cta_btn_expert,
            'fr_cta_btn_expert' => $request->fr_cta_btn_expert,
            
            'why_image_one' => $why_image_one,
            'why_image_two' => $why_image_two,
        ]);
        if ($general) {
            return redirect()->route('admin.about.page.site.content')->with('success', __('Successfully Updated !'));
        }
        return redirect()->route('admin.about.page.site.content')->with('success', __('Does not Updated !'));
    }
}

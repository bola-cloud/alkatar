@extends('front.layouts.new_design_layout')

@section('content')

@php
    $isRtl = app()->getLocale() != 'en';
    $dir = $isRtl ? 'rtl' : 'ltr';
    
    $t = [
        'profile_title' => $isRtl ? 'الملف الشخصي' : 'User Profile',
        'settings' => $isRtl ? 'إعدادات الحساب' : 'Account Settings',
        'settings_desc' => $isRtl ? 'إدارة معلومات ملفك الشخصي وتفضيلاتك.' : 'Manage your profile information and preferences.',
        'my_orders' => $isRtl ? 'طلباتي' : 'My Orders',
        'my_orders_desc' => $isRtl ? 'إدارة وتتبع وإعادة طلب منتجاتك المفضلة.' : 'Manage, track, and reorder your favorite products.',
        'my_addresses' => $isRtl ? 'عناويني' : 'My Addresses',
        'my_addresses_desc' => $isRtl ? 'إدارة عناوين الشحن والفواتير الخاصة بك.' : 'Manage your shipping and billing addresses.',
        'payments' => $isRtl ? 'طرق الدفع' : 'Payment Methods',
        'payments_desc' => $isRtl ? 'إدارة بطاقاتك المحفوظة وتفضيلات الدفع لتسجيل خروج أسرع.' : 'Manage your saved cards and payment preferences.',
        'personal_info' => $isRtl ? 'المعلومات الشخصية' : 'Personal Information',
        'profile_pic' => $isRtl ? 'صورة الملف الشخصي' : 'Profile Picture',
        'full_name' => $isRtl ? 'الاسم الكامل' : 'Full Name',
        'email_addr' => $isRtl ? 'البريد الإلكتروني' : 'Email Address',
        'phone_num' => $isRtl ? 'رقم الهاتف' : 'Phone Number',
        'change_password' => $isRtl ? 'تغيير كلمة المرور' : 'Change Password',
        'current_password' => $isRtl ? 'كلمة المرور الحالية' : 'Current Password',
        'new_password' => $isRtl ? 'كلمة المرور الجديدة' : 'New Password',
        'confirm_password' => $isRtl ? 'تأكيد كلمة المرور الجديدة' : 'Confirm New Password',
        'notif_pref' => $isRtl ? 'تفضيلات الإشعارات' : 'Notification Preferences',
        'email_notif' => $isRtl ? 'إشعارات البريد الإلكتروني' : 'Email Notifications',
        'email_notif_desc' => $isRtl ? 'تلقي تحديثات الطلبات والعروض الترويجية عبر البريد.' : 'Receive order updates and promotions via email.',
        'sms_notif' => $isRtl ? 'إشعارات الرسائل القصيرة' : 'SMS Notifications',
        'sms_notif_desc' => $isRtl ? 'احصل على تتبع التوصيل في الوقت الفعلي على هاتفك.' : 'Get real-time delivery tracking on your phone.',
        'push_notif' => $isRtl ? 'إشعارات الدفع (Push)' : 'Push Notifications',
        'push_notif_desc' => $isRtl ? 'بق على اطلاع مع تنبيهات التطبيق على جهازك.' : 'Stay updated with app alerts on your device.',
        'discard' => $isRtl ? 'تراجع عن التغييرات' : 'Discard Changes',
        'save_changes' => $isRtl ? 'حفظ التغييرات' : 'Save Changes',
        'order_status' => $isRtl ? 'حالة الطلب' : 'Order Status',
        'date_range' => $isRtl ? 'الفترة الزمنية' : 'Date Range',
        'apply_filters' => $isRtl ? 'تطبيق الفلتر' : 'Apply Filters',
        'all_orders' => $isRtl ? 'جميع الطلبات' : 'All Orders',
        'last_30_days' => $isRtl ? 'آخر 30 يوم' : 'Last 30 Days',
        'order_details' => $isRtl ? 'تفاصيل الطلب' : 'Order Details',
        'date' => $isRtl ? 'التاريخ' : 'Date',
        'items' => $isRtl ? 'المنتجات' : 'Items',
        'total' => $isRtl ? 'الإجمالي' : 'Total',
        'status' => $isRtl ? 'الحالة' : 'Status',
        'actions' => $isRtl ? 'إجراءات' : 'Actions',
        'reorder' => $isRtl ? 'إعادة طلب' : 'Reorder',
        'security_privacy' => $isRtl ? 'الأمان والخصوصية' : 'Security & Privacy',
        'security_desc' => $isRtl ? 'نحن نستخدم تشفيراً قياسياً للحفاظ على أمان معلومات الدفع الخاصة بك. لا يتم تخزين تفاصيل بطاقتك الكاملة على خوادمنا.' : 'We use industry-standard encryption to keep your payment information safe. Your full card details are never stored on our servers.',
        'add_card' => $isRtl ? 'إضافة بطاقة جديدة' : 'Add New Card',
        'add_address' => $isRtl ? 'إضافة عنوان جديد' : 'Add New Address',
        'primary' => $isRtl ? 'أساسي' : 'PRIMARY',
        'default' => $isRtl ? 'افتراضي' : 'Default',
        'expires' => $isRtl ? 'تنتهي في' : 'EXPIRES',
        'edit' => $isRtl ? 'تعديل' : 'Edit',
        'remove' => $isRtl ? 'حذف' : 'Remove',
        'learn_more' => $isRtl ? 'لمعرفة المزيد' : 'Learn More',
        'add_new_address' => $isRtl ? 'إضافة عنوان جديد' : 'Add New Address',
        'add_another_address' => $isRtl ? 'إضافة عنوان آخر' : 'Add Another Address',
        'gym_parents_friends' => $isRtl ? 'النادي الرياضي، منزل الوالدين، أو مكان صديق' : 'Gym, Parent\'s home, or Friend\'s place',
        'address_label' => $isRtl ? 'اسم العنوان' : 'Address Label',
        'address_label_placeholder' => $isRtl ? 'مثال: المنزل، العمل، النادي الرياضي' : 'e.g. Home, Office, Gym',
        'street_address' => $isRtl ? 'عنوان الشارع' : 'Street Address',
        'street_address_placeholder' => $isRtl ? '123 شارع رئيسي' : '123 Main St',
        'building_number' => $isRtl ? 'رقم المبنى' : 'Building Number',
        'apartment_floor' => $isRtl ? 'الشقة / الطابق' : 'Apartment/Floor',
        'city' => $isRtl ? 'المدينة' : 'City',
        'phone_number' => $isRtl ? 'رقم الهاتف' : 'Phone Number',
        'delivery_notes' => $isRtl ? 'ملاحظات التوصيل' : 'Delivery Notes',
        'delivery_notes_placeholder' => $isRtl ? 'رمز البوابة، اتركه عند الباب، رن الجرس، إلخ...' : 'Gate code, drop at door, ring bell, etc...',
        'save_address' => $isRtl ? 'حفظ العنوان' : 'Save Address',
        'cancel' => $isRtl ? 'إلغاء' : 'Cancel',
        'items_ordered' => $isRtl ? 'المنتجات المطلوبة' : 'Items Ordered',
        'delivery_details' => $isRtl ? 'تفاصيل التوصيل' : 'Delivery Details',
        'payment_summary' => $isRtl ? 'ملخص الدفع' : 'Payment Summary',
        'payment_method' => $isRtl ? 'طريقة الدفع' : 'Payment Method',
        'subtotal' => $isRtl ? 'المجموع الفرعي' : 'Subtotal',
        'delivery_fee' => $isRtl ? 'رسوم التوصيل' : 'Delivery Fee',
        'service_fee' => $isRtl ? 'رسوم الخدمة' : 'Service Fee',
        'tax' => $isRtl ? 'الضريبة' : 'Tax',
        'contact_support_question' => $isRtl ? 'هل هناك مشكلة في طلبك؟' : 'Something went wrong with your order?',
        'contact_support_link' => $isRtl ? 'اتصل بخدمة العملاء' : 'Contact Customer Support',
        
        // Reviews and Stepper Translations
        'my_reviews' => $isRtl ? 'تقييماتي' : 'My Reviews',
        'my_reviews_desc' => $isRtl ? 'إدارة ومراجعة تقييمات المنتجات التي قمت بشرائها.' : 'Manage and review your product ratings and feedback.',
        'no_reviews' => $isRtl ? 'لا توجد تقييمات بعد.' : 'No reviews yet!',
        'rating' => $isRtl ? 'التقييم' : 'Rating',
        'feedback' => $isRtl ? 'التعليق' : 'Feedback',
        'write_review' => $isRtl ? 'كتابة تقييم' : 'Write a Review',
        'submit_review' => $isRtl ? 'إرسال التقييم' : 'Submit Review',
        'review_product' => $isRtl ? 'تقييم المنتج' : 'Review Product',
        'order_tracking' => $isRtl ? 'تتبع الطلب' : 'Order Tracking',
        'confirmed' => $isRtl ? 'تم التأكيد' : 'Confirmed',
        'processing' => $isRtl ? 'جاري التجهيز' : 'Processing',
        'shipped' => $isRtl ? 'تم الشحن' : 'Shipped',
        'delivered' => $isRtl ? 'تم التوصيل' : 'Delivered',
        'cancelled' => $isRtl ? 'ملغي' : 'Cancelled',
        'delivery_status' => $isRtl ? 'حالة التوصيل' : 'Delivery Status',
    ];
@endphp

<link rel="stylesheet" href="{{ asset('frontend/assets/css/profile.css') }}">

<script>
    window.isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
    window.profileOrdersData = {!! json_encode($orders->map(function($order) use($isRtl) {
        $methodLabel = isset($order->collection_method_ar) ? ($isRtl ? $order->collection_method_ar : $order->collection_method_label) : ($isRtl ? ($order->collection_method == 'pickup' ? 'استلام من المخزن' : 'توصيل') : ($order->collection_method == 'pickup' ? 'Warehouse Pickup' : 'Delivery'));
        return [
            'number' => $order->Order_Number,
            'date' => \Carbon\Carbon::parse($order->created_at)->format('M d, Y'),
            'total' => number_format($order->Grand_Total, 2) . ' ' . ($isRtl ? 'ر.ع.' : 'OMR'),
            'subtotal' => number_format($order->subtotal ?? ($order->Grand_Total - ($order->delivery_fee ?? 0) - ($order->service_fee ?? 0)), 2) . ' ' . ($isRtl ? 'ر.ع.' : 'OMR'),
            'delivery_fee' => number_format($order->delivery_fee ?? 0, 2) . ' ' . ($isRtl ? 'ر.ع.' : 'OMR'),
            'service_fee' => number_format($order->service_fee ?? 0, 2) . ' ' . ($isRtl ? 'ر.ع.' : 'OMR'),
            'tax' => number_format($order->tax ?? 0, 2) . ' ' . ($isRtl ? 'ر.ع.' : 'OMR'),
            'status' => $order->Order_Status,
            'method' => $methodLabel,
            'payment_method_brand' => $order->payment_method_brand ?? 'Visa',
            'payment_method_last4' => $order->payment_method_last4 ?? '4242',
            'delivery_name' => $order->delivery_name ?? 'Jane Doe',
            'delivery_address' => $order->delivery_address ?? 'Muscat, Oman',
            'items' => $order->order_details->map(function($d) use($isRtl) {
                return [
                    'product_id' => $d->Product_Id ?? $d->product_id ?? 1,
                    'name' => $isRtl ? ($d->product->fr_Product_Name ?? $d->product->en_Product_Name ?? 'منتج') : ($d->product->en_Product_Name ?? 'Product'),
                    'qty' => $d->Quantity,
                    'price' => number_format($d->Price ?? 0, 2) . ' ' . ($isRtl ? 'ر.ع.' : 'OMR'),
                    'price_val' => $d->Price ?? 0,
                    'volume' => $d->product->volume ?? '100ml',
                    'image' => !empty($d->product->image) ? asset($d->product->image) : 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?q=80&w=300&auto=format&fit=crop'
                ];
            })
        ];
    })) !!};

    window.profileReviewsData = {!! json_encode($reviews->map(function($review) use($isRtl) {
        return [
            'id' => $review->id,
            'rating' => $review->rating,
            'feedback' => $review->feedback,
            'product_id' => $review->product_id,
            'product_name' => $isRtl ? ($review->product->fr_Product_Name ?? $review->product->en_Product_Name ?? 'منتج') : ($review->product->en_Product_Name ?? 'Product'),
            'product_image' => !empty($review->product->Primary_Image) ? asset(ProductImage().$review->product->Primary_Image) : (!empty($review->product->image) ? asset($review->product->image) : 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?q=80&w=300&auto=format&fit=crop'),
            'product_slug' => $review->product->en_Product_Slug ?? '#',
            'date' => \Carbon\Carbon::parse($review->created_at)->format('M d, Y')
        ];
    })) !!};

    window.profileConfig = {
        addresses: {!! json_encode($addresses) !!},
        reviews: window.profileReviewsData || [],
        isRtl: {{ $isRtl ? 'true' : 'false' }},
        isAuthenticated: {{ auth()->check() ? 'true' : 'false' }},
        csrfToken: '{{ csrf_token() }}',
        userImage: '{{ $user->image ? (str_starts_with($user->image, 'http') ? $user->image : asset('uploaded_files/admin_profile/' . $user->image)) : "https://www.w3schools.com/howto/img_avatar.png" }}',
        messages: {
            requiredFields: '{{ $isRtl ? "يرجى ملء جميع الحقول المطلوبة!" : "Please fill all required fields!" }}',
            addressUpdated: '{{ $isRtl ? "تم تعديل العنوان بنجاح!" : "Address updated successfully!" }}',
            addressAdded: '{{ $isRtl ? "تم إضافة العنوان بنجاح!" : "Address added successfully!" }}',
            addressDeleted: '{{ $isRtl ? "تم حذف العنوان بنجاح!" : "Address deleted successfully!" }}',
            confirmDelete: '{{ $isRtl ? "هل أنت متأكد من حذف هذا العنوان؟" : "Are you sure you want to delete this address?" }}',
            errorOccurred: '{{ $isRtl ? "حدث خطأ ما!" : "Something went wrong!" }}'
        }
    };
</script>
<script src="{{ asset('frontend/assets/js/profile.js') }}"></script>

<div class="profile-page-wrapper text-[#1A4231] py-12 lg:py-16" dir="{{ $dir }}" x-data="profileDashboardState(window.profileConfig)">

    <div class="container mx-auto px-4 lg:px-8">
        
        @if($errors->any())
            <div class="hidden" x-init="triggerToast('{{ $errors->first() }}', 'error')"></div>
        @endif

        <div class="profile-dashboard">
            
            <!-- Sidebar Panel -->
            <aside class="profile-sidebar-panel">
                <!-- User Card -->
                <div class="bg-white rounded-[24px] border border-gray-200/80 p-6 flex flex-col items-center text-center shadow-sm">
                    <div class="relative w-24 h-24 mb-4">
                        <img :src="previewImage" alt="User Avatar" class="w-full h-full rounded-full object-cover border-4 border-[#1A4231]/10">
                    </div>
                    <h2 class="text-xl font-bold text-[#1A4231]">{{ $user->name ?? 'Alex Johnson' }}</h2>
                    <p class="text-slate-500 text-sm font-semibold mt-1">{{ $user->email ?? 'alex.j@example.com' }}</p>
                </div>

                <!-- Navigation Tabs Menu -->
                <div class="bg-white rounded-[24px] border border-gray-200/80 p-3 shadow-sm flex flex-col gap-1">
                    <!-- Tab: Settings -->
                    <button @click="activeTab = 'settings'" 
                            :class="activeTab === 'settings' ? 'active' : ''"
                            class="profile-nav-btn">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ $t['settings'] }}</span>
                    </button>

                    <!-- Tab: Orders -->
                    <button @click="activeTab = 'orders'" 
                            :class="activeTab === 'orders' ? 'active' : ''"
                            class="profile-nav-btn">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span>{{ $t['my_orders'] }}</span>
                    </button>

                    <!-- Tab: Addresses -->
                    <button @click="activeTab = 'addresses'" 
                            :class="activeTab === 'addresses' ? 'active' : ''"
                            class="profile-nav-btn">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ $t['my_addresses'] }}</span>
                    </button>

                    <!-- Tab: Payments -->
                    <button @click="activeTab = 'payments'" 
                            :class="activeTab === 'payments' ? 'active' : ''"
                            class="profile-nav-btn">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span>{{ $t['payments'] }}</span>
                    </button>

                    <!-- Tab: Reviews -->
                    <button @click="activeTab = 'reviews'" 
                            :class="activeTab === 'reviews' ? 'active' : ''"
                            class="profile-nav-btn">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.36 1.246.588 1.81l-3.97 2.883a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.971-2.883a1 1 0 00-1.17 0l-3.971 2.883c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118L2.05 9.43c-.773-.564-.374-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        <span>{{ $t['my_reviews'] }}</span>
                    </button>

                    <!-- Tab: Return to Store -->
                    <a href="{{ route('front.store') }}" 
                       class="profile-nav-btn text-[#1A4231] hover:bg-gray-50 border-t border-gray-100/80 pt-3 mt-2 flex items-center gap-2">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>{{ $isRtl ? 'العودة للمتجر' : 'Return to Store' }}</span>
                    </a>
                </div>
            </aside>

            <!-- Main Content Area -->
            <main class="flex flex-col gap-8">

                <!-- PANEL: Settings -->
                <div x-show="activeTab === 'settings'" class="flex flex-col gap-8">
                    <!-- Title & Header -->
                    <div class="bg-white rounded-[24px] border border-gray-200/80 p-6 lg:p-8 shadow-sm text-start">
                        <h1 class="text-2xl font-black text-[#1A4231]">{{ $t['settings'] }}</h1>
                        <p class="text-slate-500 text-sm font-semibold mt-2">{{ $t['settings_desc'] }}</p>
                    </div>

                    <!-- Personal Information Form -->
                    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-8">
                        @csrf
                        <div class="bg-white rounded-[24px] border border-gray-200/80 p-6 lg:p-8 shadow-sm text-start flex flex-col gap-6">
                            <h3 class="text-lg font-bold text-[#1A4231] pb-3 border-b border-gray-100 flex items-center gap-2">
                                <span class="w-2 h-6 bg-[#1A4231] rounded-full"></span>
                                {{ $t['personal_info'] }}
                            </h3>

                            <!-- Profile Picture Upload -->
                            <div class="flex items-center gap-6">
                                <div class="relative w-24 h-24">
                                    <img :src="previewImage" alt="Upload Avatar" class="w-full h-full rounded-full object-cover border-2 border-gray-200">
                                    <label class="absolute bottom-0 right-0 w-8 h-8 rounded-full bg-[#1A4231] hover:bg-[#133224] text-white flex items-center justify-center cursor-pointer shadow-md transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                        <input type="file" name="image" class="hidden" accept="image/*" @change="
                                            const file = $event.target.files[0];
                                            if (file) {
                                                const reader = new FileReader();
                                                reader.onload = (e) => { previewImage = e.target.result; };
                                                reader.readAsDataURL(file);
                                            }
                                        ">
                                    </label>
                                </div>
                                <div class="text-start">
                                    <h4 class="text-sm font-bold text-gray-700">{{ $t['profile_pic'] }}</h4>
                                    <p class="text-xs text-slate-400 mt-1">PNG, JPG or GIF. Max 2MB.</p>
                                </div>
                            </div>

                            <div class="profile-form-grid">
                                <!-- Full Name -->
                                <div class="flex flex-col gap-2">
                                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t['full_name'] }}</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                           class="profile-input">
                                </div>

                                <!-- Email Address -->
                                <div class="flex flex-col gap-2">
                                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t['email_addr'] }}</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                           class="profile-input">
                                </div>

                                <!-- Phone Number -->
                                <div class="flex flex-col gap-2 profile-form-grid full-width">
                                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t['phone_num'] }}</label>
                                    <div class="flex gap-2">
                                        <!-- Country Code Dropdown -->
                                        <select class="profile-select">
                                            <option>🇴🇲 +968</option>
                                            <option>🇶🇦 +974</option>
                                            <option>🇸🇦 +966</option>
                                            <option>🇦🇪 +971</option>
                                        </select>
                                        <input type="text" name="number" value="{{ old('number', $user->Number) }}" required
                                               class="profile-input">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Change Password Card -->
                        <div class="bg-white rounded-[24px] border border-gray-200/80 p-6 lg:p-8 shadow-sm text-start flex flex-col gap-6">
                            <h3 class="text-lg font-bold text-[#1A4231] pb-3 border-b border-gray-100 flex items-center gap-2">
                                <span class="w-2 h-6 bg-[#1A4231] rounded-full"></span>
                                {{ $t['change_password'] }}
                            </h3>

                            <div class="profile-form-grid">
                                <!-- Current Password -->
                                <div class="flex flex-col gap-2">
                                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t['current_password'] }}</label>
                                    <input type="password" placeholder="••••••••"
                                           class="profile-input">
                                </div>

                                <!-- New Password -->
                                <div class="flex flex-col gap-2">
                                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t['new_password'] }}</label>
                                    <input type="password" name="password" placeholder="Min. 8 characters"
                                           class="profile-input">
                                </div>

                                <!-- Confirm Password -->
                                <div class="flex flex-col gap-2">
                                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t['confirm_password'] }}</label>
                                    <input type="password" name="password_confirmation" placeholder="Confirm password"
                                           class="profile-input">
                                </div>
                            </div>
                        </div>

                        <!-- Notification Preferences Card -->
                        <div class="bg-white rounded-[24px] border border-gray-200/80 p-6 lg:p-8 shadow-sm text-start flex flex-col gap-6">
                            <h3 class="text-lg font-bold text-[#1A4231] pb-3 border-b border-gray-100 flex items-center gap-2">
                                <span class="w-2 h-6 bg-[#1A4231] rounded-full"></span>
                                {{ $t['notif_pref'] }}
                            </h3>

                            <div class="flex flex-col gap-4">
                                <!-- Preference 1 -->
                                <label class="flex items-start gap-4 p-4 rounded-xl border border-gray-100 hover:bg-gray-50/50 cursor-pointer transition-colors">
                                    <input type="checkbox" name="offer_types[]" value="email" checked
                                           class="w-5 h-5 rounded border-gray-300 text-[#1A4231] focus:ring-[#1A4231] mt-0.5">
                                    <div class="flex flex-col text-start">
                                        <span class="text-sm font-bold text-gray-800">{{ $t['email_notif'] }}</span>
                                        <span class="text-xs text-slate-500 mt-0.5">{{ $t['email_notif_desc'] }}</span>
                                    </div>
                                </label>

                                <!-- Preference 2 -->
                                <label class="flex items-start gap-4 p-4 rounded-xl border border-gray-100 hover:bg-gray-50/50 cursor-pointer transition-colors">
                                    <input type="checkbox" name="offer_types[]" value="sms" checked
                                           class="w-5 h-5 rounded border-gray-300 text-[#1A4231] focus:ring-[#1A4231] mt-0.5">
                                    <div class="flex flex-col text-start">
                                        <span class="text-sm font-bold text-gray-800">{{ $t['sms_notif'] }}</span>
                                        <span class="text-xs text-slate-500 mt-0.5">{{ $t['sms_notif_desc'] }}</span>
                                    </div>
                                </label>

                                <!-- Preference 3 -->
                                <label class="flex items-start gap-4 p-4 rounded-xl border border-gray-100 hover:bg-gray-50/50 cursor-pointer transition-colors">
                                    <input type="checkbox" name="offer_types[]" value="push"
                                           class="w-5 h-5 rounded border-gray-300 text-[#1A4231] focus:ring-[#1A4231] mt-0.5">
                                    <div class="flex flex-col text-start">
                                        <span class="text-sm font-bold text-gray-800">{{ $t['push_notif'] }}</span>
                                        <span class="text-xs text-slate-500 mt-0.5">{{ $t['push_notif_desc'] }}</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end items-center gap-4">
                            <button type="button" @click="window.location.reload()" class="text-[#1A4231] font-bold text-sm hover:underline py-3 px-6 rounded-xl">
                                {{ $t['discard'] }}
                            </button>
                            <button type="submit" class="bg-[#1A4231] hover:bg-[#133224] text-white font-bold text-sm py-3.5 px-8 rounded-full shadow-md hover:scale-[1.01] transition-all">
                                {{ $t['save_changes'] }}
                            </button>
                        </div>
                    </form>
                </div>


                <!-- PANEL: Orders -->
                <div x-show="activeTab === 'orders'" class="flex flex-col gap-8" x-cloak>
                    <!-- LIST VIEW -->
                    <div x-show="ordersView === 'list'" class="flex flex-col gap-8">
                        <!-- Title & Header -->
                        <div class="bg-white rounded-[24px] border border-gray-200/80 p-6 lg:p-8 shadow-sm text-start">
                            <h1 class="text-2xl font-black text-[#1A4231]">{{ $t['my_orders'] }}</h1>
                            <p class="text-slate-500 text-sm font-semibold mt-2">{{ $t['my_orders_desc'] }}</p>
                        </div>

                        <!-- Filters Card -->
                        <div class="bg-white rounded-[24px] border border-gray-200/80 p-6 shadow-sm">
                            <div class="flex flex-col sm:flex-row gap-4 items-end">
                                <!-- Dropdown 1 -->
                                <div class="w-full flex flex-col gap-2 text-start">
                                    <label class="text-xs font-bold text-gray-600 uppercase tracking-wider">{{ $t['order_status'] }}</label>
                                    <select class="w-full bg-[#FDF9F0]/40 border border-gray-200 rounded-[14px] py-3 px-4 text-sm font-bold text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#1A4231] focus:bg-white transition-all">
                                        <option>{{ $t['all_orders'] }}</option>
                                        <option>{{ $isRtl ? 'قيد المعالجة' : 'Processing' }}</option>
                                        <option>{{ $isRtl ? 'تم الشحن' : 'Shipped' }}</option>
                                        <option>{{ $isRtl ? 'تم التوصيل' : 'Delivered' }}</option>
                                        <option>{{ $isRtl ? 'ملغي' : 'Cancelled' }}</option>
                                    </select>
                                </div>
                                <!-- Dropdown 2 -->
                                <div class="w-full flex flex-col gap-2 text-start">
                                    <label class="text-xs font-bold text-gray-600 uppercase tracking-wider">{{ $t['date_range'] }}</label>
                                    <select class="w-full bg-[#FDF9F0]/40 border border-gray-200 rounded-[14px] py-3 px-4 text-sm font-bold text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#1A4231] focus:bg-white transition-all">
                                        <option>{{ $t['last_30_days'] }}</option>
                                        <option>{{ $isRtl ? 'آخر 6 أشهر' : 'Last 6 Months' }}</option>
                                        <option>{{ $isRtl ? 'طوال الوقت' : 'All Time' }}</option>
                                    </select>
                                </div>
                                <!-- Apply button -->
                                <button class="bg-[#1A4231] hover:bg-[#133224] text-white font-bold py-3 px-8 rounded-full text-sm shadow-md transition-all whitespace-nowrap h-[46px] flex items-center justify-center">
                                    {{ $t['apply_filters'] }}
                                </button>
                            </div>
                        </div>

                        <!-- Orders Table Card -->
                        <div class="bg-white rounded-[24px] border border-gray-200/80 shadow-sm overflow-hidden">
                            <div class="overflow-x-auto" style="overflow-x: auto; width: 100%;">
                                <table class="w-full text-start border-collapse" style="width: 100%; border-collapse: collapse;">
                                    <thead>
                                        <tr class="bg-gray-50/70 border-b border-gray-100 text-[#1A4231] text-[11px] font-black tracking-wider uppercase">
                                            <th class="py-4 px-6 text-start">{{ $t['order_details'] }}</th>
                                            <th class="py-4 px-6 text-start">{{ $t['date'] }}</th>
                                            <th class="py-4 px-6 text-start">{{ $t['items'] }}</th>
                                            <th class="py-4 px-6 text-start">{{ $t['total'] }}</th>
                                            <th class="py-4 px-6 text-start">{{ $t['status'] }}</th>
                                            <th class="py-4 px-6 text-center">{{ $t['actions'] }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 text-sm font-semibold text-gray-700">
                                        @forelse($orders as $order)
                                            @php
                                                $methodLabel = '';
                                                if (isset($order->collection_method_ar) || (is_object($order) && property_exists($order, 'collection_method_ar'))) {
                                                    $methodLabel = $isRtl ? $order->collection_method_ar : $order->collection_method_label;
                                                } else {
                                                    $methodLabel = $isRtl ? ($order->collection_method == 'pickup' ? 'استلام من المخزن' : 'توصيل') : ($order->collection_method == 'pickup' ? 'Warehouse Pickup' : 'Delivery');
                                                }
                                            @endphp
                                            <tr class="hover:bg-gray-50/30 transition-colors">
                                                <!-- Number / Type -->
                                                <td class="py-4 px-6 text-start">
                                                    <span class="block font-bold text-gray-800">#{{ $order->Order_Number }}</span>
                                                    <span class="text-xs text-slate-400 mt-0.5 block font-medium">
                                                        {{ $methodLabel }}
                                                    </span>
                                                </td>
                                                <!-- Date -->
                                                <td class="py-4 px-6 text-start">
                                                    <span>{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</span>
                                                </td>
                                                <!-- Items snippet -->
                                                <td class="py-4 px-6 text-start max-w-[200px] truncate">
                                                    @php
                                                        $itemSnippets = [];
                                                        foreach($order->order_details as $detail) {
                                                            $prodName = $isRtl ? ($detail->product->fr_Product_Name ?? $detail->product->en_Product_Name ?? 'منتج') : ($detail->product->en_Product_Name ?? 'Product');
                                                            $itemSnippets[] = $detail->Quantity . 'x ' . $prodName;
                                                        }
                                                        $snippetText = implode(', ', $itemSnippets);
                                                    @endphp
                                                    <span class="text-xs font-semibold text-slate-500" title="{{ $snippetText }}">
                                                        {{ Str::limit($snippetText, 25) }}
                                                    </span>
                                                </td>
                                                <!-- Total -->
                                                <td class="py-4 px-6 text-start">
                                                    <span class="font-extrabold text-[#1A4231]">{{ number_format($order->Grand_Total, 2) }} {{ $isRtl ? 'ر.ع.' : 'OMR' }}</span>
                                                </td>
                                                <!-- Status badge -->
                                                <td class="py-4 px-6 text-start">
                                                    @if($order->Order_Status == ORDER_DELIVERED)
                                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-[#387C5F]/10 text-[#387C5F]">
                                                            {{ $isRtl ? 'تم التوصيل' : 'Completed' }}
                                                        </span>
                                                    @elseif($order->Order_Status == ORDER_CANCELLED || $order->Order_Status == ORDER_DELIVERED_FAILED)
                                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-500">
                                                            {{ $isRtl ? 'ملغي' : 'Cancelled' }}
                                                        </span>
                                                    @else
                                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-600">
                                                            {{ $isRtl ? 'قيد المعالجة' : 'Processing' }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <!-- Actions -->
                                                <td class="py-4 px-6 text-center">
                                                    <div class="flex items-center justify-center gap-3">
                                                        <!-- View Eye -->
                                                        <button @click="
                                                             selectedOrder = window.profileOrdersData.find(o => o.number === '{{ $order->Order_Number }}');
                                                             ordersView = 'details';
                                                         " class="text-slate-400 hover:text-[#1A4231] transition-colors p-1.5 rounded-lg hover:bg-gray-50">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                            </svg>
                                                        </button>
                                                        
                                                        <!-- Reorder Pill -->
                                                        <button type="button" @click="reorderOrder('{{ $order->Order_Number }}')"
                                                                class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-full text-xs font-bold transition-all">
                                                            {{ $t['reorder'] }}
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="py-8 text-center text-slate-400 font-bold">
                                                    {{ $isRtl ? 'لا توجد طلبات سابقة' : 'No orders found' }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination (Mockup) -->
                            <div class="bg-gray-50/50 border-t border-gray-100 py-4 px-6 flex items-center justify-between font-bold text-xs text-slate-500">
                                <div>
                                    {{ $isRtl ? 'عرض 1 إلى 3 من 3 طلبات' : 'Showing 1 to 3 of 3 orders' }}
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <button class="w-7 h-7 rounded-full bg-white border border-gray-200 flex items-center justify-center text-slate-400 hover:bg-gray-50 transition-colors">&lt;</button>
                                    <button class="w-7 h-7 rounded-full bg-[#1A4231] text-white flex items-center justify-center shadow-sm">1</button>
                                    <button class="w-7 h-7 rounded-full bg-white border border-gray-200 flex items-center justify-center text-slate-600 hover:bg-gray-50 transition-colors">&gt;</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DETAILS VIEW -->
                    <div x-show="ordersView === 'details'" class="flex flex-col gap-8" x-cloak>
                        
                        <!-- Order Header Card -->
                        <div class="bg-white rounded-[24px] border border-gray-200/80 shadow-sm overflow-hidden flex flex-col md:flex-row justify-between relative group hover:border-[#1A4231]/30 transition-all duration-300">
                            <!-- Left Content -->
                            <div class="p-6 lg:p-8 flex-1 flex flex-col justify-between text-start">
                                <div>
                                    <!-- Breadcrumb / Status -->
                                    <div class="flex flex-wrap items-center gap-3 mb-4">
                                        <span class="text-slate-400 font-bold text-sm flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                            {{ $isRtl ? 'طلب' : 'Order' }} <span x-text="selectedOrder ? '#' + selectedOrder.number : ''"></span>
                                        </span>
                                        <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span>
                                        <span class="text-[#387C5F] font-black text-[11px] uppercase tracking-wider"
                                              x-text="selectedOrder && selectedOrder.status === '{{ ORDER_DELIVERED }}' ? '{{ $isRtl ? '• تم التوصيل اليوم الساعة 6:45 م' : '• DELIVERED TODAY AT 6:45 PM' }}' : (selectedOrder && (selectedOrder.status === '{{ ORDER_CANCELLED }}' || selectedOrder.status === '{{ ORDER_DELIVERED_FAILED }}') ? '{{ $isRtl ? '• ملغي' : '• CANCELLED' }}' : '{{ $isRtl ? '• قيد المعالجة' : '• PROCESSING' }}')">
                                        </span>
                                    </div>

                                    <!-- Shop Name -->
                                    <h1 class="text-3xl font-black text-[#1A4231]">{{ $isRtl ? 'بن القطر' : 'El Katar Coffee' }}</h1>
                                    
                                    <!-- Date, items, and total summary -->
                                    <p class="text-slate-500 text-sm font-semibold mt-2">
                                        <span x-text="selectedOrder ? selectedOrder.date : ''"></span>
                                        <span class="mx-1.5">•</span>
                                        <span x-text="selectedOrder ? selectedOrder.items.length + ' {{ $isRtl ? 'منتجات' : 'Items' }}' : ''"></span>
                                        <span class="mx-1.5">•</span>
                                        <span x-text="selectedOrder ? selectedOrder.total : ''"></span>
                                    </p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center gap-3 mt-6">
                                    <button @click="reorderOrder(selectedOrder.number)" 
                                            class="bg-[#1A4231] hover:bg-[#133224] text-white px-5 py-2.5 rounded-full text-xs font-black flex items-center gap-2 shadow-sm transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.2"/></svg>
                                        {{ $t['reorder'] }}
                                    </button>
                                    <button @click="triggerToast('{{ $isRtl ? 'تم إرسال طلب الدعم بنجاح!' : 'Support ticket requested!' }}', 'success')"
                                            class="border border-gray-300 hover:bg-gray-50 text-slate-600 px-5 py-2.5 rounded-full text-xs font-black transition-all">
                                        {{ $isRtl ? 'احصل على مساعدة' : 'Get Help' }}
                                    </button>
                                </div>
                            </div>

                            <!-- Right Coffee Cup Image Visual -->
                            <div class="w-full md:w-56 h-48 md:h-auto relative overflow-hidden bg-gray-100 flex items-center justify-center shrink-0">
                                <img src="https://images.unsplash.com/photo-1498804103079-a6351b050096?q=80&w=600&auto=format&fit=crop" 
                                     alt="Coffee cup layout" class="w-full h-full object-cover">
                            </div>
                        </div>

                        <!-- Order Tracking Stepper Card -->
                        <div class="bg-white rounded-[24px] border border-gray-200/80 p-6 lg:p-8 shadow-sm text-start flex flex-col gap-6">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">{{ $t['order_tracking'] }}</h3>
                            
                            <!-- Stepper Container -->
                            <div class="relative flex flex-col md:flex-row justify-between items-center gap-8 md:gap-4 w-full mt-4">
                                <!-- Stepper Progress Line -->
                                <div class="absolute top-[20px] left-[12%] right-[12%] hidden md:block h-1 bg-gray-100 -z-10 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#387C5F] transition-all duration-500"
                                         :style="selectedOrder ? 'width: ' + getProgressPercentage(selectedOrder.status) + '%' : 'width: 0%'">
                                    </div>
                                </div>

                                <!-- Step 1: Confirmed -->
                                <div class="flex md:flex-col items-center gap-4 md:gap-2 text-start md:text-center w-full md:w-1/4">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300 border-2"
                                         :class="isStepActive(selectedOrder, 'confirmed') ? 'bg-[#387C5F] border-[#387C5F] text-white' : 'bg-white border-gray-200 text-slate-400'">
                                        ✓
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-extrabold text-[#1A4231]">{{ $t['confirmed'] }}</h4>
                                        <p class="text-[10px] text-slate-400 font-semibold mt-0.5" x-text="selectedOrder ? selectedOrder.date : ''"></p>
                                    </div>
                                </div>

                                <!-- Step 2: Processing -->
                                <div class="flex md:flex-col items-center gap-4 md:gap-2 text-start md:text-center w-full md:w-1/4">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300 border-2"
                                         :class="isStepActive(selectedOrder, 'processing') ? 'bg-[#387C5F] border-[#387C5F] text-white' : 'bg-white border-gray-200 text-slate-400'">
                                        2
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-extrabold text-[#1A4231]">{{ $t['processing'] }}</h4>
                                        <p class="text-[10px] text-slate-400 font-semibold mt-0.5" 
                                           x-text="isStepActive(selectedOrder, 'processing') ? '{{ $isRtl ? "جاري التجهيز" : "In preparation" }}' : ''"></p>
                                    </div>
                                </div>

                                <!-- Step 3: Shipped -->
                                <div class="flex md:flex-col items-center gap-4 md:gap-2 text-start md:text-center w-full md:w-1/4">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300 border-2"
                                         :class="isStepActive(selectedOrder, 'shipped') ? 'bg-[#387C5F] border-[#387C5F] text-white' : 'bg-white border-gray-200 text-slate-400'">
                                        3
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-extrabold text-[#1A4231]">{{ $t['shipped'] }}</h4>
                                        <p class="text-[10px] text-slate-400 font-semibold mt-0.5"
                                           x-text="isStepActive(selectedOrder, 'shipped') ? '{{ $isRtl ? "على الطريق" : "On the way" }}' : ''"></p>
                                    </div>
                                </div>

                                <!-- Step 4: Delivered / Cancelled -->
                                <div class="flex md:flex-col items-center gap-4 md:gap-2 text-start md:text-center w-full md:w-1/4">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300 border-2"
                                         :class="selectedOrder && (selectedOrder.status === 5 || selectedOrder.status === 8) ? 'bg-red-500 border-red-500 text-white' : (isStepActive(selectedOrder, 'delivered') ? 'bg-[#387C5F] border-[#387C5F] text-white' : 'bg-white border-gray-200 text-slate-400')">
                                        <span x-text="selectedOrder && (selectedOrder.status === 5 || selectedOrder.status === 8) ? '✗' : '4'"></span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-extrabold text-[#1A4231]" 
                                            x-text="selectedOrder && (selectedOrder.status === 5 || selectedOrder.status === 8) ? '{{ $t['cancelled'] }}' : '{{ $t['delivered'] }}'"></h4>
                                        <p class="text-[10px] text-slate-400 font-semibold mt-0.5"
                                           x-text="isStepActive(selectedOrder, 'delivered') ? '{{ $isRtl ? "تم التوصيل" : "Delivered" }}' : ''"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Grid Columns (Details & Summary) -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            
                            <!-- Left Column: Items & Delivery Details (spans 2 on large screens) -->
                            <div class="lg:col-span-2 flex flex-col gap-6">
                                
                                <!-- Card: Items Ordered -->
                                <div class="bg-white rounded-[24px] border border-gray-200/80 p-6 lg:p-8 shadow-sm text-start">
                                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">{{ $t['items_ordered'] }}</h3>
                                    
                                    <div class="flex flex-col divide-y divide-gray-100">
                                        <template x-for="item in (selectedOrder ? selectedOrder.items : [])" :key="item.name">
                                            <div class="py-4 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
                                                <div class="flex items-center gap-4">
                                                    <!-- Thumbnail image -->
                                                    <img :src="item.image" alt="Product thumbnail" 
                                                         class="w-16 h-16 rounded-xl object-cover border border-gray-200/60 shadow-sm shrink-0">
                                                    
                                                    <div class="flex flex-col text-start">
                                                        <span class="font-extrabold text-[#1A4231] text-sm" x-text="item.name"></span>
                                                        <span class="text-xs text-slate-400 font-semibold mt-0.5" x-text="item.volume"></span>
                                                        <span class="text-xs text-slate-500 font-bold mt-1" x-text="'{{ $isRtl ? 'الكمية: ' : 'Qty: ' }}' + item.qty"></span>
                                                    </div>
                                                </div>
                                                <div class="flex flex-col items-end gap-2 shrink-0">
                                                    <span class="font-black text-[#1A4231] text-base" x-text="item.price"></span>
                                                    <template x-if="selectedOrder && selectedOrder.status === 4">
                                                        <button @click="openReviewModal(item)"
                                                                class="bg-[#1A4231]/5 hover:bg-[#1A4231]/10 text-[#1A4231] px-3.5 py-1.5 rounded-full text-xs font-bold transition-all whitespace-nowrap">
                                                            {{ $t['review_product'] }}
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Card: Delivery Details -->
                                <div class="bg-white rounded-[24px] border border-gray-200/80 p-6 lg:p-8 shadow-sm text-start">
                                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">{{ $t['delivery_details'] }}</h3>
                                    
                                    <div class="flex flex-col gap-2">
                                        <span class="text-red-500 font-black text-[10px] uppercase tracking-widest">{{ $isRtl ? 'عنوان التوصيل' : 'DELIVERY ADDRESS' }}</span>
                                        <h4 class="font-extrabold text-[#1A4231] text-base" x-text="selectedOrder ? selectedOrder.delivery_name : ''"></h4>
                                        <p class="text-sm font-semibold text-slate-500 leading-relaxed mt-1" x-text="selectedOrder ? selectedOrder.delivery_address : ''"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Summary & Payment -->
                            <div class="flex flex-col gap-6">
                                
                                <!-- Card: Payment Summary (Dark Green background) -->
                                <div class="bg-[#1A4231] rounded-[24px] p-6 lg:p-8 text-white text-start shadow-md flex flex-col justify-between">
                                    <div>
                                        <h3 class="text-xs font-black text-white/60 uppercase tracking-widest mb-6">{{ $t['payment_summary'] }}</h3>
                                        
                                        <div class="flex flex-col gap-4 text-sm font-semibold">
                                            <!-- Subtotal -->
                                            <div class="flex justify-between items-center text-white/90">
                                                <span>{{ $t['subtotal'] }}</span>
                                                <span x-text="selectedOrder ? selectedOrder.subtotal : ''"></span>
                                            </div>
                                            <!-- Delivery Fee -->
                                            <div class="flex justify-between items-center text-white/90">
                                                <span>{{ $t['delivery_fee'] }}</span>
                                                <span x-text="selectedOrder ? selectedOrder.delivery_fee : ''"></span>
                                            </div>
                                            <!-- Service Fee -->
                                            <div class="flex justify-between items-center text-white/90">
                                                <span>{{ $t['service_fee'] }}</span>
                                                <span x-text="selectedOrder ? selectedOrder.service_fee : ''"></span>
                                            </div>
                                            <!-- Tax -->
                                            <div class="flex justify-between items-center text-white/90">
                                                <span>{{ $t['tax'] }}</span>
                                                <span x-text="selectedOrder ? selectedOrder.tax : ''"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Divider -->
                                    <div class="border-t border-white/10 my-6"></div>

                                    <!-- Grand Total -->
                                    <div class="flex justify-between items-center">
                                        <span class="text-base font-black uppercase text-white/70">{{ $t['total'] }}</span>
                                        <span class="text-2xl font-black text-white" x-text="selectedOrder ? selectedOrder.total : ''"></span>
                                    </div>
                                </div>

                                <!-- Card: Payment Method -->
                                <div class="bg-white rounded-[24px] border border-gray-200/80 p-6 lg:p-8 shadow-sm text-start flex flex-col gap-4">
                                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">{{ $t['payment_method'] }}</h3>
                                    
                                    <div class="flex items-center gap-4 p-4 rounded-xl border border-gray-100 bg-gray-50/50">
                                        <!-- Visa/Card Icon matching screenshot style -->
                                        <div class="w-12 h-8 rounded bg-slate-900 flex items-center justify-center text-white font-black italic tracking-wider text-[11px] shrink-0 shadow-sm"
                                             x-text="selectedOrder ? selectedOrder.payment_method_brand : 'VISA'">
                                        </div>
                                        
                                        <div class="flex flex-col text-start">
                                            <span class="text-sm font-extrabold text-[#1A4231]" 
                                                  x-text="(selectedOrder ? selectedOrder.payment_method_brand : 'Visa') + ' Ending in ' + (selectedOrder ? selectedOrder.payment_method_last4 : '4242')"></span>
                                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5"
                                                  x-text="selectedOrder && selectedOrder.payment_method_brand === 'Visa' ? '{{ $isRtl ? 'بطاقة شخصية' : 'Personal Card' }}' : ''"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Back Button & Support Footer -->
                        <div class="flex flex-col items-center gap-4 mt-4 pt-6 border-t border-gray-100">
                            <!-- Back Button -->
                            <button @click="ordersView = 'list'; selectedOrder = null;" 
                                    class="bg-white hover:bg-gray-50 border border-gray-300 text-slate-600 font-bold text-sm py-2.5 px-6 rounded-full shadow-sm flex items-center gap-1.5 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                {{ $isRtl ? 'العودة لقائمة الطلبات' : 'Back to Orders List' }}
                            </button>

                            <!-- Center Help Support Text -->
                            <p class="text-xs font-bold text-slate-400 flex items-center justify-center gap-1 flex-wrap">
                                <span>{{ $t['contact_support_question'] }}</span>
                                <a href="javascript:void(0)" @click="triggerToast('{{ $isRtl ? 'سيتم توجيهك إلى الدعم الفني قريباً' : 'Redirecting to support...' }}', 'info')" 
                                   class="text-red-500 hover:text-red-700 hover:underline">
                                    {{ $t['contact_support_link'] }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>


                <!-- PANEL: Addresses -->
                <div x-show="activeTab === 'addresses'" class="flex flex-col gap-8" x-cloak>
                    <!-- LIST VIEW -->
                    <div x-show="addressView === 'list'" class="flex flex-col gap-8">
                        <!-- Title & Header -->
                        <div class="bg-white rounded-[24px] border border-gray-200/80 p-6 lg:p-8 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 text-start">
                            <div>
                                <h1 class="text-2xl font-black text-[#1A4231]">{{ $t['my_addresses'] }}</h1>
                                <p class="text-slate-500 text-sm font-semibold mt-2">{{ $t['my_addresses_desc'] }}</p>
                            </div>
                            <button @click="initAddressForm()" class="bg-[#1A4231] hover:bg-[#133224] text-white font-bold py-3 px-6 rounded-full text-sm shadow-md transition-all whitespace-nowrap flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                                {{ $t['add_new_address'] }}
                            </button>
                        </div>

                        <!-- Addresses Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <template x-for="addr in addressesList" :key="addr.id">
                                <div class="bg-white rounded-[24px] border border-gray-200/80 shadow-sm overflow-hidden flex flex-col justify-between hover:border-[#1A4231]/30 transition-all duration-300">
                                    
                                    <!-- Map Preview Visual -->
                                    <div class="h-28 w-full relative overflow-hidden bg-[#EDEAE3] flex items-center justify-center">
                                        <!-- Stylized map lines SVG background -->
                                        <svg class="absolute inset-0 w-full h-full text-gray-300 opacity-60" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 100 100" preserveAspectRatio="none">
                                            <path d="M0,20 Q40,40 100,10 M10,0 L30,100 M90,0 Q60,50 40,100 M0,70 L100,80 M50,0 L50,100" />
                                            <circle cx="45" cy="35" r="1.5" fill="#1A4231" />
                                            <circle cx="75" cy="65" r="1.5" fill="#1A4231" />
                                        </svg>
                                        <!-- Map image placeholder or background matching color -->
                                        <div class="absolute inset-0 bg-[#387C5F]/10 mix-blend-multiply"></div>
                                        
                                        <!-- Type Badge (pill) -->
                                        <span class="absolute top-4 left-4 bg-red-50 text-red-500 text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider shadow-sm"
                                              x-text="addr.type === 'work' ? '{{ $isRtl ? 'عمل' : 'WORK' }}' : '{{ $isRtl ? 'منزل' : 'HOME' }}'">
                                        </span>
                                    </div>

                                    <!-- Details -->
                                    <div class="p-6 flex-1 flex flex-col justify-between text-start">
                                        <div>
                                            <div class="flex items-center justify-between mb-3">
                                                <h3 class="text-base font-extrabold text-[#1A4231]" x-text="addr.title"></h3>
                                                <!-- Icon -->
                                                <template x-if="addr.type === 'work'">
                                                    <span class="text-red-500">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                        </svg>
                                                    </span>
                                                </template>
                                                <template x-if="addr.type !== 'work'">
                                                    <span class="text-[#387C5F]">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                                        </svg>
                                                    </span>
                                                </template>
                                            </div>
                                            <!-- Full Address Block -->
                                            <p class="text-sm font-bold text-gray-700 leading-relaxed" 
                                               x-text="addr.street + (addr.building_no ? ', ' + addr.building_no : '') + (addr.apartment ? ', ' + addr.apartment : '') + ', ' + addr.city + ', ' + (addr.zip_code ? addr.zip_code + ', ' : '') + addr.country"></p>
                                            
                                            <!-- Phone Number -->
                                            <p class="text-xs text-slate-500 font-semibold mt-3 flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-[#1A4231]/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                <span x-text="addr.phone"></span>
                                            </p>
                                        </div>

                                        <!-- Actions Row -->
                                        <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-100 text-xs font-bold">
                                            <!-- Edit button style matching screenshot -->
                                            <button @click="initAddressForm(addr)" class="bg-[#F8F9F8] hover:bg-[#1A4231]/5 text-[#1A4231] px-4 py-2 rounded-xl flex items-center gap-1.5 transition-all">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                {{ $t['edit'] }}
                                            </button>
                                            <!-- Delete button style matching screenshot -->
                                            <button @click="deleteAddress(addr.id)" class="text-red-500 hover:text-red-700 flex items-center gap-1 transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                {{ $t['remove'] }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- Add Another Address Card matching screenshot styling -->
                            <button @click="initAddressForm()"
                                    class="bg-transparent border-2 border-dashed border-gray-300 hover:border-[#1A4231] rounded-[24px] p-6 min-h-[260px] flex flex-col items-center justify-center text-center group transition-all">
                                <div class="w-12 h-12 rounded-full bg-white group-hover:bg-[#1A4231] text-[#1A4231] group-hover:text-white flex items-center justify-center shadow-md border border-gray-200 transition-all duration-300 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                                <span class="text-base font-extrabold text-[#1A4231] transition-colors">{{ $t['add_another_address'] }}</span>
                                <span class="text-xs text-slate-500 font-semibold mt-1">{{ $t['gym_parents_friends'] }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- FORM VIEW -->
                    <div x-show="addressView === 'form'" class="bg-white rounded-[24px] border border-gray-200/80 p-6 lg:p-8 shadow-sm text-start flex flex-col gap-6" x-cloak>
                        <div>
                            <span class="text-red-500 font-black text-xs uppercase tracking-widest flex items-center gap-1.5 mb-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $isRtl ? 'تفاصيل العنوان' : 'ADDRESS DETAILS' }}
                            </span>
                            <h2 class="text-2xl font-black text-[#1A4231]" x-text="formId ? '{{ $isRtl ? "تعديل العنوان" : "Edit Address" }}' : '{{ $isRtl ? "إضافة عنوان جديد" : "Add Address" }}'"></h2>
                            <p class="text-slate-500 text-sm font-semibold mt-1">{{ $isRtl ? 'سنستخدم هذه المعلومات لضمان وصول طلبك طازجاً وفي الوقت المحدد.' : 'We\'ll use this information to ensure your food arrives fresh and on time.' }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <!-- Address Label -->
                            <div class="col-span-1 md:col-span-2 flex flex-col gap-2">
                                <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t['address_label'] }}</label>
                                <input type="text" x-model="formLabel" :placeholder="'{{ $t['address_label_placeholder'] }}'"
                                       class="profile-input">
                            </div>

                            <!-- Street Address -->
                            <div class="col-span-1 md:col-span-2 flex flex-col gap-2 relative">
                                <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t['street_address'] }}</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                        <!-- Map Icon inside input -->
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                    </span>
                                    <input type="text" x-model="formStreet" :placeholder="'{{ $t['street_address_placeholder'] }}'"
                                           class="profile-input pl-11">
                                </div>
                            </div>

                            <!-- Building Number -->
                            <div class="flex flex-col gap-2">
                                <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t['building_number'] }}</label>
                                <input type="text" x-model="formBuilding" placeholder="Bldg 5"
                                       class="profile-input">
                            </div>

                            <!-- Apartment/Floor -->
                            <div class="flex flex-col gap-2">
                                <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t['apartment_floor'] }}</label>
                                <input type="text" x-model="formApartment" placeholder="Apt 4B"
                                       class="profile-input">
                            </div>

                            <!-- City -->
                            <div class="flex flex-col gap-2">
                                <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t['city'] }}</label>
                                <input type="text" x-model="formCity" placeholder="San Francisco"
                                       class="profile-input">
                            </div>

                            <!-- Phone Number -->
                            <div class="flex flex-col gap-2">
                                <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t['phone_number'] }}</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 font-bold text-sm">
                                        📞
                                    </span>
                                    <input type="text" x-model="formPhone" placeholder="+1 (555) 000-0000"
                                           class="profile-input pl-11">
                                </div>
                            </div>

                            <!-- Delivery Notes -->
                            <div class="col-span-1 md:col-span-2 flex flex-col gap-2">
                                <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t['delivery_notes'] }}</label>
                                <textarea x-model="formNotes" rows="3" :placeholder="'{{ $t['delivery_notes_placeholder'] }}'"
                                          class="profile-input resize-none"></textarea>
                            </div>
                        </div>

                        <!-- Actions Buttons -->
                        <div class="flex flex-col gap-3 mt-6">
                            <button @click="saveAddress()"
                                    class="bg-[#1A4231] hover:bg-[#133224] text-white font-bold py-3.5 px-8 rounded-full shadow-md flex items-center justify-center gap-2 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                {{ $t['save_address'] }}
                            </button>
                            <button @click="addressView = 'list'"
                                    class="text-slate-500 hover:text-[#1A4231] font-bold text-sm hover:underline py-2 text-center transition-colors">
                                {{ $t['cancel'] }}
                            </button>
                        </div>
                    </div>
                </div>


                <!-- PANEL: Payments -->
                <div x-show="activeTab === 'payments'" class="flex flex-col gap-8" x-cloak>
                    <!-- Title & Header -->
                    <div class="bg-white rounded-[24px] border border-gray-200/80 p-6 lg:p-8 shadow-sm text-start">
                        <h1 class="text-2xl font-black text-[#1A4231]">{{ $t['payments'] }}</h1>
                        <p class="text-slate-500 text-sm font-semibold mt-2">{{ $t['payments_desc'] }}</p>
                    </div>

                    <!-- Payment Cards Grid -->
                    <div class="profile-cards-grid">
                        <!-- VISA Card -->
                        <div class="bg-gradient-to-br from-[#1E293B] to-[#0F172A] rounded-[24px] p-6 text-white text-start shadow-md flex flex-col justify-between min-h-[190px] relative group overflow-hidden">
                            <!-- Background decoration -->
                            <div class="absolute -right-10 -bottom-10 w-32 h-32 rounded-full bg-white/5 blur-2xl"></div>
                            
                            <div>
                                <div class="flex items-center justify-between mb-6">
                                    <span class="text-lg font-black italic tracking-wider text-slate-100">VISA</span>
                                    <span class="bg-red-600 text-white text-[9px] font-black px-2 py-0.5 rounded shadow-sm tracking-wider">{{ $t['primary'] }}</span>
                                </div>
                                <p class="text-lg font-bold tracking-widest text-slate-200">•••• •••• •••• 4242</p>
                            </div>
                            <div class="flex items-center justify-between mt-6 text-xs font-bold text-slate-400">
                                <div>
                                    <span class="block text-[10px] text-slate-500 uppercase font-black">{{ $t['expires'] }}</span>
                                    <span class="text-slate-300 font-extrabold">12/26</span>
                                </div>
                                <div class="flex gap-3">
                                    <button @click="triggerToast('{{ $isRtl ? 'تعديل البطاقة سيتوفر قريباً' : 'Edit card coming soon' }}', 'info')" class="hover:text-white transition-colors">{{ $t['edit'] }}</button>
                                    <button @click="triggerToast('{{ $isRtl ? 'حذف البطاقة سيتوفر قريباً' : 'Remove card coming soon' }}', 'info')" class="hover:text-red-400 transition-colors">{{ $t['remove'] }}</button>
                                </div>
                            </div>
                        </div>

                        <!-- Add Card Card -->
                        <button @click="triggerToast('{{ $isRtl ? 'إضافة بطاقة جديدة ستتوفر قريباً' : 'Add card feature coming soon' }}', 'info')"
                                class="bg-transparent border-2 border-dashed border-gray-300 hover:border-[#1A4231] rounded-[24px] p-6 min-h-[190px] flex flex-col items-center justify-center text-center group transition-all">
                            <div class="w-10 h-10 rounded-full bg-gray-100 group-hover:bg-[#1A4231] text-gray-400 group-hover:text-white flex items-center justify-center shadow-sm transition-all duration-300 mb-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-600 group-hover:text-[#1A4231] transition-colors">{{ $t['add_card'] }}</span>
                        </button>
                    </div>

                    <!-- Security Banner -->
                    <div class="bg-white rounded-[24px] border border-gray-200/80 p-6 flex flex-col md:flex-row items-center gap-4 text-start shadow-sm">
                        <div class="w-12 h-12 rounded-full bg-[#1A4231]/5 flex items-center justify-center text-[#1A4231] shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-[#1A4231]">{{ $t['security_privacy'] }}</h4>
                            <p class="text-xs text-slate-500 font-semibold mt-1 leading-relaxed">{{ $t['security_desc'] }}</p>
                        </div>
                        <button type="button" @click="triggerToast('{{ $isRtl ? 'صفحة سياسة الأمان قيد التطوير' : 'Security details page under development' }}', 'info')"
                                class="bg-[#1A4231] hover:bg-[#133224] text-white font-bold py-2.5 px-6 rounded-full text-xs shadow-sm transition-all whitespace-nowrap mt-2 md:mt-0">
                            {{ $t['learn_more'] }}
                        </button>
                    </div>
                </div>

                <!-- PANEL: Reviews -->
                <div x-show="activeTab === 'reviews'" class="flex flex-col gap-8" x-cloak>
                    <!-- Title & Header -->
                    <div class="bg-white rounded-[24px] border border-gray-200/80 p-6 lg:p-8 shadow-sm text-start">
                        <h1 class="text-2xl font-black text-[#1A4231]">{{ $t['my_reviews'] }}</h1>
                        <p class="text-slate-500 text-sm font-semibold mt-2">{{ $t['my_reviews_desc'] }}</p>
                    </div>

                    <!-- Reviews List -->
                    <div class="flex flex-col gap-6">
                        <template x-for="review in reviewsList" :key="review.id">
                            <div class="bg-white rounded-[24px] border border-gray-200/80 p-6 shadow-sm flex flex-col sm:flex-row gap-6 hover:border-[#1A4231]/30 transition-all duration-300">
                                <!-- Product Image -->
                                <div class="w-20 h-20 bg-gray-100 rounded-xl overflow-hidden shrink-0 border border-gray-100">
                                    <img :src="review.product_image" alt="Product Image" class="w-full h-full object-cover">
                                </div>
                                <!-- Review Info -->
                                <div class="flex-1 flex flex-col text-start justify-between">
                                    <div>
                                        <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                            <h3 class="font-extrabold text-[#1A4231] text-base" x-text="review.product_name"></h3>
                                            <span class="text-xs text-slate-400 font-bold" x-text="review.date"></span>
                                        </div>
                                        <!-- Rating Stars -->
                                        <div class="flex items-center gap-1 mb-3 text-amber-400">
                                            <template x-for="i in 5">
                                                <svg class="w-4 h-4 fill-current" :class="i <= review.rating ? 'text-amber-400' : 'text-gray-200'" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.971-2.883a1 1 0 00-1.17 0l-3.971 2.883c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118L2.05 9.43c-.773-.564-.374-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                </svg>
                                            </template>
                                        </div>
                                        <p class="text-sm font-semibold text-slate-600 leading-relaxed" x-text="review.feedback"></p>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="reviewsList.length === 0">
                            <div class="bg-white rounded-[24px] border border-gray-200/80 p-12 text-center shadow-sm">
                                <p class="text-slate-400 font-bold">{{ $t['no_reviews'] }}</p>
                            </div>
                        </template>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- WRITE REVIEW MODAL -->
    <div x-show="showReviewModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         x-cloak>
        <div class="bg-white rounded-[32px] border border-gray-100 shadow-2xl w-full max-w-lg p-6 lg:p-8 text-start flex flex-col gap-6" @click.away="showReviewModal = false">
            <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                <h3 class="text-xl font-black text-[#1A4231]" x-text="'{{ $t['review_product'] }}: ' + (reviewingItem ? reviewingItem.name : '')"></h3>
                <button @click="showReviewModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('user.profile.review_store') }}" method="POST" class="flex flex-col gap-5">
                @csrf
                <input type="hidden" name="product_id" :value="reviewingItem ? reviewingItem.product_id : ''">

                <!-- Rating -->
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t['rating'] }}</label>
                    <div class="flex items-center gap-2">
                        <select name="rating" required class="profile-select w-full">
                            <option value="5" selected>5 ★★★★★</option>
                            <option value="4">4 ★★★★</option>
                            <option value="3">3 ★★★</option>
                            <option value="2">2 ★★</option>
                            <option value="1">1 ★</option>
                        </select>
                    </div>
                </div>

                <!-- Feedback -->
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $t['feedback'] }}</label>
                    <textarea name="feedback" rows="4" required placeholder="{{ $isRtl ? 'اكتب تعليقك هنا...' : 'Write your feedback here...' }}"
                              class="profile-input resize-none"></textarea>
                </div>

                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" @click="showReviewModal = false" class="text-slate-500 font-bold text-sm hover:underline px-4">
                        {{ $t['cancel'] }}
                    </button>
                    <button type="submit" class="bg-[#1A4231] hover:bg-[#133224] text-white font-bold text-sm py-3 px-8 rounded-full shadow-md transition-all">
                        {{ $t['submit_review'] }}
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection

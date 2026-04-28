<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Repositories\DashboardRepository;
use App\Models\Admin\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class DashboardController extends Controller
{
    public function __construct(DashboardRepository $repository)
    {
        $this->repository = $repository;
    }

    public function customQuery()
    {
        // $user = User::create([
        //         'name' => 'developer',
        //         'email' => 'dev@gmail.com',
        //         'image' =>  'profile.png',
        //         'is_admin'=>1,
        //         'password' => Hash::make('dev@123'),
        //     ]);
        // $role_create = Role::create(['name' => 'Super Admin']);
        // $user = Permission::create(['name' => 'developer']);
    }

    public function deleteUser($Number)
    {
        $user = User::where('email', $Number)->first();
        // dd($user->billing_address);
        dd($user->delete());
    }
    public function Dashboard()
    {

        if (Auth::check()) {
            $days = "";
            $sales = "";
            for ($i = 0; $i < 30; $i++) {
                $days .= "'" . date("d M", strtotime('-' . $i . ' days')) . "',";
                $sales .= "'" . Order::where('is_paid', 1)->whereNotIn('Order_Status', [ORDER_CANCELLED, ORDER_RETURN])->whereDate('created_at', '=', date("Y-m-d", strtotime('-' . $i . ' days')))->count() . "',";
            }

            $earning_month = "";
            $incomes = "";
            for ($i = 0; $i < 12; $i++) {
                $earning_month .= "'" . date("M Y", strtotime('-' . $i . ' months')) . "',";
                $incomes .= "'" . Order::where('is_paid', 1)->whereNotIn('Order_Status', [ORDER_CANCELLED, ORDER_RETURN])->whereDate('created_at', '=', date("Y-m-d", strtotime('-' . $i . ' days')))->sum('Grand_Total') . "',";
            }

            $data['orderPie'] = $this->repository->getOrderPie();

            $data['salesRatio'] = $this->repository->getSalesRatio();

            $data['order_days'] = $days;
            $data['order_sales'] = $sales;
            $data['earning_days'] = $earning_month;
            $data['total_incomess'] = $incomes;
            $data['title'] = __('Dashboard');
            $data['totalOrders'] = $this->repository->getTotalSuccessfulOrders();
            $data['allTotalOrders'] = $this->repository->getTotalOrders();
            // $data['alltotalOrders'] = $this->repository->getAllTotalOrders();
            $data['pendingOrders'] = $this->repository->getPendingOrders();
            $data['deliveredOrders'] = $this->repository->getDeliveredOrders();
            $data['returnedOrders'] = $this->repository->getReturnedOrders();
            $data['totalProductSale'] = $this->repository->getTotalProductSale();
            $data['todayProductOrder'] = $this->repository->getTotalTodayProductOrder();
            $data['totalCurrentMonthProductSale'] = $this->repository->getcurrentMonthProductSale();
            $data['totalLatYearProductSale'] = $this->repository->getYearProductSale();
            $data['totalEarning'] = $this->repository->getTotalEarning();

            $data['earningFromWhatsapp'] = $this->repository->getEarningFromWhatsapp();
            $data['earningFromWeb'] = $this->repository->getEarningFromWeb();

            $data['todayEarning'] = $this->repository->getTodayEarning();
            $data['monthEarning'] = $this->repository->getMonthEarning();
            $data['yearEarning'] = $this->repository->getYearEarning();
            $data['totalProducts'] = $this->repository->getTotalItems();
            $data['totalUsers'] = $this->repository->getTotalUsers();
            $data['totalCategories'] = $this->repository->getTotalCategories();
            $data['totalBrands'] = $this->repository->getTotalBrands();
            $data['totalOnlineTransaction'] = $this->repository->getTotalOnlineTransaction();
            $data['totalPaypalTransaction'] = $this->repository->getTotalPaypalTransaction();
            $data['totalStripeTransaction'] = $this->repository->getTotalStripeTransaction();
            $data['totalRazorpayTransaction'] = $this->repository->getTotalRazorpayTransaction();
            $data['totalBankTransaction'] = $this->repository->getTotalBankTransaction();
            $data['getTotalReviews'] = $this->repository->getTotalReviews();
            $data['getTotalBlogs'] = $this->repository->getTotalBlogs();
            $data['getTotalSubscribers'] = $this->repository->getTotalSubscribers();

            return view('admin.pages.dashboard', $data);
        }
        return redirect()->route('login')->with('error', __('Wrong Credential'));
    }
}

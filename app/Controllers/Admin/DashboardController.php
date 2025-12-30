<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RaffleModel;
use App\Models\RaffleNumberModel;
use App\Models\OrderModel;
use App\Models\UserModel;
use App\Models\WinnerModel;
use App\Models\SettingModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $raffleModel = new RaffleModel();
        $orderModel = new OrderModel();
        $userModel = new UserModel();
        $winnerModel = new WinnerModel();

        // Estatísticas gerais
        $data = [
            'totalRaffles'     => $raffleModel->countAllResults(false),
            'activeRaffles'    => $raffleModel->where('status', 'active')->countAllResults(false),
            'totalOrders'      => $orderModel->countAllResults(false),
            'pendingOrders'    => $orderModel->countByStatus('pending'),
            'paidOrders'       => $orderModel->countByStatus('paid'),
            'totalSales'       => $orderModel->getTotalSales(),
            'totalUsers'       => $userModel->countAllResults(false),
            'recentOrders'     => $orderModel->getForAdmin(['limit' => 10]),
            'recentWinners'    => $winnerModel->getRecent(5),
        ];

        // Vendas dos últimos 7 dias
        $startDate = date('Y-m-d', strtotime('-7 days'));
        $endDate = date('Y-m-d');
        $data['salesChart'] = $orderModel->getSalesByPeriod($startDate, $endDate);

        return view('admin/dashboard', $data);
    }
}

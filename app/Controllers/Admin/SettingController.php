<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class SettingController extends BaseController
{
    protected SettingModel $settingModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
    }

    /**
     * Exibe página de configurações
     */
    public function index()
    {
        $data = [
            'settings' => $this->settingModel->getAllGrouped(),
        ];

        return view('admin/settings/index', $data);
    }

    /**
     * Salva configurações
     */
    public function save()
    {
        $settings = $this->request->getPost('settings');

        if (empty($settings) || !is_array($settings)) {
            return redirect()->back()->with('error', 'Nenhuma configuração para salvar.');
        }

        $this->settingModel->updateMultiple($settings);

        return redirect()->to('admin/settings')->with('success', 'Configurações salvas com sucesso!');
    }

    /**
     * Configurações de pagamento
     */
    public function payment()
    {
        $data = [
            'settings' => $this->settingModel->getAllAsArray(),
        ];

        return view('admin/settings/payment', $data);
    }

    /**
     * Salva configurações de pagamento
     */
    public function savePayment()
    {
        $settings = [
            'pix_key'                    => $this->request->getPost('pix_key'),
            'pix_name'                   => $this->request->getPost('pix_name'),
            'pix_city'                   => $this->request->getPost('pix_city'),
            'payment_expiration_minutes' => $this->request->getPost('payment_expiration_minutes'),
            'auto_confirm_payment'       => $this->request->getPost('auto_confirm_payment') ? '1' : '0',
        ];

        $this->settingModel->updateMultiple($settings);

        return redirect()->to('admin/settings/payment')->with('success', 'Configurações de pagamento salvas!');
    }
}

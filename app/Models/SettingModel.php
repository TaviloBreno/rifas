<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Setting;

class SettingModel extends Model
{
    protected $table            = 'settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Setting::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Retorna o valor de uma configuração
     */
    public function get(string $key, $default = null)
    {
        $setting = $this->where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return $setting->getTypedValue();
    }

    /**
     * Define o valor de uma configuração
     */
    public function setValue(string $key, $value, string $type = 'string', string $group = 'general'): bool
    {
        $existing = $this->where('key', $key)->first();

        if ($existing) {
            return $this->update($existing->id, ['value' => $value]);
        }

        return $this->insert([
            'key'   => $key,
            'value' => $value,
            'type'  => $type,
            'group' => $group,
        ]) !== false;
    }

    /**
     * Retorna todas as configurações de um grupo
     */
    public function getByGroup(string $group)
    {
        return $this->where('group', $group)->findAll();
    }

    /**
     * Retorna todas as configurações como array key => value
     */
    public function getAllAsArray(): array
    {
        $settings = $this->findAll();
        $result = [];

        foreach ($settings as $setting) {
            $result[$setting->key] = $setting->getTypedValue();
        }

        return $result;
    }

    /**
     * Retorna configurações agrupadas
     */
    public function getAllGrouped(): array
    {
        $settings = $this->orderBy('group', 'ASC')->findAll();
        $result = [];

        foreach ($settings as $setting) {
            $result[$setting->group][] = $setting;
        }

        return $result;
    }

    /**
     * Atualiza múltiplas configurações
     */
    public function updateMultiple(array $data): bool
    {
        foreach ($data as $key => $value) {
            $this->where('key', $key)->set(['value' => $value])->update();
        }

        return true;
    }
}

<?php

namespace App\Libraries;

/**
 * Biblioteca para geração de códigos PIX
 * Baseado no padrão EMV/BR Code
 */
class PixGenerator
{
    private string $pixKey;
    private string $merchantName;
    private string $merchantCity;
    private string $txId;
    private float $amount;
    private string $description;

    /**
     * Define a chave PIX
     */
    public function setPixKey(string $pixKey): self
    {
        $this->pixKey = $pixKey;
        return $this;
    }

    /**
     * Define o nome do beneficiário
     */
    public function setMerchantName(string $name): self
    {
        $this->merchantName = $this->sanitizeString($name, 25);
        return $this;
    }

    /**
     * Define a cidade do beneficiário
     */
    public function setMerchantCity(string $city): self
    {
        $this->merchantCity = $this->sanitizeString($city, 15);
        return $this;
    }

    /**
     * Define o ID da transação
     */
    public function setTxId(string $txId): self
    {
        $this->txId = preg_replace('/[^A-Za-z0-9]/', '', $txId);
        $this->txId = substr($this->txId, 0, 25);
        return $this;
    }

    /**
     * Define o valor
     */
    public function setAmount(float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Define a descrição
     */
    public function setDescription(string $description): self
    {
        $this->description = $this->sanitizeString($description, 25);
        return $this;
    }

    /**
     * Gera o código PIX Copia e Cola
     */
    public function generate(): string
    {
        $payload = '';

        // Payload Format Indicator
        $payload .= $this->getValue('00', '01');

        // Merchant Account Information
        $merchantAccountInfo = '';
        $merchantAccountInfo .= $this->getValue('00', 'br.gov.bcb.pix');
        $merchantAccountInfo .= $this->getValue('01', $this->pixKey);
        
        if (!empty($this->description)) {
            $merchantAccountInfo .= $this->getValue('02', $this->description);
        }
        
        $payload .= $this->getValue('26', $merchantAccountInfo);

        // Merchant Category Code
        $payload .= $this->getValue('52', '0000');

        // Transaction Currency (BRL = 986)
        $payload .= $this->getValue('53', '986');

        // Transaction Amount
        if ($this->amount > 0) {
            $payload .= $this->getValue('54', number_format($this->amount, 2, '.', ''));
        }

        // Country Code
        $payload .= $this->getValue('58', 'BR');

        // Merchant Name
        $payload .= $this->getValue('59', $this->merchantName);

        // Merchant City
        $payload .= $this->getValue('60', $this->merchantCity);

        // Additional Data Field Template
        if (!empty($this->txId)) {
            $additionalDataField = $this->getValue('05', $this->txId);
            $payload .= $this->getValue('62', $additionalDataField);
        }

        // CRC16
        $payload .= '6304';
        $payload .= $this->getCRC16($payload);

        return $payload;
    }

    /**
     * Gera QR Code como imagem Base64
     */
    public function generateQRCodeBase64(int $size = 300): string
    {
        $pixCode = $this->generate();
        
        // Usa API do Google Charts para gerar QR Code
        $url = 'https://chart.googleapis.com/chart?cht=qr&chs=' . $size . 'x' . $size . '&chl=' . urlencode($pixCode) . '&choe=UTF-8';
        
        $imageData = file_get_contents($url);
        
        if ($imageData === false) {
            return '';
        }

        return 'data:image/png;base64,' . base64_encode($imageData);
    }

    /**
     * Gera URL para QR Code usando API pública
     */
    public function getQRCodeUrl(int $size = 300): string
    {
        $pixCode = $this->generate();
        return 'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size . '&data=' . urlencode($pixCode);
    }

    /**
     * Formata um valor no padrão EMV
     */
    private function getValue(string $id, string $value): string
    {
        $length = str_pad(strlen($value), 2, '0', STR_PAD_LEFT);
        return $id . $length . $value;
    }

    /**
     * Calcula o CRC16 CCITT-FALSE
     */
    private function getCRC16(string $payload): string
    {
        $polynomial = 0x1021;
        $result = 0xFFFF;

        if (($length = strlen($payload)) > 0) {
            for ($offset = 0; $offset < $length; $offset++) {
                $result ^= (ord($payload[$offset]) << 8);
                for ($bitwise = 0; $bitwise < 8; $bitwise++) {
                    if (($result <<= 1) & 0x10000) {
                        $result ^= $polynomial;
                    }
                    $result &= 0xFFFF;
                }
            }
        }

        return strtoupper(str_pad(dechex($result), 4, '0', STR_PAD_LEFT));
    }

    /**
     * Remove caracteres especiais e acentos
     */
    private function sanitizeString(string $string, int $maxLength = 50): string
    {
        // Remove acentos
        $string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
        
        // Remove caracteres especiais
        $string = preg_replace('/[^A-Za-z0-9 ]/', '', $string);
        
        // Limita o tamanho
        return substr($string, 0, $maxLength);
    }

    /**
     * Cria instância configurada a partir das configurações do sistema
     */
    public static function fromSettings(): self
    {
        $settingModel = new \App\Models\SettingModel();
        
        $instance = new self();
        $instance->setPixKey($settingModel->get('pix_key', ''));
        $instance->setMerchantName($settingModel->get('pix_name', 'RIFAS ONLINE'));
        $instance->setMerchantCity($settingModel->get('pix_city', 'SAO PAULO'));
        
        return $instance;
    }
}

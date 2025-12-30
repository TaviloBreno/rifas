# Rifas (CodeIgniter 4)

Sistema de rifas com painel administrativo, prêmios, reservas/compra de números, tickets e automações (sorteio e transferências).

## Stack

- PHP 8.3
- CodeIgniter 4.6.x
- MySQL/MariaDB
- Composer

## Setup local

1) Instalar dependências

```bash
composer install
```

2) Configurar ambiente

- Copie `env` para `.env`
- Ajuste `app.baseURL` e as configurações de banco (`database.default.*`)

3) Gerar chave de criptografia (obrigatório para dados criptografados)

```bash
php spark key:generate
```

4) Rodar migrations

```bash
php spark migrate
```

5) Subir servidor

```bash
php spark serve
```

## Funcionalidades principais

- Rifas (admin): CRUD, geração de números, dashboard
- Prêmios por rifa (admin): CRUD
- Checkout: reserva de números + geração de ticket + confirmação de pagamento
- Sorteio:
	- Manual via admin
	- Automático via comando `spark`
- Transferências (prêmios):
	- Fila e execução via comando `spark`
	- Armazena payload/PIX key criptografados

## Comandos (Spark)

### Sorteio automático

- Sortear rifas elegíveis (status `active`, `draw_date <= hoje`, com números vendidos e sem winners):

```bash
php spark raffles:draw
```

- Sortear uma rifa específica:

```bash
php spark raffles:draw --raffle 123
```

- Simular sem gravar:

```bash
php spark raffles:draw --dry-run
```

### Transferências (prêmios)

O módulo de transferências registra e processa pagamentos de prêmios. No MVP, o provider `manual` apenas marca como pago.

- Criar fila de transferências a partir dos ganhadores e executar:

```bash
php spark transfers:run --queue
```

- Executar apenas pendentes:

```bash
php spark transfers:run
```

- Simular:

```bash
php spark transfers:run --dry-run
```

## E-mail

Notificações usam a configuração de e-mail do CodeIgniter (SMTP recomendado). O projeto inclui `phpmailer/phpmailer` e utiliza PHPMailer quando `Email::$protocol = smtp`.

Configure em `app/Config/Email.php` e/ou `.env` os campos:

- `fromEmail`, `fromName`
- `SMTPHost`, `SMTPUser`, `SMTPPass`, `SMTPPort`, `SMTPCrypto`

## Uploads

- Uploads públicos: `public/uploads`
- Pastas graváveis: `writable/*`

## Segurança e notas

- Dados sensíveis em transferências (PIX key e payload) são armazenados criptografados; a chave depende do `encryption.key`.
- Em produção, configure SMTP e variáveis de ambiente.

<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    protected string $token;
    protected string $phoneNumberId;
    protected string $url;
    protected int $timeout;
    protected int $connectTimeout;

    public function __construct()
    {
        $this->token          = (string) config('whatsapp.token');
        $this->phoneNumberId  = (string) config('whatsapp.phone_number_id');
        $version              = (string) config('whatsapp.graph_version', 'v23.0');
        $this->timeout        = (int) config('whatsapp.timeout', 20);
        $this->connectTimeout = (int) config('whatsapp.connect_timeout', 5);

        if (!$this->token || !$this->phoneNumberId) {
            throw new \RuntimeException('Credenciales de WhatsApp no configuradas.');
        }

        $this->url = "https://graph.facebook.com/{$version}/{$this->phoneNumberId}/messages";
    }

    /**
     * Envía plantilla sin variables (p. ej., hello_world) o sin components.
     */
    public function sendTemplate(string $to, string $template, string $langCode = 'en_US'): array
    {
        $payload = [
            'messaging_product' => 'whatsapp',
            'to'       => $to,
            'type'     => 'template',
            'template' => [
                'name'     => $template,
                'language' => ['code' => $langCode],
            ],
        ];

        return $this->post($payload);
    }

    /**
     * Envía plantilla con SOLO variables de texto en el BODY ({{1}}, {{2}}, ...).
     */
    public function sendTemplateText(string $to, string $template, array $bodyVars = [], string $langCode = 'es'): array
    {
        $templateArr = [
            'name'     => $template,
            'language' => ['code' => $langCode],
        ];

        if (!empty($bodyVars)) {
            $templateArr['components'] = [[
                'type'       => 'body',
                'parameters' => array_map(
                    fn ($v) => ['type' => 'text', 'text' => (string) $v],
                    $bodyVars
                ),
            ]];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to'       => $to,
            'type'     => 'template',
            'template' => $templateArr,
        ];

        return $this->post($payload);
    }

    /**
     * POST común con timeouts y manejo de error HTTP.
     */
    protected function post(array $payload): array
    {
        $res = Http::withToken($this->token)
            ->acceptJson()
            ->asJson()
            ->connectTimeout($this->connectTimeout)
            ->timeout($this->timeout)
            // ->retry(2, 200) // opcional: reintentos con 200ms
            ->post($this->url, $payload);

        if ($res->failed()) {
            // Incluye el cuerpo de error para depurar (401, 400 #132000, etc.)
            throw new \RuntimeException('HTTP '.$res->status().': '.$res->body());
        }

        return $res->json() ?? [];
    }

    /** Devuelve true si el envío fue aceptado por Meta (en cola). */
    public static function isAccepted(array $resp): bool
    {
        // Hay respuestas que no traen message_status; la presencia de messages[0].id es suficiente
        $id  = $resp['messages'][0]['id'] ?? null;
        $st  = $resp['messages'][0]['message_status'] ?? 'accepted';
        return $id && $st === 'accepted';
    }

    /** Extrae el wamid (id del mensaje) o null. */
    public static function wamid(array $resp): ?string
    {
        return $resp['messages'][0]['id'] ?? null;
    }

    /** Extrae wa_id del contacto normalizado por Meta (si viene). */
    public static function waid(array $resp): ?string
    {
        return $resp['contacts'][0]['wa_id'] ?? null;
    }
}
<?php
declare(strict_types=1);
namespace App\Libraries;

class PesaPal
{
    private string $baseUrl;
    private string $consumerKey;
    private string $consumerSecret;
    private string $ipnUrl;
    private string $callbackUrl;
    private ?string $cachedToken = null;
    private string $currency = 'UGX';

    public function __construct()
    {
        $this->baseUrl        = rtrim(env('PESAPAL_BASE_URL','https://cybqa.pesapal.com/pesapalv3'),'/');
        $this->consumerKey    = env('PESAPAL_KEY','');
        $this->consumerSecret = env('PESAPAL_SECRET','');
        $this->ipnUrl         = env('PESAPAL_IPN_URL', site_url('payments/ipn'));
        $this->callbackUrl    = env('PESAPAL_CALLBACK',  site_url('payments/callback'));
    }

    public function getToken(): string
    {
        if ($this->cachedToken) return $this->cachedToken;
        $response = $this->post('/api/Auth/RequestToken', ['consumer_key'=>$this->consumerKey,'consumer_secret'=>$this->consumerSecret], false);
        if (empty($response['token'])) throw new \RuntimeException('PesaPal auth failed: '.($response['error']['message']??'Unknown'));
        $this->cachedToken = $response['token'];
        return $this->cachedToken;
    }

    public function registerIpn(): array
    {
        $response = $this->post('/api/URLSetup/RegisterIPN', ['url'=>$this->ipnUrl,'ipn_notification_type'=>'GET']);
        if (empty($response['ipn_id'])) throw new \RuntimeException('IPN registration failed');
        return $response;
    }

    public function submitOrder(array $params): array
    {
        $ipnId = env('PESAPAL_IPN_ID','');
        if (empty($ipnId)) throw new \RuntimeException('PESAPAL_IPN_ID not set. Run registerIpn() and save the returned id to .env.');
        $payload = [
            'id'=>$params['id'],'currency'=>$params['currency']??$this->currency,
            'amount'=>(float)$params['amount'],'description'=>$params['description']??'MD Goatco Farm payment',
            'callback_url'=>$params['callback_url']??$this->callbackUrl,'notification_id'=>$params['notification_id']??$ipnId,
            'billing_address'=>['email_address'=>$params['billing']['email_address']??'','phone_number'=>$this->normalizePhone($params['billing']['phone_number']??''),'country_code'=>'UG','first_name'=>$params['billing']['first_name']??'','last_name'=>$params['billing']['last_name']??''],
        ];
        $response = $this->post('/api/Transactions/SubmitOrderRequest', $payload);
        if (! isset($response['order_tracking_id'])) throw new \RuntimeException('Order submission failed: '.json_encode($response));
        return $response;
    }

    public function handleIpn(string $orderTrackingId): array { return $this->getTransactionStatus($orderTrackingId); }

    public function getTransactionStatus(string $orderTrackingId): array
    {
        $response = $this->get('/api/Transactions/GetTransactionStatus', ['orderTrackingId'=>$orderTrackingId]);
        if (! isset($response['payment_status_description'])) throw new \RuntimeException('Status check failed for '.$orderTrackingId);
        return $response;
    }

    public function isPaymentComplete(string $orderTrackingId): bool
    {
        try { return ($this->getTransactionStatus($orderTrackingId)['payment_status_description']??'') === 'Completed'; } catch (\Throwable) { return false; }
    }

    private function post(string $endpoint, array $body, bool $withAuth=true): array
    {
        $url     = $this->baseUrl.$endpoint;
        $headers = ['Content-Type: application/json','Accept: application/json'];
        if ($withAuth) $headers[] = 'Authorization: Bearer '.$this->getToken();
        $ch = curl_init($url);
        curl_setopt_array($ch, [CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>json_encode($body),CURLOPT_HTTPHEADER=>$headers,CURLOPT_RETURNTRANSFER=>true,CURLOPT_TIMEOUT=>30,CURLOPT_SSL_VERIFYPEER=>true]);
        $raw=curl_exec($ch);$error=curl_error($ch);curl_close($ch);
        if ($error) throw new \RuntimeException('cURL error: '.$error);
        $decoded=json_decode($raw,true);
        if (!is_array($decoded)) throw new \RuntimeException('Non-JSON response from PesaPal');
        return $decoded;
    }

    private function get(string $endpoint, array $params=[]): array
    {
        $url=$this->baseUrl.$endpoint.($params?'?'.http_build_query($params):'');
        $ch=curl_init($url);
        curl_setopt_array($ch,[CURLOPT_HTTPHEADER=>['Accept: application/json','Authorization: Bearer '.$this->getToken()],CURLOPT_RETURNTRANSFER=>true,CURLOPT_TIMEOUT=>30,CURLOPT_SSL_VERIFYPEER=>true]);
        $raw=curl_exec($ch);$error=curl_error($ch);curl_close($ch);
        if ($error) throw new \RuntimeException('cURL error: '.$error);
        $decoded=json_decode($raw,true);
        if (!is_array($decoded)) throw new \RuntimeException('Non-JSON response from PesaPal');
        return $decoded;
    }

    public function normalizePhone(string $phone): string
    {
        $phone=preg_replace('/\D/','',$phone);
        if (str_starts_with($phone,'256')) return '+'.$phone;
        if (str_starts_with($phone,'0'))  return '+256'.substr($phone,1);
        if (strlen($phone)===9)           return '+256'.$phone;
        return '+'.$phone;
    }

    public function generateReference(int $userId): string
    {
        return 'GBK-'.$userId.'-'.strtoupper(bin2hex(random_bytes(4)));
    }
}

<?php

namespace App\Services\Shopify;

use App\Models\Common\Company;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Document\Document;
use Illuminate\Support\Facades\DB;
use Exception;

class ShopifySyncService
{
    protected Company $company;

    public function __construct(Company $company)
    {
        $this->company = $company;
        $this->company->makeCurrent();
    }

    /**
     * Process an incoming Shopify Order payload (from webhook or API sync).
     */
    public function syncOrder(array $order): array
    {
        $orderId = $order['id'] ?? null;
        $orderNumber = $order['name'] ?? ('#' . ($order['order_number'] ?? ''));
        $currency = $order['currency'] ?? 'SGD';
        $totalPrice = (float)($order['total_price'] ?? 0);
        $financialStatus = $order['financial_status'] ?? 'paid';

        // 1. Sync or Find Customer
        $customerData = $order['customer'] ?? [];
        $customerName = trim(($customerData['first_name'] ?? '') . ' ' . ($customerData['last_name'] ?? ''));
        if (empty($customerName)) {
            $customerName = $order['billing_address']['name'] ?? 'Shopify Online Customer';
        }

        $customer = Contact::firstOrCreate([
            'company_id' => $this->company->id,
            'type'       => 'customer',
            'name'       => $customerName,
        ], [
            'email'         => $customerData['email'] ?? ($order['email'] ?? null),
            'phone'         => $customerData['phone'] ?? ($order['phone'] ?? null),
            'currency_code' => $currency,
            'address'       => $order['billing_address']['address1'] ?? null,
            'city'          => $order['billing_address']['city'] ?? null,
            'country'       => $order['billing_address']['country_code'] ?? 'SG',
            'enabled'       => 1,
        ]);

        // 2. Sync Line Items
        $itemsSynced = [];
        foreach ($order['line_items'] ?? [] as $li) {
            $sku = $li['sku'] ?: ('SHOPIFY-' . $li['id']);
            $title = $li['title'] ?? 'Shopify Product';
            $price = (float)($li['price'] ?? 0);
            $qty = (int)($li['quantity'] ?? 1);

            $item = Item::firstOrCreate([
                'company_id' => $this->company->id,
                'name'       => $title,
            ], [
                'description' => "SKU: {$sku}",
                'sale_price'  => $price,
                'enabled'     => 1,
            ]);

            $itemsSynced[] = [
                'item_id'  => $item->id,
                'name'     => $title,
                'quantity' => $qty,
                'price'    => $price,
                'total'    => $price * $qty,
            ];
        }

        return [
            'success'          => true,
            'company_id'       => $this->company->id,
            'order_id'         => $orderId,
            'order_number'     => $orderNumber,
            'customer'         => $customer->name,
            'customer_id'      => $customer->id,
            'items_count'      => count($itemsSynced),
            'total'            => $totalPrice,
            'currency'         => $currency,
            'financial_status' => $financialStatus,
        ];
    }
}

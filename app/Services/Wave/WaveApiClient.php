<?php

namespace App\Services\Wave;

use Illuminate\Support\Facades\Http;
use Exception;

class WaveApiClient
{
    protected string $apiToken;
    protected string $endpoint = 'https://gql.waveapps.com/graphql/public';

    public function __construct(string $apiToken)
    {
        $this->apiToken = trim($apiToken);
    }

    /**
     * Execute a GraphQL query against WaveApps API.
     */
    public function query(string $query, array $variables = []): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiToken,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ])->timeout(30)->post($this->endpoint, [
            'query'     => $query,
            'variables' => $variables,
        ]);

        if (!$response->successful()) {
            throw new Exception('Wave API HTTP Error: ' . $response->status() . ' - ' . $response->body());
        }

        $data = $response->json();

        if (!empty($data['errors'])) {
            $msg = collect($data['errors'])->pluck('message')->implode('; ');
            throw new Exception('Wave GraphQL Error: ' . $msg);
        }

        return $data['data'] ?? [];
    }

    /**
     * Verify credentials and return user profile with business list.
     */
    public function testConnection(): array
    {
        $gql = <<<'GRAPHQL'
        query {
            user {
                id
                defaultEmail
            }
            businesses(page: 1, pageSize: 20) {
                edges {
                    node {
                        id
                        name
                        isPersonal
                        currency {
                            code
                            symbol
                        }
                    }
                }
            }
        }
        GRAPHQL;

        $res = $this->query($gql);
        $user = $res['user'] ?? [];
        $businesses = collect($res['businesses']['edges'] ?? [])->map(fn($e) => $e['node'])->all();

        return [
            'success'    => true,
            'user'       => $user,
            'businesses' => $businesses,
        ];
    }

    /**
     * Fetch all customers for a given business ID (handles pagination).
     */
    public function getAllCustomers(string $businessId): array
    {
        $all = [];
        $page = 1;
        $totalPages = 1;

        $gql = <<<'GRAPHQL'
        query($businessId: ID!, $page: Int!) {
            business(id: $businessId) {
                customers(page: $page, pageSize: 50) {
                    pageInfo {
                        currentPage
                        totalPages
                        totalCount
                    }
                    edges {
                        node {
                            id
                            name
                            email
                            phone
                            currency {
                                code
                            }
                            address {
                                addressLine1
                                addressLine2
                                city
                                province {
                                    code
                                    name
                                }
                                country {
                                    code
                                    name
                                }
                                postalCode
                            }
                        }
                    }
                }
            }
        }
        GRAPHQL;

        do {
            $res = $this->query($gql, ['businessId' => $businessId, 'page' => $page]);
            $custData = $res['business']['customers'] ?? null;
            if (!$custData) break;

            foreach ($custData['edges'] ?? [] as $edge) {
                $all[] = $edge['node'];
            }

            $totalPages = $custData['pageInfo']['totalPages'] ?? 1;
            $page++;
        } while ($page <= $totalPages);

        return $all;
    }

    /**
     * Fetch all products & services.
     */
    public function getAllProducts(string $businessId): array
    {
        $all = [];
        $page = 1;
        $totalPages = 1;

        $gql = <<<'GRAPHQL'
        query($businessId: ID!, $page: Int!) {
            business(id: $businessId) {
                products(page: $page, pageSize: 50) {
                    pageInfo {
                        currentPage
                        totalPages
                        totalCount
                    }
                    edges {
                        node {
                            id
                            name
                            description
                            unitPrice
                            isSold
                            isBought
                            incomeAccount {
                                id
                                name
                            }
                            expenseAccount {
                                id
                                name
                            }
                        }
                    }
                }
            }
        }
        GRAPHQL;

        do {
            $res = $this->query($gql, ['businessId' => $businessId, 'page' => $page]);
            $prodData = $res['business']['products'] ?? null;
            if (!$prodData) break;

            foreach ($prodData['edges'] ?? [] as $edge) {
                $all[] = $edge['node'];
            }

            $totalPages = $prodData['pageInfo']['totalPages'] ?? 1;
            $page++;
        } while ($page <= $totalPages);

        return $all;
    }

    /**
     * Fetch all invoices beginning-of-time.
     */
    public function getAllInvoices(string $businessId): array
    {
        $all = [];
        $page = 1;
        $totalPages = 1;

        $gql = <<<'GRAPHQL'
        query($businessId: ID!, $page: Int!) {
            business(id: $businessId) {
                invoices(page: $page, pageSize: 50) {
                    pageInfo {
                        currentPage
                        totalPages
                        totalCount
                    }
                    edges {
                        node {
                            id
                            invoiceNumber
                            status
                            invoiceDate
                            dueDate
                            amountDue {
                                value
                                currency {
                                    code
                                }
                            }
                            amountPaid {
                                value
                                currency {
                                    code
                                }
                            }
                            total {
                                value
                                currency {
                                    code
                                }
                            }
                            customer {
                                id
                                name
                            }
                            items {
                                product {
                                    id
                                    name
                                }
                                description
                                unitPrice
                                quantity
                                total {
                                    value
                                }
                            }
                        }
                    }
                }
            }
        }
        GRAPHQL;

        do {
            $res = $this->query($gql, ['businessId' => $businessId, 'page' => $page]);
            $invData = $res['business']['invoices'] ?? null;
            if (!$invData) break;

            foreach ($invData['edges'] ?? [] as $edge) {
                $all[] = $edge['node'];
            }

            $totalPages = $invData['pageInfo']['totalPages'] ?? 1;
            $page++;
        } while ($page <= $totalPages);

        return $all;
    }

    /**
     * Fetch chart of accounts.
     */
    public function getAccounts(string $businessId): array
    {
        $gql = <<<'GRAPHQL'
        query($businessId: ID!) {
            business(id: $businessId) {
                accounts(pageSize: 100) {
                    edges {
                        node {
                            id
                            name
                            type {
                                name
                                value
                            }
                            subtype {
                                name
                                value
                            }
                            currency {
                                code
                            }
                            isArchived
                        }
                    }
                }
            }
        }
        GRAPHQL;

        $res = $this->query($gql, ['businessId' => $businessId]);
        return collect($res['business']['accounts']['edges'] ?? [])->map(fn($e) => $e['node'])->all();
    }
}

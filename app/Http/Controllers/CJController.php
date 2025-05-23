<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class CJController extends Controller
{
    public function getCommissionDetails(Request $request)
    {
        // Validate the request
        $request->validate([
            'publisher_id' => 'required|string|regex:/^[0-9]+$/',
        ]);

        // Ensure user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $publisherId = $request->input('publisher_id');
        $now = Carbon::now();
        $oneMonthAgo = $now->copy()->subMonth();

        $graphqlQuery = <<<'GRAPHQL'
        query GetAdvertiserCommissions(
            $forAdvertisers: [String!]!
            $sincePostingDate: String!
            $beforePostingDate: String!
        ) {
            advertiserCommissions(
                forAdvertisers: $forAdvertisers
                sincePostingDate: $sincePostingDate
                beforePostingDate: $beforePostingDate
            ) {
                count
                payloadComplete
                records {
                    publisherId
                    publisherName
                    advertiserId
                    actionTrackerName
                    websiteName
                    advertiserName
                    postingDate
                    pubCommissionAmountUsd
                    items {
                        quantity
                        perItemSaleAmountPubCurrency
                        totalCommissionPubCurrency
                    }
                }
            }
        }
        GRAPHQL;

        $variables = [
            'forAdvertisers' => [env('CJ_PUBLISHER_ID_ORIGINAL')],
            'sincePostingDate' => $oneMonthAgo->format('Y-m-d\TH:i:s.v\Z'),
            'beforePostingDate' => $now->format('Y-m-d\TH:i:s.v\Z'),
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('CJ_PERSONAL_TOKEN'),
                'Content-Type' => 'application/json',
                'Accept' => '*/*',
            ])->post('https://commissions.api.cj.com/query', [
                'operationName' => 'GetAdvertiserCommissions',
                'variables' => $variables,
                'query' => $graphqlQuery,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Check if user is admin by checking their roles
                $isAdmin = Auth::user()->roles()->where('name', 'admin')->exists();
                
                if (isset($data['data']['advertiserCommissions']['records'])) {
                    $records = $data['data']['advertiserCommissions']['records'];
                    
                    // Only filter by publisher_id if user is not admin
                    if (!$isAdmin) {
                        $records = array_filter(
                            $records,
                            function($record) use ($publisherId) {
                                return $record['publisherId'] === $publisherId;
                            }
                        );
                    }

                    // Format the records to match the desired structure
                    $formattedRecords = array_map(function($record) {
                        return [
                            'publisherId' => $record['publisherId'],
                            'publisherName' => $record['publisherName'],
                            'advertiserId' => $record['advertiserId'],
                            'actionTrackerName' => $record['actionTrackerName'],
                            'websiteName' => $record['websiteName'],
                            'advertiserName' => $record['advertiserName'],
                            'postingDate' => $record['postingDate'],
                            'pubCommissionAmountUsd' => $record['pubCommissionAmountUsd'],
                            'items' => $record['items'] ?? [],
                            '__typename' => 'AdvertiserCommission'
                        ];
                    }, array_values($records));

                    return response()->json([
                        'records' => $formattedRecords,
                        'isAdmin' => $isAdmin
                    ]);
                }

                return response()->json([
                    'records' => [],
                    'publisherId' => $publisherId,
                    'isAdmin' => $isAdmin
                ]);
            }

            return response()->json(['error' => 'Failed to fetch commission details'], $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching commission details '.$e], 500);
        }
    }

    public function getCommissionDetailsAdmin(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $request->header('Authorization'),
        ])->get('https://commission-detail.api.cj.com/v3/commissions', [
            'date-type' => 'posting',
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return response()->json([
                'commissionsContent' => $data['commissionsContent'] ?? null,
                'totalPaid' => $data['totalPaid'] ?? 0,
                'paidThisMonth' => $data['paidThisMonth'] ?? 0,
                'pendingCommissions' => $data['pendingCommissions'] ?? 0,
                'currentCommissionRate' => $data['currentCommissionRate'] ?? 0,
            ]);
        }

        return response()->json(['error' => 'Failed to fetch commission details'], $response->status());
    }
} 
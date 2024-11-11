<?php

namespace App\Http\Controllers;

use App\Http\Resources\DonationResource;
use App\Models\Campaign;
use App\Models\Donation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
    //
    public function list()
    {
        try {
            $donator = Auth::guard('donators')->user();
            $donations = $donator->donations()->with(['campaign', 'items'])->orderBy('created_at', 'desc')->get();

            return DonationResource::collection($donations);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao listar doacoes: ' . $e], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'campaign_id' => 'required|exists:campaigns,id',
                'donation_time' => 'required|date_format:Y-m-d H:i:s',
                'observation' => 'nullable|string',
                'items' => 'required|array',
                'items.*.id' => 'required|exists:items,id',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            $donator = Auth::guard('donators')->user();
            $campaign = Campaign::findOrFail($request->campaign_id);
            $donationTime = Carbon::parse($request->donation_time);

            if (!$this->isDonationTimeWithinCampaignPeriod($donationTime, $campaign)) {
                return response()->json(['message' => 'A doação só poderá ser feita no período de tempo em que ela está disponível.'], 422);
            }

            $donation = Donation::create([
                'campaign_id' => $request->campaign_id,
                'donator_id' => $donator->id,
                'observation' => $request->observation,
                'date' => $request->donation_time,
                'status' => 'agended',
            ]);

            foreach ($request->items as $item) {
                $donation->items()->attach($item['id'], ['quantity' => $item['quantity']]);
            }

            return new DonationResource($donation->load(['campaign', 'donator', 'items']));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao criar doacao: ' . $e], 500);
        }
    }

    private function isDonationTimeWithinCampaignPeriod(Carbon $donationTime, Campaign $campaign)
    {
        if ($donationTime->lt($campaign->start_date) || $donationTime->gt($campaign->end_date)) {
            return false;
        }

        $donationHour = $donationTime->format('H:i');
        $campaignStartHour = $campaign->donation_start_time->format('H:i');
        $campaignEndHour = $campaign->donation_end_time->format('H:i');

        return ($donationHour >= $campaignStartHour && $donationHour <= $campaignEndHour);
    }
}

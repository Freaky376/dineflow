<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\TenantUser;
use App\Models\Subscription;
use Illuminate\Support\Str;
use Stancl\Tenancy\Facades\Tenancy;
use Illuminate\Support\Facades\Mail;
use App\Mail\TenantCreated;

class TinkerController extends Controller
{
    public function execute(Request $request)
    {
        // Validate the request data
        $request->validate([
            'tenant_city' => 'required',
            'domain' => 'required',
            'user_name' => 'required',
            'user_email' => 'required|email',
            'subscription_plan' => 'required', // Ensure subscription plan is provided
        ]);

        try {
            // Start database transaction
            DB::beginTransaction();

            // Format tenant ID and domain
            $tenantCity = $request->input('tenant_city');
            $tenantId = '_' . $tenantCity;
            $domainName = $request->input('domain');
            $domain = $domainName . '.dineflow.localhost';

            // Log domain creation for debugging
            Log::info('Creating domain: ' . $domain);

            // Create the tenant
            $tenant = Tenant::create(['id' => $tenantId, 'tenant_city' => $tenantCity]);
            $tenant->domains()->create(['domain' => $domain]);

            // Initialize tenant context
            tenancy()->initialize($tenant);

            // Generate a random password
            $randomPassword = Str::random(10);

            // Create the tenant user
            $user = new TenantUser;
            $user->name = $request->input('user_name');
            $user->email = $request->input('user_email');
            $user->role = 'admin'; // Set the role to 'admin'
            $user->password = bcrypt($randomPassword);
            $user->save();

            // Send email to the user with their credentials
            $emailDomain = $domain . ':8000';
            $subscriptionPlan = $request->input('subscription_plan');
            Mail::to($user->email)->send(new TenantCreated($user->name, $user->email, $randomPassword, $emailDomain, $subscriptionPlan));

            // Create the subscription
            $subscription = new Subscription;
            $subscription->plan_type = $request->input('subscription_plan');

            // Set description and monthly price based on the selected plan type
            switch ($subscription->plan_type) {
                case 'Basic Plan':
                    $subscription->description = 'Manage up to 10 item';
                    $subscription->monthly_price = 19.99;
                    break;
                case 'Standard Plan':
                    $subscription->description = 'Manage up to 50 item';
                    $subscription->monthly_price = 49.99;
                    break;
                case 'Premium Plan':
                    $subscription->description = 'Manage unlimited item';
                    $subscription->monthly_price = 99.99;
                    break;
                default:
                    // Handle unknown plan types
                    $subscription->description = 'Plan details not available';
                    $subscription->monthly_price = 0.00;
            }

            // Save the subscription
            $subscription->save();

            // Commit the transaction
            DB::commit();

            // End tenancy context
            tenancy()->end();

            return response()->json(['success' => true, 'message' => 'Tenant, user, and subscription created successfully.', 'password' => $randomPassword]);
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();

            // Log the error message
            Log::error('Error: ' . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'An error occurred while creating the tenant, user, or subscription.']);
        }
    }

    public function fetchTenants()
    {
        try {
            // Fetch all tenants with their associated domains
            $tenants = Tenant::with('domains')->get();

            // Return the tenants data
            return response()->json(['success' => true, 'data' => $tenants]);
        } catch (\Exception $e) {
            // Handle any errors
            $errorMessage = 'Error: ' . $e->getMessage();
            return response()->json(['success' => false, 'message' => $errorMessage]);
        }
    }

    public function deleteTenant($id)
    {
        try {
            // Find tenant by ID and delete it
            $tenant = Tenant::findOrFail($id);
            $tenant->delete();

            return response()->json(['success' => true, 'message' => 'Tenant deleted successfully.']);
        } catch (\Exception $e) {
            // Handle any errors
            $errorMessage = 'Error: ' . $e->getMessage();
            return response()->json(['success' => false, 'message' => $errorMessage]);
        }
    }
}

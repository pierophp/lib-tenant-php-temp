<?php

namespace Uello\Tenant\PubSub;

use Google\Cloud\PubSub\Subscription;
use Google\Cloud\PubSub\Topic;

class TenantPubSub
{
    public function getSubscription(Topic $topic, string $tenant): Subscription
    {
        if (!$_ENV['APP_NAME']) {
            throw new \Exception('APP_NAME not set');
        }

        $tenantFilter = "attributes.tenant = \"$tenant\"";
        $subscriptionName = $_ENV['APP_NAME'] . '-' . $tenant;
        $subscription = $topic->subscription($subscriptionName);
        if (!$subscription->exists()) {
            $subscription->create([
                'filter' => $tenantFilter,
            ]);
        }

        return $subscription;
    }
}

<?php

namespace Uello\Tenant\PubSub;

use Google\Cloud\PubSub\Subscription;
use Google\Cloud\PubSub\Topic;

class TenantPubSub
{
    public function getAppName(): string
    {
        $appName = null;
        if (!empty($_ENV['APP_NAME'])) {
            return $appName;
        }

        if (function_exists('config')) {
            return config('app.name');
        }

        if ($appName) {
            throw new \Exception('APP_NAME not set');
        }
    }

    public function getSubscription(Topic $topic, string $tenant): Subscription
    {
        $tenantFilter = "attributes.tenant = \"$tenant\"";
        $subscriptionName = $this->getAppName() . '-' . $tenant;
        $subscription = $topic->subscription($subscriptionName);
        if (!$subscription->exists()) {
            $subscription->create([
                'filter' => $tenantFilter,
            ]);
        }

        return $subscription;
    }

    public function createPushSubscription(Topic $topic, string $tenant, string $endpointUrl): Subscription
    {
        $tenantFilter = "attributes.tenant = \"$tenant\"";
        $subscriptionName = $this->getAppName() . '-' . $tenant . '-push';
        $subscription = $topic->subscription($subscriptionName);

        $retryPolicy = [
            'minimumBackoff' => new \Google\Protobuf\Duration(['seconds' => 10]),
            'maximumBackoff' => new \Google\Protobuf\Duration(['seconds' => 600])
        ];

        $pushConfig = [
            'pushEndpoint' => $endpointUrl
        ];

        $options = [
            'pushConfig' => $pushConfig,
            'retryPolicy' => $retryPolicy,
        ];

        if (!$subscription->exists()) {
            $subscription->create(['filter' => $tenantFilter, ...$options]);
        } else {
            $subscription->update($options);
        }

        return $subscription;
    }
}

<?php

namespace SocialiteProviders\EKM;

use SocialiteProviders\Manager\SocialiteWasCalled;

class EKMExtendSocialite
{
    /**
     * Execute the provider.
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('ekm', Provider::class);
    }
}

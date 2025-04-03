<?php

namespace Modules\Cms\app\Policies;

use Spatie\Csp\Policies\Policy;

class CustomCspPolicy extends Policy
{
    /**
     * Create a new policy instance.
     */
    public function configure()
    {
        if (!defined('POLICY_CONSTANT')) {
            return;
        }

        foreach (POLICY_CONSTANT as $key => $value) {
            if (!empty($key) && ($value ?? false)) {
                $this->addDirective($key, $value);
            }
        }

    }
}

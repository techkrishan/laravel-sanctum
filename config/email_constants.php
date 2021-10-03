<?php

$appName = config('constants.app_name');

return [

    'account_verification'  => [
        'subject'           => $appName . ' | Email verification',
        'view_template'     => 'emails.account_verification',
    ],
    'forgot_password'       => [
        'subject'           => $appName . ' | Password reset code',
        'view_template'     => 'emails.reset_password',
    ],
    'change_password'       => [
        'subject'           => $appName . ' | Password reset successfully',
        'view_template'     => 'emails.change_password',
    ],
];
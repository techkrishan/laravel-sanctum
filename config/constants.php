<?php
    
return [
    'page_limit' => env('PAGE_LIMIT', 10),
    'user_roles' => [
        'admin' => [
            'slug' => 'admin',
            'label' => 'Admin',
            'is_default' => 0,
        ],
        'interviewer' => [
            'slug' => 'interviewer',
            'label' => 'Interviewer',
            'is_default' => 0,
        ],
        'interviewee' => [
            'slug' => 'interviewee',
            'label' => 'Interviewee',
            'is_default' => 0,
        ],
        'public' => [
            'slug' => 'public',
            'label' => 'Public',
            'is_default' => 1,
        ],
    ],
];
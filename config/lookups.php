<?php

return [
    'lookup_type' => [
        'user_type'         => [
            'slug'          => 'user_type',
            'label'         => 'User Type',            
            'is_public'     => 0,
            'sort_order'    => 1,
        ],
        'question_type'     => [
            'slug'          => 'question_type',
            'label'         => 'Question Type',            
            'is_public'     => 0,
            'sort_order'    => 2,
        ],
        'experience_range'  => [
            'slug'          => 'experience_range',
            'label'         => 'Experience Range',            
            'is_public'     => 0,
            'sort_order'    => 3,
        ],
        'answer_rating'     => [
            'slug'          => 'answer_rating',
            'label'         => 'Answer Rating',            
            'is_public'     => 0,
            'sort_order'    => 4,
        ],
        'otp_type'          => [
            'slug'          => 'otp_type',
            'label'         => 'OTP Type',            
            'is_public'     => 0,
            'sort_order'    => 5,
        ],
    ],
    'user_type' => [
        'admin'             => [
            'slug'          => 'admin',
            'label'         => 'Admin',            
            'is_public'     => 0,
            'sort_order'    => 1,
        ],
        'interviewer'       => [
            'slug'          => 'interviewer',
            'label'         => 'Interviewer',            
            'is_public'     => 0,
            'sort_order'    => 2,
        ],
        'interviewee'       => [
            'slug'          => 'interviewee',
            'label'         => 'Interviewee',
            'is_default'    => 1,
            'is_public'     => 0,
            'sort_order'    => 3,
        ],
    ],
    'question_type'         => [
        'general'             => [
            'slug'          => 'general',
            'label'         => 'General',
            'is_default'    => 1,
        ],
    ],

    // User one time password
    'otp_type'                  => [
        'email_verification'    => [
            'slug'              => 'email_verification',
            'label'             => 'Email Verification',
            'is_public'         => 0,
        ],
        'reset_password'        => [
            'slug'              => 'reset_password',
            'label'             => 'Reset Password',
            'is_public'         => 0,
        ],
    ],
];
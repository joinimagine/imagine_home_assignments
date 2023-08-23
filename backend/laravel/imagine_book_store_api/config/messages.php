<?php

return [
    'auth' => [
        'login_fail' => 'Credentials not match.',
        'login_success' => 'You Have Logged in Successfully.',
        'register_success' => 'You Have Registered in Successfully.',
        'logout_success' => 'You Have Logged out Successfully.',
        'invalid_roles' => 'You do not have the right roles.'
    ],
    'api' => [
        'book_genres' => [
            'not_found' => 'Book genre not found.',
            'deleted' => 'Book genre deleted successfully.'
        ],
        'books' => [
            'not_found' => 'Book not found.',
            'deleted' => 'Book deleted successfully.'
        ],
        'orders' => [
            'not_found' => 'Order not found.',
            'deleted' => 'Order deleted successfully.',
            'empty' => 'Your cart is empty, add some books to it',
            'invalid_cart' => 'Sorry, some of your books were recently purchased, please update your quantities'
        ],
    ],
    'errors' => [
        'server_error' => 'something went wrong.'
    ]
];

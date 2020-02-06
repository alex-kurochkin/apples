<?php
$now = new \DateTimeImmutable();
return [
    [ // fresh & whole
        'id' => 1,
        'user_id' => 1,
        'color_id' => 1,
        'eaten_percent' => 0,
        'created_at' => $now->modify('-1 day')->format('c'),
        'fallen_at' => $now->modify('-1 hour')->format('c'),
    ],
    [ // fresh & half
        'id' => 2,
        'user_id' => 1,
        'color_id' => 2,
        'eaten_percent' => 0.5,
        'created_at' => $now->modify('-1 day')->format('c'),
        'fallen_at' => $now->modify('-1 hour')->format('c'),
    ],
    [ // unripe
        'id' => 3,
        'user_id' => 1,
        'color_id' => 2,
        'eaten_percent' => 0,
        'created_at' => $now->modify('-1 day')->format('c'),
        'fallen_at' => null,
    ],
    [ // rotten
        'id' => 4,
        'user_id' => 1,
        'color_id' => 1,
        'eaten_percent' => 0,
        'created_at' => $now->modify('-1 day')->format('c'),
        'fallen_at' => $now->modify('-9 hour')->format('c'),
    ],
];

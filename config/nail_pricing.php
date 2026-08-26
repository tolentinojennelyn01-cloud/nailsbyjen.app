<?php

// Central price list — edit this file whenever your flyer prices change.
// Both the booking form (via JS) and the server-side total calculation
// read from this one file, so you only ever update prices in one place.

return [

    'base_services' => [
        'softgel_short_med' => ['label' => 'Softgel Extension (short-med)', 'price' => 300],
        'softgel_long'      => ['label' => 'Softgel Extension (long)',      'price' => 350],
        'gel_polish_only'   => ['label' => 'Gel Polish Only (natural nails)', 'price' => 270],
    ],

    'full_set_designs' => [
        'french' => ['label' => 'French', 'price' => 200],
        'ombre'  => ['label' => 'Ombre',  'price' => 200],
        'cateye' => ['label' => 'Cat eye', 'price' => 200],
    ],

    // Per-nail add-ons: customer picks quantity (how many nails), 1-10
    'addons' => [
        'french'        => ['label' => 'French (per nail)',        'price' => 20],
        'ombre'         => ['label' => 'Ombre (per nail)',         'price' => 20],
        'nail_charms'   => ['label' => 'Nail Charms (per nail)',   'price' => 15],
        'rhinestones'   => ['label' => 'Rhinestones (per nail)',   'price' => 20],
        '3d_flower'     => ['label' => '3D Flower (per nail)',     'price' => 20],
        '3d_nail_art'   => ['label' => '3D Nail Art (per nail)',   'price' => 20],
        'nail_art'      => ['label' => 'Nail Art (per nail, avg)', 'price' => 25], // ranges 10-40, 25 used as default estimate
        'blooming_gel'  => ['label' => 'Blooming Gel (per nail)',  'price' => 20],
        'cat_eye_addon' => ['label' => 'Cat Eye (per nail)',       'price' => 20],
    ],

    'removal' => [
        'my_work'     => ['label' => 'Removal (my work)',     'price' => 150],
        'not_my_work' => ['label' => 'Removal (not my work)', 'price' => 200],
    ],

    'nail_colors' => [
        '#F7CAC9' => 'Blush Pink',
        '#FFFFFF' => 'Milky White',
        '#F5DEB3' => 'Nude Beige',
        '#C0C0C0' => 'Chrome Silver',
        '#4A5A6A' => 'Slate Gray',
        '#800020' => 'Wine Red',
        '#000000' => 'Black',
        '#FFD1DC' => 'Baby Pink',
        '#E6E6FA' => 'Lavender',
        'custom'  => 'Other / Custom (specify in notes)',
    ],

    'nail_shapes' => ['Round', 'Oval', 'Almond', 'Coffin', 'Stiletto', 'Square'],
];

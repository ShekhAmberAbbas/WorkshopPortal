<?php

return [
    'questions' => [
        ['key' => 'workshop_rating', 'label' => 'Rate this Seminar Workshop', 'type' => 'choice', 'options' => ['Excellent', 'Good', 'Average', 'Poor']],
        ['key' => 'previous_quaere_training', 'label' => 'Have you ever done training from Quaere before?', 'type' => 'choice', 'options' => ['Yes', 'No']],
        ['key' => 'trainee_interest', 'label' => 'Would you like to be a part of Quaere as a Trainee?', 'type' => 'choice', 'options' => ['Yes, of course', "No, I don't think so"]],
        ['key' => 'program_interest', 'label' => 'What program would you like to join?', 'type' => 'choice', 'options' => ['Summer Training/Internship', 'Industrial Training', 'Fullstack Training', 'Apprenticeship Training', 'Not Applicable']],
        ['key' => 'practical_oriented', 'label' => 'Did you like the Practical Oriented Workshop conducted by Quaere Experts in your College?', 'type' => 'choice', 'options' => ['Yes', 'No']],
        ['key' => 'industry_relevant', 'label' => 'Did you learn something new and industry-relevant in the workshop?', 'type' => 'choice', 'options' => ['Yes', 'No']],
        ['key' => 'speaker', 'label' => "Who is today's speaker?", 'type' => 'select', 'options' => ['Akshat']],
        ['key' => 'speaker_rating', 'label' => 'How much will you rate the speaker?', 'type' => 'choice', 'options' => ['Excellent', 'Good', 'Average', 'Poor']],
        ['key' => 'speaker_answered_questions', 'label' => 'Was the speaker able to answer your questions?', 'type' => 'choice', 'options' => ['Yes', 'No']],
        ['key' => 'comments', 'label' => 'Please Share Your Valuable Comments', 'type' => 'textarea', 'options' => []],
    ],
];

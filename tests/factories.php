<?php

$faktory->define(['thread', 'Hamedmehryar\Chat\Models\Thread'], function ($f) {
    $f->subject = "Sample thread";
});

$faktory->define(['message', 'Hamedmehryar\Chat\Models\Message'], function ($f) {
    $f->user_id = 1;
    $f->thread_id = 1;
    $f->body = "A message";
});

$faktory->define(['participant', 'Hamedmehryar\Chat\Models\Participant'], function ($f) {
    $f->user_id = 1;
    $f->thread_id = 1;
});

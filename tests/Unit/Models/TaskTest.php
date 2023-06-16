<?php

use App\Models\Task;

beforeEach(function () {
    $this->task = new Task();
});

test('check if task\'s fillable fields are correct', function () {
    $expectedFillable = ['user_id', 'title', 'description', 'completed_at'];

    expect($this->task->getFillable())->toEqual($expectedFillable);
});

test('check if task\'s cast fields are correct', function () {
    $expectedCasts = ['completed_at' => 'datetime', 'deleted_at' => 'datetime'];

    expect($this->task->getCasts())->toEqual($expectedCasts);
});

test('check if task\'s append fields are correct', function () {
    $expectedAppends = ['is_completed'];

    expect($this->task->getAppends())->toEqual($expectedAppends);
});

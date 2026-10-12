<?php

test('the application redirects root to login', function () {
    $response = $this->get('/');

    $response->assertRedirect('/login');
});

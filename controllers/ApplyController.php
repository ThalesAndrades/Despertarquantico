<?php

class ApplyController
{
    public function form(): void
    {
        $error = flash('error');
        $success = flash('success');
        view('apply/index', compact('error', 'success'));
    }

    public function submit(): void
    {
        CSRF::check();

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $whatsapp = trim($_POST['whatsapp'] ?? '');
        $moment = trim($_POST['moment'] ?? '');
        $goal = trim($_POST['goal'] ?? '');

        if ($name === '' || $email === '' || $whatsapp === '' || $moment === '' || $goal === '') {
            flash('error', 'Preencha todos os campos.');
            redirect('aplicacao');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'E-mail inválido.');
            redirect('aplicacao');
            return;
        }

        $id = Database::insert(
            "INSERT INTO high_ticket_applications (name, email, whatsapp, moment, goal, status)
             VALUES (?, ?, ?, ?, ?, 'new')",
            [$name, strtolower($email), $whatsapp, $moment, $goal]
        );

        $bookingUrl = trim((string) env('HIGH_TICKET_BOOKING_URL', ''));

        EventDispatcher::dispatch('high_ticket.application_submitted', [
            'email' => $email,
            'attributes' => [
                'name' => $name,
                'whatsapp' => $whatsapp,
            ],
            'properties' => [
                'application_id' => (int) $id,
                'moment' => $moment,
                'goal' => $goal,
                'source' => 'aplicacao',
                'booking_url' => $bookingUrl,
            ],
        ]);

        flash('success', 'Aplicação enviada. Verifique seu e-mail para os próximos passos.');
        redirect('aplicacao');
    }
}


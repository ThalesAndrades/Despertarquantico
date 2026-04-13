<?php

class LeadController
{
    public function newsletterForm(): void
    {
        $error = flash('error');
        $success = flash('success');
        view('leads/newsletter', compact('error', 'success'));
    }

    public function newsletterSubmit(): void
    {
        CSRF::check();

        $name = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $whatsapp = trim($_POST['whatsapp'] ?? '');
        $pain = trim($_POST['pain_primary'] ?? '');
        $stage = trim($_POST['stage'] ?? '');
        $archetype = trim($_POST['social_archetype'] ?? '');

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Informe um e-mail válido.');
            $_SESSION['old_input'] = compact('name', 'email', 'whatsapp', 'pain', 'stage', 'archetype');
            redirect('newsletter');
        }

        $utm = $this->readUtm($_POST);

        EventDispatcher::dispatch('lead.optin', [
            'email' => $email,
            'attributes' => [
                'name' => $name,
                'whatsapp' => $whatsapp,
                'pain_primary' => $pain,
                'stage' => $stage,
                'social_archetype' => $archetype,
            ],
            'properties' => array_merge($utm, [
                'source' => 'newsletter',
            ]),
        ]);

        if ($pain !== '') {
            EventDispatcher::dispatch('lead.optin.pain_' . $this->slug($pain), [
                'email' => $email,
                'attributes' => [
                    'name' => $name,
                    'whatsapp' => $whatsapp,
                    'pain_primary' => $pain,
                    'stage' => $stage,
                    'social_archetype' => $archetype,
                ],
                'properties' => array_merge($utm, [
                    'source' => 'newsletter',
                    'pain_primary' => $pain,
                ]),
            ]);
        }

        flash('success', 'Perfeito. Agora verifique seu e-mail para o primeiro passo.');
        unset($_SESSION['old_input']);
        redirect('newsletter');
    }

    public function diagnosticForm(): void
    {
        $error = flash('error');
        $success = flash('success');
        view('leads/diagnostico', compact('error', 'success'));
    }

    public function diagnosticSubmit(): void
    {
        CSRF::check();

        $name = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $whatsapp = trim($_POST['whatsapp'] ?? '');

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Informe um e-mail válido.');
            $_SESSION['old_input'] = compact('name', 'email', 'whatsapp');
            redirect('diagnostico');
        }

        $pain = trim($_POST['pain_primary'] ?? '');
        $stage = trim($_POST['stage'] ?? '');
        $archetype = trim($_POST['social_archetype'] ?? '');
        $moment = trim($_POST['moment'] ?? '');
        if ($pain === '' || $stage === '' || $moment === '') {
            flash('error', 'Preencha todos os campos obrigatórios.');
            $_SESSION['old_input'] = compact('name', 'email', 'whatsapp', 'pain', 'stage', 'archetype', 'moment');
            redirect('diagnostico');
        }

        $utm = $this->readUtm($_POST);

        EventDispatcher::dispatch('lead.diagnostic_completed', [
            'email' => $email,
            'attributes' => [
                'name' => $name,
                'whatsapp' => $whatsapp,
                'pain_primary' => $pain,
                'stage' => $stage,
                'social_archetype' => $archetype,
            ],
            'properties' => array_merge($utm, [
                'source' => 'diagnostico',
                'moment' => $moment,
            ]),
        ]);

        if ($pain !== '') {
            EventDispatcher::dispatch('lead.diagnostic_completed.pain_' . $this->slug($pain), [
                'email' => $email,
                'attributes' => [
                    'name' => $name,
                    'whatsapp' => $whatsapp,
                    'pain_primary' => $pain,
                    'stage' => $stage,
                    'social_archetype' => $archetype,
                ],
                'properties' => array_merge($utm, [
                    'source' => 'diagnostico',
                    'pain_primary' => $pain,
                    'moment' => $moment,
                ]),
            ]);
        }

        flash('success', 'Recebi seu diagnóstico. Verifique seu e-mail para os próximos passos.');
        unset($_SESSION['old_input']);
        redirect('diagnostico');
    }

    private function readUtm(array $src): array
    {
        return [
            'utm_source' => trim($src['utm_source'] ?? ($_GET['utm_source'] ?? '')),
            'utm_medium' => trim($src['utm_medium'] ?? ($_GET['utm_medium'] ?? '')),
            'utm_campaign' => trim($src['utm_campaign'] ?? ($_GET['utm_campaign'] ?? '')),
            'utm_content' => trim($src['utm_content'] ?? ($_GET['utm_content'] ?? '')),
            'utm_term' => trim($src['utm_term'] ?? ($_GET['utm_term'] ?? '')),
        ];
    }

    private function slug(string $value): string
    {
        $slug = slugify($value);
        $slug = str_replace('-', '_', $slug);
        return $slug !== '' ? $slug : 'unknown';
    }
}


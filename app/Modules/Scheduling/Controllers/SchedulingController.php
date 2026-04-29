<?php

declare(strict_types=1);

namespace App\Modules\Scheduling\Controllers;

use App\Modules\Core\Base\BaseController;
use App\Modules\Scheduling\Repositories\SchedulingRepository;

class SchedulingController extends BaseController
{
    private $schedulingRepository;

    public function __construct()
    {
        parent::__construct();
        $this->schedulingRepository = new SchedulingRepository();
    }

    public function index(): void
    {
        // Exemplo de busca de dados via repository do módulo
        $agendamentos = $this->schedulingRepository->getAll();

        $this->render('scheduling/index.php', [
            'title' => 'Gestão de Agendamentos',
            'agendamentos' => $agendamentos
        ]);
    }
}

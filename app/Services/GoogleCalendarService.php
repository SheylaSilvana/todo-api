<?php

namespace App\Services;

use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Carbon\Carbon;

class GoogleCalendarService
{
    protected $client;
    protected $calendar;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/google-calendar/service_account_credentials.json')); // JSON da Service Account
        $this->client->useApplicationDefaultCredentials(); // Usando as credenciais da conta de serviço
        $this->client->addScope(Calendar::CALENDAR);

        $this->client->setHttpClient(new \GuzzleHttp\Client([
            'verify' => false,
        ]));

        $this->calendar = new Calendar($this->client);
    }

    // Criar evento no Google Calendar
    public function createEvent($task, $startDateTime, $endDateTime)
    {
        $email = env('MAIL_USERNAME');
        $event = new Event([
            'summary' => $task->title,
            'description' => $task->description,
            'start' => [
                'dateTime' => $startDateTime->toRfc3339String(),
                'timeZone' => 'America/Sao_Paulo',
            ],
            'end' => [
                'dateTime' => $endDateTime->toRfc3339String(),
                'timeZone' => 'America/Sao_Paulo',
            ],
        ]);

        return $this->calendar->events->insert($email, $event);
    }

    // Atualizar evento no Google Calendar
    public function updateEvent($eventId, $task, $startDateTime, $endDateTime)
    {
        $email = env('MAIL_USERNAME');
        $event = $this->calendar->events->get($email, $eventId);
        $event->setSummary($task->title);
        $event->setDescription($task->description);
        $event->setStart(new \Google\Service\Calendar\EventDateTime([
            'dateTime' => $startDateTime->toRfc3339String(),
            'timeZone' => 'America/Sao_Paulo',
        ]));
        $event->setEnd(new \Google\Service\Calendar\EventDateTime([
            'dateTime' => $endDateTime->toRfc3339String(),
            'timeZone' => 'America/Sao_Paulo',
        ]));

        return $this->calendar->events->update($email, $eventId, $event);
    }

    // Excluir evento no Google Calendar
    public function deleteEvent($eventId)
    {
        $email = env('MAIL_USERNAME');
        return $this->calendar->events->delete($email, $eventId);
    }
}

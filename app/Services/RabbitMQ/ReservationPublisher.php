<?php

namespace App\Services\RabbitMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ReservationPublisher
{
    public function publishReservationCreated($reservation)
    {
        $connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_USER'),
            env('RABBITMQ_PASSWORD')
        );

        $channel = $connection->channel();

        $channel->exchange_declare(
            'travelnest',
            'topic',
            false,
            true,
            false
        );

        $data = [
            'reservationId' => $reservation->id,
            'accommodationId' => $reservation->accommodation_id,
            'startDate' => $reservation->start_date,
            'endDate' => $reservation->end_date,
            'status' => $reservation->status,
        ];

        $msg = new AMQPMessage(json_encode($data));

        $channel->basic_publish(
            $msg,
            'travelnest',
            'reservation.created'
        );

        $channel->close();
        $connection->close();
    }

    public function publishReservationCanceled($reservation)
    {
        $connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_USER'),
            env('RABBITMQ_PASSWORD')
        );

        $channel = $connection->channel();

        $channel->exchange_declare(
            'travelnest',
            'topic',
            false,
            true,
            false
        );

        $data = $reservation->id;

        $msg = new AMQPMessage($data);

        $channel->basic_publish(
            $msg,
            'travelnest',
            'reservation.canceled'
        );

        $channel->close();
        $connection->close();
    }
}
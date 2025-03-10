<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use App\Models\Attendee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $events = Event::all();

        foreach ($users as $user) {
            $attendeeEvent = $events->random(rand(1,3));

            foreach ($attendeeEvent as $event) {
                Attendee::create([
                    "user_id"=> $user->id,
                    "event_id" => $event->id,
                ]);
            }

        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    use CanLoadRelationships;
    use AuthorizesRequests;
    private array $relations = ['user'];

    public function index(Event $event)
    {
        $this->authorize('viewAny', Attendee::class);

        $attendees = $this->loadRelationships(
            $event->attendees()->latest()
        );

        return AttendeeResource::collection( $attendees->paginate());
    }

    public function store(Request $request, Event $event)
    {
        $this->authorize('create', Attendee::class);
        $attendee = $this->loadRelationships(
            $event->attendees()->create([
                "user_id" => 1
            ])
        );
        return new AttendeeResource($attendee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        $this->authorize('view', Attendee::class);
        return new AttendeeResource($this->loadRelationships($attendee));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, Attendee $attendee)
    {
        $this->authorize('delete', Attendee::class);
        $attendee->delete();
        return response(status: 204);
    }
}

<?php

namespace App\Http\Resources\V1;

use App\Models\events;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A tourism site or an event (same shape; events add dates).
 *
 * @mixin \App\Models\TourismSite|\App\Models\events
 */
class ExperienceResource extends JsonResource
{
    public bool $detail = false;

    public function detailed(): static
    {
        $this->detail = true;

        return $this;
    }

    public function toArray(Request $request): array
    {
        $isEvent = $this->resource instanceof events;
        $type = $isEvent ? 'event' : 'site';

        $data = [
            'id' => $this->getKey(),
            'kind' => $type,
            'name' => $isEvent ? $this->EventName : $this->SiteName,
            'city' => $this->City,
            'country' => $this->Country,
            'summary' => $this->ShortDescription,
            'photo' => Present::photo($type, $this->resource),
        ];
        if ($isEvent) {
            $data += ['starts_on' => Present::date($this->StartDate), 'ends_on' => Present::date($this->EndDate)];
        }

        return $this->detail ? $data + [
            'description' => $this->Description,
            'gallery' => Present::gallery($type, $this->resource),
        ] : $data;
    }
}

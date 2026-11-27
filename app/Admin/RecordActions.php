<?php

namespace App\Admin;

use App\Support\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

/** Approve and delete, shared by the list and the edit form. Each checks permissions itself. */
final class RecordActions
{
    /**
     * Set the approval column of several records.
     *
     * @param  class-string<Resource>  $resource
     * @return int records changed
     */
    public static function approve(string $resource, array $ids, bool $on): int
    {
        Gate::authorize('admin.access');
        $approval = $resource::approval();
        if (! $approval || $ids === []) {
            return 0;
        }

        $count = $resource::query()->whereKey($ids)->update([$approval['column'] => $on ? $approval['on'] : $approval['off']]);
        CacheBuster::flush($resource::cacheKeys());

        return $count;
    }

    /**
     * Delete a record with its dependent rows and photos. Admins only.
     *
     * @param  class-string<Resource>  $resource
     * @return string|null an error message when the delete was refused
     */
    public static function delete(string $resource, Model $record): ?string
    {
        Gate::authorize('admin.manage');

        if ($reason = $resource::preventDelete($record)) {
            return $reason;
        }

        $attributes = $record->getAttributes();
        DB::transaction(function () use ($resource, $record, $attributes) {
            if ($checklist = $resource::checklist()) {
                $match = ($checklist['match'])($attributes);
                if (array_filter($match, fn ($v) => $v !== null && $v !== '') !== []) {
                    $checklist['model']::query()->where($match)->delete();
                }
            }
            $record->delete();
            $resource::afterDelete($record);
        });

        if ($type = $resource::media()) {
            MediaFiles::deleteAll($type, Media::baseName($type, $record), MediaFiles::count($type, $record));
        }
        CacheBuster::flush($resource::cacheKeys());

        return null;
    }
}

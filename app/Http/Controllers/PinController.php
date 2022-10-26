<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Resource;
use App\Models\Pin;
use Illuminate\Support\Facades\Validator;

class PinController extends Controller
{
    public function pinProject(Request $request)
    {
        $input = $request->all();

        Validator::make($input, [
            'pinableId' => ['required'],
        ])->validateWithBag('pin');

        $project = Project::query()->findOrFail($input['pinableId']);

        $this->handlePin(Project::class, $project, $request->user());

        return back();
    }

    public function pinResource(Request $request)
    {
        $input = $request->all();

        Validator::make($input, [
            'pinableId' => ['required'],
        ])->validateWithBag('pin');

        $resource = Resource::query()->findOrFail($input['pinableId']);

        $this->handlePin(Resource::class, $resource, $request->user());

        return back();
    }

    private function handlePin($type, $pinable, $user)
    {
        $existing_pin = Pin::withTrashed()->wherePinableType($type)->wherePinableId($pinable->id)->whereUserId($user->id)->first();

        if (is_null($existing_pin)) {
            Pin::create([
                'user_id' => $user->id,
                'pinable_id' => $pinable->id,
                'pinable_type' => $type,
            ]);
        } else {
            if (is_null($existing_pin->deleted_at)) {
                $existing_pin->delete();
            } else {
                $existing_pin->restore();
            }
        }
    }
}

<?php

namespace Emiliogrv\NovaBatchLoad\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Nova\Actions\ActionEvent;
use Laravel\Nova\Http\Requests\CreateResourceRequest;
use Laravel\Nova\Http\Requests\NovaRequest;

class ResourceStoreController extends Controller
{
    /**
     * Create a new resource.
     *
     * @param  \Laravel\Nova\Http\Requests\CreateResourceRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(CreateResourceRequest $request)
    {
        $resource = $request->resource();

        $resource::authorizeToCreate($request);

        $this->validateData($request);

        $this->validateForCreation($request, $resource);

        DB::transaction(function () use ($request, $resource) {
            foreach ($request->data as $key => $array) {
                $request->replace($array);

                [$model, $callbacks] = $resource::fill(
                    $request, $resource::newModel()
                );

                if ($request->viaRelationship()) {
                    $request->findParentModelOrFail()
                        ->{$request->viaRelationship}()
                        ->save($model);
                } else {
                    $model->save();
                }

                ActionEvent::forResourceCreate($request->user(), $model)->save();

                collect($callbacks)->each->__invoke();
            }
        });

        return response()->json([
            'redirect' => '/resources/' . $resource::uriKey(),
        ], 201);
    }

    /**
     * Create a validator instance for a resource creation request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Illuminate\Validation\Validator
     */
    public static function validateData(NovaRequest $request): void
    {
        Validator::make($request->all(), [
            'data' => 'required|array|min:1',
        ])->validate();
    }

    /**
     * Validate a resource creation request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return void
     */
    public static function validateForCreation(NovaRequest $request, $resource): void
    {
        $rules = $resource::rulesForCreation($request);
        $dataRules = [];

        foreach ($rules as $key => $value) {
            foreach ($value as $rule) {
                if (is_string($rule) && Str::contains($rule, 'unique')) {
                    $value[] = 'distinct';
                }
            }

            $dataRules["data.*.$key"] = $value;
        }

        Validator::make($request->all(), $dataRules)->validate();
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Venue\StoreTemplate;
use App\Http\Resources\TemplateResource;
use App\Models\Template;
use App\Models\Venue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TemplateController extends ApiController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('hasPermissions:VIEW_TEMPLATES', only: ['index', 'show']),
            new Middleware('hasPermissions:MANAGE_TEMPLATES', only: ['store', 'update', 'destroy']),
        ];
    }

    /**
     * Display a listing of the resource
     *
     * @param Request $request
     * @param Venue $venue
     * @return JsonResponse
     */
    public function index(Request $request, Venue $venue): JsonResponse
    {
        $templates = $venue->templates()->where('visible', true);

        if($request->has('q'))
            $templates = $templates->where('name', 'ILIKE', "%{$request->q}%");

        $templates = $templates->paginate(env('POSTS_PER_PAGE'));

        return $this->success(
            TemplateResource::collection($templates),
            collect($templates)->only(['from', 'to', 'total', 'per_page', 'last_page', 'current_page'])->toArray(),
        );
    }

    /**
     * Display a list of all the templates
     *
     * @param Request $request
     * @param Venue $venue
     * @return JsonResponse
     */
    public function list(Request $request, Venue $venue): JsonResponse
    {
        $templates = $venue->templates()->get();
        return $this->success(TemplateResource::collection($templates));
    }

    /**
     * Show the specific resource
     *
     * @param Venue $venue
     * @param Template $template
     * @return JsonResponse
     */
    public function show(Venue $venue, Template $template): JsonResponse
    {
        return $this->success(new TemplateResource($template));
    }

    /**
     * Store a new resource
     *
     * @param StoreTemplate $request
     * @param Venue $venue
     * @return JsonResponse
     */
    public function store(StoreTemplate $request, Venue $venue): JsonResponse
    {
        $template = $venue->templates()->create($request->validated());
        return $this->success(new TemplateResource($template));
    }

    /**
     * Update the specific resource
     *
     * @param StoreTemplate $request
     * @param Venue $venue
     * @param Template $template
     * @return JsonResponse
     */
    public function update(StoreTemplate $request, Venue $venue, Template $template): JsonResponse
    {
        $template->update($request->validated());
        return $this->success(new TemplateResource($template));
    }

    /**
     * Delete the specific resource
     *
     * @param Venue $venue
     * @param Template $template
     * @return JsonResponse
     */
    public function destroy(Venue $venue, Template $template): JsonResponse
    {
        $template->delete();
        return $this->success();
    }
}

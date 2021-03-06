<?php

namespace Lavalite\Calendar\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;
use Lavalite\Calendar\Interfaces\CalendarRepositoryInterface;

class CalendarController extends BaseController
{
    /**
     * Constructor.
     *
     * @param type \Lavalite\Calendar\Interfaces\CalendarRepositoryInterface $calendar
     *
     * @return type
     */
    public function __construct(CalendarRepositoryInterface $calendar)
    {
        $this->repository = $calendar;
        $this->middleware('web');
        $this->setupTheme(config('theme.themes.public.theme'), config('theme.themes.public.layout'));
        parent::__construct();
    }

    /**
     * Show calendar's list.
     *
     * @param string $slug
     *
     * @return response
     */
    protected function index()
    {
        $calendars = $this->repository
            ->pushCriteria(new \Lavalite\Calendar\Repositories\Criteria\CalendarPublicCriteria())
            ->scopeQuery(function($query){
                return $query->orderBy('id','DESC');
            })->paginate();

        return $this->theme->of('calendar::public.calendar.index', compact('calendars'))->render();
    }

    /**
     * Show calendar.
     *
     * @param string $slug
     *
     * @return response
     */
    protected function show($slug)
    {
        $calendar = $this->repository->scopeQuery(function($query) use ($slug) {
            return $query->orderBy('id','DESC')
                         ->where('slug', $slug);
        })->first(['*']);

        return $this->theme->of('calendar::public.calendar.show', compact('calendar'))->render();
    }
}

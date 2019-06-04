<?php

namespace App;

use App\Components\Traits\Filters\ShiftsFilter;
use Illuminate\Database\Eloquent\{Builder, Model, Relations\BelongsToMany};
use Carbon\Carbon;
use DB;

class Shift extends Model
{
    use ShiftsFilter;

    public const MEALS_TITLES = [
        1 => 'Breakfast',
        2 => 'Lunch',
        3 => 'Dinner'
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'date', 'city_id', 'start', 'end', 'meal', 'company_id'
    ];

    /**
     * @var int
     */
    protected $perPage = 10;

    /**
     * @return BelongsToMany
     */
    public function drivers ()
    {
        return $this->belongsToMany(Driver::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city ()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company ()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Set shifts company id
     */
    public function setCompanyId ()
    {
        $this->company_id = auth()->user()->company_id;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeByRole (Builder $query)
    {
        return $this->byRole($query);
    }

    /**
     * @param Builder $query
     * @param $request
     * @return Builder
     */
    public function scopeIndexAll (Builder $query, $request)
    {
        $query->byRole()
            ->select(
                'id',
                'end',
                'date',
                'meal',
                'start',
                'city_id'
            )->when($request->city, static function ($query) use ($request) {
                return $query->where('city_id', $request->city);

            })->when($request->date, static function ($query) use ($request) {
                // $date = Carbon::parse($request->date)->format('Y-d-m');

                return $query->where('date', 'like', "%{$request->date}%");

            })->when($request->sort, static function ($query) use ($request) {

                if ($request->sort === 'city') {
                    return $query->orderBy('city_id', $request->input('order', 'DESC'));
                } else {
                    return $query->orderBy($request->sort, $request->input('order', 'DESC'));
                }
            });

        if (!$request->sort) {
            return $query->orderBy('id', 'DESC');
        }

        return $query;
    }

    /**
     * @param Builder $query
     * @param $request
     * @return Builder
     */
    public function scopeShiftsForDriver (Builder $query, $request)
    {
        $query->whereNull('company_id')
        ->orWhere('company_id', auth()->user()->company_id)
        ->where('date', '>=', Carbon::now()->format('Y-m-d'))->orderBy('date')
        ->when($request->city_id, static function ($query) use ($request){
            $query->whereIn('city_id', $request->city_id);
        })
        ->when($request->month, static function ($query) use ($request){
            $query->whereIn(DB::raw('DATE_FORMAT(date, "%c")'), $request->month);
        });

        return $query;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getTitles ()
    {
        return collect(self::MEALS_TITLES)->map(static function ($value, $key) {
            return [
                'id' => $key,
                'title' => $value
            ];
        });
    }
}

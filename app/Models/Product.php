<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'description', 'sale_price', 'default_price', 'sale_end', 'label', 'main_image'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'sale_end' => 'datetime'
    ];

    /**
     * Attribute: get main image path.
     *
     * @return integer
     */
    public function getMainImagePathAttribute()
    {
        return $this->main_image ? Storage::url($this->main_image) : '';
    }

    /**
     * Attribute: get lectures count.
     *
     * @return integer
     */
    public function getLecturesCountAttribute()
    {
        $count = 0;
        if($this->lectures()->exists()) $count = $this->lectures()->count();
        return $count;
    }

    /**
     * Attribute: get lectures count name.
     *
     * @return string
     */
    public function getLecturesCountNameAttribute()
    {
        return ($this->lectures_count == 1 || ((strlen(strval($this->lectures_count)) <= 1 || strval($this->lectures_count)[-2] != 1) && strval($this->lectures_count)[-1] == 1)) ? 'Занятиe' : (in_array($this->lectures_count, [2, 3, 4]) || ((strlen(strval($this->lectures_count)) <= 1 || strval($this->lectures_count)[-2] != 1) && (in_array($this->lectures_count, [2, 3, 4]))) ? 'Занятия' : 'Занятий'); //Задания
    }

    /**
     * Attribute: get lessons count name.
     *
     * @return string
     */
    public function getTasksCountNameAttribute()
    {
        return ($this->tasks_count == 1 || ((strlen(strval($this->tasks_count)) <= 1 || strval($this->tasks_count)[-2] != 1) && strval($this->tasks_count)[-1] == 1)) ? 'Заданиe' : (in_array($this->tasks_count, [2, 3, 4]) || ((strlen(strval($this->tasks_count)) <= 1 || strval($this->tasks_count)[-2] != 1) && (in_array($this->tasks_count, [2, 3, 4]))) ? 'Задания' : 'Заданий'); //
    }

    /**
     * Attribute: get tasks count.
     *
     * @return integer
     */
    public function getTasksCountAttribute()
    {
        $count = 0;
        if($this->tasks()->exists()) $count = $this->tasks()->count();
        return $count;
    }

    /**
     * Relation: get all lectures.
     *
     * @return HasMany
     */
    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }

    /**
     * Relation: get all tasks.
     *
     * @return HasManyThrough
     */
    public function tasks()
    {
        return $this->hasManyThrough(Task::class, Lecture::class);
    }

    /**
     * Register the media collections.
     *
     * @return void
     *
     * @throws \Exception
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('multi_files_collection')
            ->acceptsMimeTypes(['application/msword', 'application/pdf', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.wordprocessing', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'image/bmp', 'image/cis-cod', 'image/gif', 'image/ief', 'image/jpeg', 'image/pipeg', 'image/svg+xml', 'image/tiff', 'image/tiff', 'image/x-cmu-raster', 'image/x-cmx', 'image/x-icon', 'image/x-portable-anymap', 'image/x-portable-bitmap', 'image/x-portable-graymap', 'image/x-portable-pixmap', 'image/x-rgb', 'image/x-xbitmap', 'image/x-xpixmap', 'image/x-xwindowdump', 'image/png']);
    }

}
